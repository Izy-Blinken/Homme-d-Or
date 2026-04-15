<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$session_id = intval($data['session_id'] ?? 0);
$content = trim($data['content'] ?? '');

if (!$session_id || !$content) {
    echo json_encode(['success' => false]);
    exit;
}

// get user's name
$u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname, lname FROM users WHERE user_id = '$user_id'"));

$sender_name = mysqli_real_escape_string($conn, ($u['fname'] ?? '') . ' ' . ($u['lname'] ?? ''));
$safe_content = mysqli_real_escape_string($conn, $content);

// get the admin na naka assign sa session
$assigned = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sender_id, sender_type FROM admin_messages
     WHERE session_id = '$session_id' AND sender_type = 'admin'
     ORDER BY sent_at ASC LIMIT 1"));

$receiver_id = $assigned ? $assigned['sender_id'] : null;
$receiver_type = $assigned ? 'admin' : 'superadmin';

// send to superdmin if wla pang assigned admin
if (!$receiver_id) {

    $sa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT superadmin_id FROM superadmins LIMIT 1"));
    $receiver_id = $sa['superadmin_id'] ?? 1;
    $receiver_type = 'superadmin';
}

$result = mysqli_query($conn, "INSERT INTO admin_messages (session_id, sender_id, sender_type, receiver_id, receiver_type, sender_name, content)
     VALUES ('$session_id', '$user_id', 'user', '$receiver_id', '$receiver_type', '$sender_name', '$safe_content')");

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}