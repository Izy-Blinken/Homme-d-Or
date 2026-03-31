<?php
session_start();
include __DIR__ . '/../db_connect.php';

$user_id = $_POST['user_id'] ?? null;
$action = $_POST['action'] ?? null; // block or unblock
$status = $_POST['status'] ?? 'active';

if (!$user_id || !in_array($action, ['block', 'unblock'])) {

    $_SESSION['error'] = 'Invalid request.';
    header('Location: ../../pages/Admin Pages/customerList.php');

    exit;

}

$value = $action === 'block' ? 1 : 0;
mysqli_query($conn, "UPDATE users SET is_blocked = $value WHERE user_id = '$user_id'");

$_SESSION['success'] = $action === 'block' ? 'Customer blocked.' : 'Customer unblocked.';
header("Location: ../../pages/Admin Pages/customerList.php?status=$status");

exit;