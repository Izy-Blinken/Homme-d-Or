<?php

session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

// check if may existing non-escalated session
$existing = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT session_id, escalated FROM chatbot_sessions
     WHERE user_id = '$user_id'
     ORDER BY started_at DESC LIMIT 1"));

if ($existing) {

    echo json_encode([
        'success' => true,
        'session_id' => $existing['session_id'],
        'escalated' => (bool) $existing['escalated'],
    ]);

    exit;

}

// create new session
mysqli_query($conn, "INSERT INTO chatbot_sessions (user_id) VALUES ('$user_id')");
$session_id = mysqli_insert_id($conn);

echo json_encode([
    'success' => true,
    'session_id' => $session_id,
    'escalated' => false,
]);