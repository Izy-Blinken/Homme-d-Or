<?php
session_start();

// clear remember me cookie
if (isset($_COOKIE['remember_token'])) {

    include __DIR__ . '/../db_connect.php';
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        mysqli_query($conn, "UPDATE users SET remember_token = NULL WHERE user_id = '$user_id'");
    }
    
    setcookie('remember_token', '', time() - 3600, '/');
}

session_destroy();
header('Location: ../../pages/index.php');
exit;