<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$current_superadmin_id = $_SESSION['superadmin_id'] ?? null;
$current_admin_id = $_SESSION['admin_id'] ?? null;

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$admin_id = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : null;
$superadmin_id = isset($_GET['superadmin_id']) ? intval($_GET['superadmin_id']) : null;
$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : null;

if (!$user_id && !$admin_id && !$superadmin_id && !$session_id) {
    echo json_encode([]);
    exit;
}

$messages = [];

// escalated chatbot session. show chatbot history first
if ($session_id) {

    $logs = mysqli_query($conn,
        "SELECT sender, chatbot_message AS content, created_at AS sent_at
         FROM chatbot_logs
         WHERE session_id = '$session_id'
         ORDER BY created_at ASC");

    while ($row = mysqli_fetch_assoc($logs)) {

        $messages[] = [
            'message_id' => null,
            'sender_name' => $row['sender'] === 'user' ? 'Customer' : 'Chatbot',
            'sender_type' => $row['sender'] === 'user' ? 'user' : 'bot',
            'content' => $row['content'],
            'sent_at' => $row['sent_at'],
            'is_bot' => $row['sender'] === 'bot',
        ];
        
    }

    // for sadmin to view radmin's reply(kay cust)
    $session_user_id = null;
    if (!empty($messages)) {

        foreach ($messages as $m) {

            if ($m['sender_type'] === 'user') {
                $session_user_id = null;
                break;
            }

        }

    }

    $cs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM chatbot_sessions WHERE session_id = '$session_id'"));
    $session_user_id = $cs['user_id'] ?? null;

    $q = mysqli_query($conn, "SELECT * FROM admin_messages WHERE session_id = '$session_id'
         OR (receiver_type = 'user' AND receiver_id = '$session_user_id' AND sender_type = 'admin')
         OR (sender_type = 'user' AND sender_id = '$session_user_id' AND receiver_type = 'admin')
         ORDER BY sent_at ASC");

    while ($row = mysqli_fetch_assoc($q)) {

        $messages[] = [ //pause muna.
            'message_id' => $row['message_id'],
            'sender_name' => $row['sender_name'],
            'sender_type' => $row['sender_type'],
            'sender_id' => $row['sender_id'],
            'content' => $row['content'],
            'sent_at' => $row['sent_at'],
            'is_bot' => false,
        ];

    }

    // sort by sent_at
    usort($messages, fn($a, $b) => strtotime($a['sent_at']) - strtotime($b['sent_at']));


    echo json_encode(['type' => 'escalated', 'messages' => $messages]);
    exit;
}

if ($user_id) {

    if ($isSuperadmin) {

        mysqli_query($conn,
            "UPDATE admin_messages SET is_read = 1
             WHERE receiver_type = 'superadmin' AND receiver_id = '$current_superadmin_id'
               AND sender_type = 'user' AND sender_id = '$user_id' AND is_read = 0");

        $q = mysqli_query($conn,
            "SELECT * FROM admin_messages
             WHERE (sender_type = 'user' AND sender_id = '$user_id')
                OR (receiver_type = 'user' AND receiver_id = '$user_id')
             ORDER BY sent_at ASC");

    } else {

        mysqli_query($conn,
            "UPDATE admin_messages SET is_read = 1
             WHERE receiver_type = 'admin' AND receiver_id = '$current_admin_id'
               AND sender_type = 'user' AND sender_id = '$user_id' AND is_read = 0");

        $q = mysqli_query($conn,
            "SELECT * FROM admin_messages
             WHERE (sender_type = 'user' AND sender_id = '$user_id' AND receiver_type = 'admin' AND receiver_id = '$current_admin_id')
                OR (receiver_type = 'user' AND receiver_id = '$user_id' AND sender_type = 'admin' AND sender_id = '$current_admin_id')
             ORDER BY sent_at ASC");
    }

} elseif ($admin_id) {

    mysqli_query($conn,
        "UPDATE admin_messages SET is_read = 1
         WHERE receiver_type = 'superadmin' AND receiver_id = '$current_superadmin_id'
           AND sender_type = 'admin' AND sender_id = '$admin_id' AND is_read = 0");

    $q = mysqli_query($conn,
        "SELECT * FROM admin_messages
         WHERE (sender_type = 'admin' AND sender_id = '$admin_id' AND receiver_type = 'superadmin' AND receiver_id = '$current_superadmin_id')
            OR (receiver_type = 'admin' AND receiver_id = '$admin_id' AND sender_type = 'superadmin' AND sender_id = '$current_superadmin_id')
         ORDER BY sent_at ASC");

} else {

    mysqli_query($conn,
        "UPDATE admin_messages SET is_read = 1
         WHERE receiver_type = 'admin' AND receiver_id = '$current_admin_id'
           AND sender_type = 'superadmin' AND sender_id = '$superadmin_id' AND is_read = 0");

    $q = mysqli_query($conn,
        "SELECT * FROM admin_messages
         WHERE (sender_type = 'superadmin' AND sender_id = '$superadmin_id' AND receiver_type = 'admin' AND receiver_id = '$current_admin_id')
            OR (receiver_type = 'superadmin' AND receiver_id = '$superadmin_id' AND sender_type = 'admin' AND sender_id = '$current_admin_id')
         ORDER BY sent_at ASC");
}

while ($row = mysqli_fetch_assoc($q)) {

    $messages[] = [
        'message_id' => $row['message_id'],
        'sender_name' => $row['sender_name'],
        'sender_type' => $row['sender_type'],
        'sender_id' => $row['sender_id'],
        'content' => $row['content'],
        'sent_at' => $row['sent_at'],
        'is_bot' => false,
    ];

}

echo json_encode(['type' => 'normal', 'messages' => $messages]);