<?php

session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/adminLogin.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    $_SESSION['error'] = 'Please enter username and password.';
    header('Location: ../../pages/Admin Pages/adminLogin.php');
    exit;
}

// check muna si superadmin kapag may naglogin sa admin login page
$result = mysqli_query($conn, "SELECT * FROM superadmins WHERE username = '$username'");
$superadmin = mysqli_fetch_assoc($result);

if ($superadmin && password_verify($password, $superadmin['sadmin_password'])) {

    $_SESSION['superadmin_id'] = $superadmin['superadmin_id'];
    $_SESSION['admin_username'] = $superadmin['username'];
    $_SESSION['is_superadmin'] = true;
    header('Location: ../../pages/Admin Pages/adminSide.php');

    exit;
}

// reg admin naman
$result = mysqli_query($conn, "SELECT u.*, a.admin_id FROM users u
        JOIN admins a ON u.user_id = a.user_id
        WHERE u.username = '$username'");

$admin = mysqli_fetch_assoc($result);

if ($admin && password_verify($password, $admin['user_password'])) {

    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['user_id'] = $admin['user_id'];
    $_SESSION['user_fname'] = $admin['fname'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['is_superadmin'] = false;
    header('Location: ../../pages/Admin Pages/adminSide.php');

    exit;
}

// admin login not found
$_SESSION['error'] = 'Invalid username or password.';
header('Location: ../../pages/Admin Pages/adminLogin.php');
exit;