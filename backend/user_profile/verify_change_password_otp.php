<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cp_email'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please try again.']);
    exit;
}

$code = trim($_POST['code'] ?? '');
$email = $_SESSION['cp_email'];
$safe_email = mysqli_real_escape_string($conn, $email);

$row = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM password_resets WHERE email = '$safe_email' AND used = 0 ORDER BY created_at DESC LIMIT 1"
));

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'No verification code found.']);
    exit;
}

if (strtotime($row['expires_at']) < time()) {
    echo json_encode(['success' => false, 'message' => 'Code has expired. Please request a new one.']);
    exit;
}

if ($code !== $row['token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid code.']);
    exit;
}

mysqli_query($conn, "UPDATE password_resets SET used = 1 WHERE email = '$safe_email'");
$_SESSION['cp_verified'] = true;

echo json_encode(['success' => true]);
exit;