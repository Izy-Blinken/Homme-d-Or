<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$current_superadmin_id = $_SESSION['superadmin_id'] ?? null;
$current_admin_id = $_SESSION['admin_id'] ?? null;

$convos = [];

if ($isSuperadmin) {

    // all customer convos
    $q = mysqli_query($conn,
        "SELECT u.user_id, u.fname, u.lname,
                lm.content AS last_message, lm.sent_at AS last_time,
                SUM(CASE WHEN m.is_read = 0 AND m.receiver_type = 'superadmin' THEN 1 ELSE 0 END) AS unread
         FROM users u
         JOIN admin_messages m ON (
             (m.sender_type = 'user' AND m.sender_id = u.user_id) OR
             (m.receiver_type = 'user' AND m.receiver_id = u.user_id)
         )
         JOIN admin_messages lm ON lm.message_id = (
             SELECT message_id FROM admin_messages
             WHERE (sender_type = 'user' AND sender_id = u.user_id)
                OR (receiver_type = 'user' AND receiver_id = u.user_id)
             ORDER BY sent_at DESC LIMIT 1
         )
         WHERE u.user_id NOT IN (
             SELECT user_id FROM chatbot_sessions WHERE escalated = 1
         )
         GROUP BY u.user_id
         ORDER BY last_time DESC");

    while ($row = mysqli_fetch_assoc($q)) {

        $convos[] = [
            'type' => 'user',
            'id' => $row['user_id'],
            'name' => $row['fname'] . ' ' . $row['lname'],
            'last_message' => $row['last_message'],
            'last_time' => $row['last_time'],
            'unread' => (int) $row['unread'],
        ];

    }

    // all admin convos
    $q2 = mysqli_query($conn,
        "SELECT a.admin_id, u.fname, u.lname,
                lm.content AS last_message, lm.sent_at AS last_time,
                SUM(CASE WHEN m.is_read = 0 AND m.receiver_type = 'superadmin' THEN 1 ELSE 0 END) AS unread
         FROM admins a
         JOIN users u ON a.user_id = u.user_id
         JOIN admin_messages m ON (
             (m.sender_type = 'admin' AND m.sender_id = a.admin_id AND m.receiver_type = 'superadmin' AND m.receiver_id = '$current_superadmin_id') OR
             (m.receiver_type = 'admin' AND m.receiver_id = a.admin_id AND m.sender_type = 'superadmin' AND m.sender_id = '$current_superadmin_id')
         )
         JOIN admin_messages lm ON lm.message_id = (
             SELECT message_id FROM admin_messages
             WHERE (sender_type = 'admin' AND sender_id = a.admin_id AND receiver_type = 'superadmin' AND receiver_id = '$current_superadmin_id')
                OR (receiver_type = 'admin' AND receiver_id = a.admin_id AND sender_type = 'superadmin' AND sender_id = '$current_superadmin_id')
             ORDER BY sent_at DESC LIMIT 1
         )
         GROUP BY a.admin_id
         ORDER BY last_time DESC");

    while ($row = mysqli_fetch_assoc($q2)) {

        $convos[] = [
            'type' => 'admin',
            'id' => $row['admin_id'],
            'name' => 'Admin ' . $row['fname'] . ' ' . $row['lname'],
            'last_message' => $row['last_message'],
            'last_time' => $row['last_time'],
            'unread' => (int) $row['unread'],
        ];

    }

    // escalated sessions — all, assigned or not
    $q3 = mysqli_query($conn,
        "SELECT cs.session_id, u.user_id, u.fname, u.lname,
                cl.chatbot_message AS last_message, cl.created_at AS last_time
         FROM chatbot_sessions cs
         JOIN users u ON cs.user_id = u.user_id
         LEFT JOIN chatbot_logs cl ON cl.log_id = (
             SELECT log_id FROM chatbot_logs
             WHERE session_id = cs.session_id
             ORDER BY created_at DESC LIMIT 1
         )
         WHERE cs.escalated = 1
         ORDER BY last_time DESC");

    while ($row = mysqli_fetch_assoc($q3)) {

        $convos[] = [
            'type' => 'escalated',
            'session_id' => $row['session_id'],
            'id' => $row['user_id'],
            'name' => $row['fname'] . ' ' . $row['lname'],
            'last_message' => $row['last_message'],
            'last_time' => $row['last_time'],
            'unread' => 0,
        ];
    }

} else {

    // reg admin — own customer convos
    $q = mysqli_query($conn,
        "SELECT u.user_id, u.fname, u.lname,
                lm.content AS last_message, lm.sent_at AS last_time,
                SUM(CASE WHEN m.is_read = 0 AND m.receiver_type = 'admin' AND m.receiver_id = '$current_admin_id' THEN 1 ELSE 0 END) AS unread
         FROM users u
         JOIN admin_messages m ON (
             (m.sender_type = 'user' AND m.sender_id = u.user_id AND m.receiver_type = 'admin' AND m.receiver_id = '$current_admin_id') OR
             (m.receiver_type = 'user' AND m.receiver_id = u.user_id AND m.sender_type = 'admin' AND m.sender_id = '$current_admin_id')
         )
         JOIN admin_messages lm ON lm.message_id = (
             SELECT message_id FROM admin_messages
             WHERE (sender_type = 'user' AND sender_id = u.user_id AND receiver_type = 'admin' AND receiver_id = '$current_admin_id')
                OR (receiver_type = 'user' AND receiver_id = u.user_id AND sender_type = 'admin' AND sender_id = '$current_admin_id')
             ORDER BY sent_at DESC LIMIT 1
         )
         GROUP BY u.user_id
         ORDER BY last_time DESC");

    while ($row = mysqli_fetch_assoc($q)) {

        $convos[] = [
            'type' => 'user',
            'id' => $row['user_id'],
            'name' => $row['fname'] . ' ' . $row['lname'],
            'last_message' => $row['last_message'],
            'last_time' => $row['last_time'],
            'unread' => (int) $row['unread'],
        ];
    }

    // reg admin — superadmin convos
    $q2 = mysqli_query($conn,
        "SELECT s.superadmin_id, s.username,
                lm.content AS last_message, lm.sent_at AS last_time,
                SUM(CASE WHEN m.is_read = 0 AND m.receiver_type = 'admin' AND m.receiver_id = '$current_admin_id' THEN 1 ELSE 0 END) AS unread
         FROM superadmins s
         JOIN admin_messages m ON (
             (m.sender_type = 'superadmin' AND m.sender_id = s.superadmin_id AND m.receiver_type = 'admin' AND m.receiver_id = '$current_admin_id') OR
             (m.receiver_type = 'superadmin' AND m.receiver_id = s.superadmin_id AND m.sender_type = 'admin' AND m.sender_id = '$current_admin_id')
         )
         JOIN admin_messages lm ON lm.message_id = (
             SELECT message_id FROM admin_messages
             WHERE (sender_type = 'superadmin' AND sender_id = s.superadmin_id AND receiver_type = 'admin' AND receiver_id = '$current_admin_id')
                OR (receiver_type = 'superadmin' AND receiver_id = s.superadmin_id AND sender_type = 'admin' AND sender_id = '$current_admin_id')
             ORDER BY sent_at DESC LIMIT 1
         )
         GROUP BY s.superadmin_id
         ORDER BY last_time DESC");

    while ($row = mysqli_fetch_assoc($q2)) {

        $convos[] = [
            'type' => 'superadmin',
            'id' => $row['superadmin_id'],
            'name' => 'Admin ' . $row['username'],
            'last_message' => $row['last_message'],
            'last_time' => $row['last_time'],
            'unread' => (int) $row['unread'],
        ];

    }
}

usort($convos, fn($a, $b) => strtotime($b['last_time']) - strtotime($a['last_time']));

echo json_encode($convos);