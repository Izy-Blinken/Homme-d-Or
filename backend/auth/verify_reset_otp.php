<?php
session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$email = $_SESSION['reset_email'] ?? null;
$otp   = trim($_POST['code'] ?? '');

if (!$email || strlen($otp) !== 6) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$safe_email = mysqli_real_escape_string($conn, $email);
$safe_otp   = mysqli_real_escape_string($conn, $otp);

$row = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM password_resets
     WHERE email = '$safe_email'
       AND token = '$safe_otp'
       AND expires_at > NOW()
       AND used = 0
     LIMIT 1"
));

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired code. Please try again.']);
    exit;
}

// Mark as used
mysqli_query($conn, "UPDATE password_resets SET used = 1 WHERE id = '{$row['id']}'");

// Allow password reset
$_SESSION['reset_verified'] = true;

echo json_encode(['success' => true]);
exit;