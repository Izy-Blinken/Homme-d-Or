<?php
session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$otp     = trim($_POST['code'] ?? '');

if (strlen($otp) !== 6) {
    echo json_encode(['success' => false, 'message' => 'Please enter the 6-digit code.']);
    exit;
}

$safe_otp = mysqli_real_escape_string($conn, $otp);

$result = mysqli_query($conn, "
    SELECT * FROM email_verifications
    WHERE user_id = '$user_id'
      AND token = '$safe_otp'
      AND expires_at > NOW()
    LIMIT 1
");

$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired code.']);
    exit;
}

// Delete all user data
mysqli_query($conn, "DELETE FROM email_verifications WHERE user_id = '$user_id'");
mysqli_query($conn, "DELETE FROM users WHERE user_id = '$user_id'");

// Destroy session
session_destroy();
setcookie('remember_token', '', time() - 3600, '/');

echo json_encode(['success' => true]);
exit;