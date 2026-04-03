<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$session_id = intval($data['session_id'] ?? 0);

if (!$session_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid session.']);
    exit;
}

mysqli_query($conn, "UPDATE chatbot_sessions SET escalated = 1 WHERE session_id = '$session_id' AND user_id = '$user_id'");

echo json_encode(['success' => true, 'session_id' => $session_id]);