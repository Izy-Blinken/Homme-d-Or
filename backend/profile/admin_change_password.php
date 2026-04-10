<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['superadmin_id']) || empty($_SESSION['admin_cp_verified'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please restart the process.']);
    exit;
}

$superadmin_id = (int) $_SESSION['superadmin_id'];
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
$stmt = $conn->prepare("UPDATE superadmins SET password = ? WHERE superadmin_id = ?");
$stmt->bind_param("si", $hashed, $superadmin_id);

if ($stmt->execute()) {
    $safe_email = mysqli_real_escape_string($conn, $_SESSION['admin_cp_email'] ?? '');
    mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");
    unset($_SESSION['admin_cp_verified'], $_SESSION['admin_cp_email']);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
}
exit;