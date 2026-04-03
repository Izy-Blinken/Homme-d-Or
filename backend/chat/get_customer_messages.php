<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['messages' => []]);
    exit;
}

$session_id = intval($_GET['session_id'] ?? 0);
$last_id = intval($_GET['last_id'] ?? 0);
$history = !empty($_GET['history']);

if (!$session_id) {
    echo json_encode(['messages' => []]);
    exit;
}

$messages = [];

if ($history) {

    $logs = mysqli_query($conn, "SELECT NULL AS message_id, chatbot_message AS content,
                sender AS sender_type, NULL AS sender_name, created_at AS sent_at
         FROM chatbot_logs
         WHERE session_id = '$session_id'
         ORDER BY created_at ASC");

    while ($row = mysqli_fetch_assoc($logs)) {

        $messages[] = [
            'message_id' => null,
            'content' => $row['content'],
            'sender_name' => $row['sender_type'] === 'user' ? 'You' : 'Chatbot',
            'sender_type' => $row['sender_type'],
            'sent_at' => $row['sent_at'],
        ];
    }

    $q = mysqli_query($conn, "SELECT message_id, content, sender_name, sender_type, sent_at
         FROM admin_messages
         WHERE session_id = '$session_id'
            OR (receiver_id = '$user_id' AND receiver_type = 'user')
         ORDER BY sent_at ASC");

    while ($row = mysqli_fetch_assoc($q)) {
        $messages[] = $row;
    }

    usort($messages, fn($a, $b) => strtotime($a['sent_at']) - strtotime($b['sent_at']));

    echo json_encode(['messages' => $messages]);
    exit;
}

$q = mysqli_query($conn, "SELECT message_id, content, sender_name, sent_at FROM admin_messages
     WHERE (session_id = '$session_id' OR (receiver_id = '$user_id' AND receiver_type = 'user'))
       AND sender_type != 'user'
       AND message_id > '$last_id'
     ORDER BY sent_at ASC");

while ($row = mysqli_fetch_assoc($q)) {
    $messages[] = $row;
}

echo json_encode(['messages' => $messages]);