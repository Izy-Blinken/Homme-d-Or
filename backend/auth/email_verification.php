<?php
session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$user_id = $_SESSION['pending_user_id'] ?? null;
$otp     = trim($_POST['code'] ?? '');  // JS sends a plain 6-digit string

if (!$user_id || strlen($otp) !== 6) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$safe_otp = mysqli_real_escape_string($conn, $otp);

// Check OTP exists, matches, is not expired, and not already used
$result = mysqli_query($conn, "
    SELECT * FROM email_verifications
    WHERE user_id = '$user_id'
      AND token = '$safe_otp'
      AND expires_at > NOW()
      AND verified_at IS NULL
    LIMIT 1
");

$data = mysqli_fetch_assoc($result);

if (!$data || strtotime($data['expires_at']) < time() ) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired code. Please try again.']);
    exit;
}

// Verfied na
mysqli_query($conn, "UPDATE email_verifications SET verified_at = NOW() WHERE id = '{$data['id']}'");
mysqli_query($conn, "UPDATE users SET is_verified = 1 WHERE user_id = '$user_id'");

$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1"
));

// Clear pending session data
unset(
    $_SESSION['pending_user_id'],
    $_SESSION['pending_user_email'],
    $_SESSION['pending_user_fname']
);

// Set logged-in session
$_SESSION['user_id']        = $user['user_id'];
$_SESSION['user_fname']     = $user['fname'];
$_SESSION['user_email']     = $user['email'];
$_SESSION['signup_success'] = true;

echo json_encode(['success' => true]);
exit;