<?php
session_start();
require '../db_connect.php';
require_once '../notifications/notify.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$bday = trim($_POST['bday'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($fname) || empty($lname) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'First name, last name, and email are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

$check = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check->bind_param("si", $email, $user_id);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'That email is already used by another account.']);
    exit;
}

$password_changed = false;

if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all password fields to change your password.']);
        exit;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
        exit;
    }

    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
        exit;
    }

    $get_pass = $conn->prepare("SELECT user_password FROM users WHERE user_id = ?");
    $get_pass->bind_param("i", $user_id);
    $get_pass->execute();
    $pass_row = $get_pass->get_result()->fetch_assoc();

    if (!password_verify($current_password, $pass_row['user_password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
    $bday_val = empty($bday) ? null : $bday;

    $stmt = $conn->prepare("UPDATE users SET fname=?, lname=?, bday=?, phone=?, email=?, user_password=? WHERE user_id=?");
    $stmt->bind_param("ssssssi", $fname, $lname, $bday_val, $phone, $email, $hashed_new, $user_id);
    $password_changed = true;

} else {

    $bday_val = empty($bday) ? null : $bday;

    $stmt = $conn->prepare("UPDATE users SET fname=?, lname=?, bday=?, phone=?, email=? WHERE user_id=?");
    $stmt->bind_param("sssssi", $fname, $lname, $bday_val, $phone, $email, $user_id);
}

if ($stmt->execute()) {
    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;

    // PASSWORD CHANGE NOTIFICATION
    if ($password_changed) {
        insertNotif($conn, $user_id, 'password_change',
            'Your password was successfully changed. If this wasn\'t you, contact support immediately.', null);
    }

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again.']);
}
?>