<?php
session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Must have gone through OTP verification
if (empty($_SESSION['reset_verified']) || empty($_SESSION['reset_email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please restart the process.']);
    exit;
}

$email = $_SESSION['reset_email'];
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$password || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

if (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password)
) {
    echo json_encode(['success' => false, 'message' => 'Password does not meet requirements.']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$safe_email = mysqli_real_escape_string($conn, $email);

mysqli_query($conn, "UPDATE users SET user_password = '$hashed' WHERE email = '$safe_email'");

if (mysqli_affected_rows($conn) === 0) {
    echo json_encode(['success' => false, 'message' => 'Failed to update password. Please try again.']);
    exit;
}

// Cleanup
mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");
unset($_SESSION['reset_email'], $_SESSION['reset_verified']);

echo json_encode(['success' => true]);
exit;