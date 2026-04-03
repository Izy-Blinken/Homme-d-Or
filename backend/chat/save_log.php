<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$session_id = intval($data['session_id'] ?? 0);
$sender = $data['sender'] ?? '';
$message = $data['message'] ?? '';

if (!$session_id || !in_array($sender, ['user', 'bot']) || !$message) {
    echo json_encode(['success' => false]);
    exit;
}

$safe_msg = mysqli_real_escape_string($conn, $message);

mysqli_query($conn, "INSERT INTO chatbot_logs (session_id, sender, chatbot_message)
     VALUES ('$session_id', '$sender', '$safe_msg')");

echo json_encode(['success' => true]);