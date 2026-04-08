<?php
session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

if (!$username || !$password) {
    $_SESSION['login_error'] = 'Please enter your username and password.';
    header('Location: ../../pages/index.php');
    exit;
}

$safe_username = mysqli_real_escape_string($conn, $username);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username = '$safe_username'"));

if (!$user || !password_verify($password, $user['user_password'])) {
    $_SESSION['login_error'] = 'Invalid username or password.';
    header('Location: ../../pages/index.php');
    exit;
}

if ($user['is_blocked']) {
    $_SESSION['login_error'] = 'Your account has been blocked. Please contact support.';
    header('Location: ../../pages/index.php');
    exit;
}

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['user_fname'] = $user['fname'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_username'] = $user['username'];

// remember me
if ($remember) {

    $token = bin2hex(random_bytes(32));
    $safe_token = mysqli_real_escape_string($conn, $token);
    mysqli_query($conn, "UPDATE users SET remember_token = '$safe_token' WHERE user_id = '{$user['user_id']}'");
    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
}

$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '../../pages/index.php';
header('Location: ' . $redirect);
exit;