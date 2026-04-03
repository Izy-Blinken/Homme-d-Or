<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$current_superadmin_id = $_SESSION['superadmin_id'] ?? null;
$current_admin_id = $_SESSION['admin_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit;
}

$content = trim($_POST['content'] ?? '');
$receiver_id = intval($_POST['receiver_id'] ?? 0);
$receiver_type = $_POST['receiver_type'] ?? '';
$session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : null;

if (!$content || !$receiver_id || !in_array($receiver_type, ['superadmin', 'admin', 'user'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

$safe_content = mysqli_real_escape_string($conn, $content);
$session_val = $session_id ? "'$session_id'" : 'NULL';

if ($isSuperadmin) {

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM superadmins WHERE superadmin_id = '$current_superadmin_id'"));
    $sender_name = mysqli_real_escape_string($conn, $r['username'] ?? 'Superadmin');
    $sender_id = $current_superadmin_id;
    $sender_type = 'superadmin';

} else {

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT u.fname, u.lname FROM admins a JOIN users u ON a.user_id = u.user_id WHERE a.admin_id = '$current_admin_id'"));
    $sender_name = mysqli_real_escape_string($conn, ($r['fname'] ?? '') . ' ' . ($r['lname'] ?? ''));
    $sender_id = $current_admin_id;
    $sender_type = 'admin';
}

mysqli_query($conn,
    "INSERT INTO admin_messages (session_id, sender_id, sender_type, receiver_id, receiver_type, sender_name, content)
     VALUES ($session_val, '$sender_id', '$sender_type', '$receiver_id', '$receiver_type', '$sender_name', '$safe_content')");

if (mysqli_affected_rows($conn) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to send.']);
}