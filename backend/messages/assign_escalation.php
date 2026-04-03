<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

if (!empty($_SESSION['superadmin_id']) === false) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit;
}

$session_id = intval($_POST['session_id'] ?? 0);
$admin_id = intval($_POST['admin_id'] ?? 0);
$user_id = intval($_POST['user_id'] ?? 0);

if (!$session_id || !$admin_id || !$user_id) {
    echo json_encode(['success' => false, 'error' => 'Missing fields.']);
    exit;
}

// get admin name
$r = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT u.fname, u.lname FROM admins a JOIN users u ON a.user_id = u.user_id WHERE a.admin_id = '$admin_id'"));

if (!$r) {
    echo json_encode(['success' => false, 'error' => 'Admin not found.']);
    exit;
}

$admin_name = mysqli_real_escape_string($conn, $r['fname'] . ' ' . $r['lname']);

// first msg sa thread na system msg about assignment
$system_msg = mysqli_real_escape_string($conn, 'Conversation assigned to ' . $r['fname'] . ' ' . $r['lname'] . '.');

mysqli_query($conn,
    "INSERT INTO admin_messages (session_id, sender_id, sender_type, receiver_id, receiver_type, sender_name, content)
     VALUES ('$session_id', '$admin_id', 'admin', '$user_id', 'user', '$admin_name', '$system_msg')");

if (mysqli_affected_rows($conn) > 0) {
    echo json_encode(['success' => true, 'admin_name' => $r['fname'] . ' ' . $r['lname']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to assign.']);
}