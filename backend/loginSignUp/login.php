<?php
session_start();
include __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../notifications/notify.php';

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
$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE username = '$safe_username'"));

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

$user_id = $user['user_id'];

$_SESSION['user_id']       = $user_id;
$_SESSION['user_fname']    = $user['fname'];
$_SESSION['user_email']    = $user['email'];
$_SESSION['user_username'] = $user['username'];

// Remember me
if ($remember) {
    $token      = bin2hex(random_bytes(32));
    $safe_token = mysqli_real_escape_string($conn, $token);
    mysqli_query($conn, "UPDATE users SET remember_token = '$safe_token' WHERE user_id = '$user_id'");
    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
}

// ── CART REMINDER NOTIFICATION ─────────────────────────────────────
// Only insert if user has cart items AND no existing unread cart reminder
$cartCount = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS cnt FROM cart WHERE user_id = '$user_id'"));

if ($cartCount['cnt'] > 0) {
    $existing = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT notif_id FROM notifications
         WHERE user_id = '$user_id' AND notif_type = 'cart_reminder' AND is_read = 0
         LIMIT 1"));

    if (!$existing) {
        insertNotif($conn, $user_id, 'cart_reminder',
            "You have {$cartCount['cnt']} item(s) waiting in your cart. Complete your purchase!", null);
    }
}

$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '../../pages/index.php';
header('Location: ' . $redirect);
exit;
?>