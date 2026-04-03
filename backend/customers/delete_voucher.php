<?php

session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

if (empty($_SESSION['superadmin_id'])) {
    $_SESSION['error'] = 'Unauthorized.';
    header('Location: ../../pages/Admin Pages/voucherManagement.php');
    exit;
}

$discount_id = intval($_POST['discount_id'] ?? 0);

if (!$discount_id) {
    $_SESSION['error'] = 'Invalid voucher.';
    header('Location: ../../pages/Admin Pages/voucherManagement.php');
    exit;
}

mysqli_query($conn, "DELETE FROM discounts WHERE discount_id = '$discount_id'");

$_SESSION['success'] = 'Voucher deleted.';
header('Location: ../../pages/Admin Pages/voucherManagement.php');
exit;