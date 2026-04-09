<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || empty($_SESSION['cp_verified'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please restart the process.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!$new_password || !$confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

if (
    strlen($new_password) < 8 ||
    !preg_match('/[A-Z]/', $new_password) ||
    !preg_match('/[a-z]/', $new_password) ||
    !preg_match('/[0-9]/', $new_password)
) {
    echo json_encode(['success' => false, 'message' => 'Password does not meet requirements.']);
    exit;
}

$hashed = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
$stmt->bind_param("si", $hashed, $user_id);

if ($stmt->execute()) {
    unset($_SESSION['cp_verified'], $_SESSION['cp_email']);
    mysqli_query($conn, "DELETE FROM password_resets WHERE email = '" . mysqli_real_escape_string($conn, $_SESSION['user_email'] ?? '') . "'");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
}
exit;