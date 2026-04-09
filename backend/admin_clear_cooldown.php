<?php
// Clear password reset cooldown for current user - for testing only
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE user_id = '$user_id'"));

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$email = $user['email'];
$safe_email = mysqli_real_escape_string($conn, $email);

// Clear all password reset records for this email
$result = mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");

if ($result) {
    echo json_encode(['success' => true, 'message' => 'All cooldown records cleared for ' . $email]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed: ' . mysqli_error($conn)]);
}
exit;
