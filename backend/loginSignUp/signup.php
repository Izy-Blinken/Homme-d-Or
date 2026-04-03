<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/index.php');
    exit;
}

$fname = trim($_POST['firstname'] ?? '');
$lname = trim($_POST['lastname'] ?? '');
$bday = trim($_POST['birthday'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$fname || !$lname || !$email || !$username || !$password) {
    $_SESSION['signup_error'] = 'Please fill in all required fields.';
    header('Location: ../../pages/index.php');
    exit;
}

if ($password !== $confirm) {
    $_SESSION['signup_error'] = 'Passwords do not match.';
    header('Location: ../../pages/index.php');
    exit;
}

if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    $_SESSION['signup_error'] = 'Password does not meet requirements.';
    header('Location: ../../pages/index.php');
    exit;
}

// check if email is existing na
$safe_email = mysqli_real_escape_string($conn, $email);
$existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$safe_email'"));

if ($existing) {
    $_SESSION['signup_error'] = 'Email is already registered.';
    header('Location: ../../pages/index.php');
    exit;
}

// check if username is existing na
$safe_username = mysqli_real_escape_string($conn, $username);
$existing_username = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM users WHERE username = '$safe_username'"));

if ($existing_username) {
    $_SESSION['signup_error'] = 'Username is already taken.';
    header('Location: ../../pages/index.php');
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$safe_fname = mysqli_real_escape_string($conn, $fname);
$safe_lname = mysqli_real_escape_string($conn, $lname);
$safe_bday = mysqli_real_escape_string($conn, $bday);
$safe_phone = mysqli_real_escape_string($conn, $phone);

mysqli_query($conn, "INSERT INTO users (fname, lname, bday, phone, email, username, user_password)
     VALUES ('$safe_fname', '$safe_lname', '$safe_bday', '$safe_phone', '$safe_email', '$safe_username', '$hashed')");

if (mysqli_affected_rows($conn) === 0) {
    $_SESSION['signup_error'] = 'Registration failed. Please try again.';
    header('Location: ../../pages/index.php');
    exit;
}

$user_id = mysqli_insert_id($conn);
$_SESSION['user_id'] = $user_id;
$_SESSION['user_fname'] = $fname;
$_SESSION['user_email'] = $email;
$_SESSION['signup_success'] = true;

header('Location: ../../pages/index.php');
exit;