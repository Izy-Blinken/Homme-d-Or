<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../notifications/notify.php';

if (empty($_SESSION['superadmin_id'])) {
    $_SESSION['error'] = 'Unauthorized.';
    header('Location: ../../pages/Admin Pages/voucherManagement.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/voucherManagement.php');
    exit;
}

$voucher_type   = $_POST['voucher_type']   ?? 'individual';
$discount_type  = $_POST['discount_type']  ?? '';
$discount_value = floatval($_POST['discount_value'] ?? 0);
$usage_limit    = $voucher_type === 'individual' ? 1 : intval($_POST['usage_limit'] ?? 1);
$expires_at     = $_POST['expires_at']     ?? '';

if (!in_array($discount_type, ['fixed', 'percent']) || !$discount_value || !$expires_at) {
    $_SESSION['error'] = 'Missing required fields.';
    header('Location: ../../pages/Admin Pages/voucherManagement.php');
    exit;
}

$code              = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
$safe_code         = mysqli_real_escape_string($conn, $code);
$safe_type         = mysqli_real_escape_string($conn, $discount_type);
$safe_voucher_type = mysqli_real_escape_string($conn, $voucher_type);
$safe_expires      = mysqli_real_escape_string($conn, $expires_at);

$discount_id = null;
mysqli_query($conn, "INSERT INTO discounts (code, discount_type, discount_value, usage_limit, expires_at, voucher_type)
     VALUES ('$safe_code', '$safe_type', '$discount_value', '$usage_limit', '$safe_expires', '$safe_voucher_type')");

if (mysqli_affected_rows($conn) === 0) {
    $_SESSION['error'] = 'Failed to create voucher.';
    header('Location: ../../pages/Admin Pages/voucherManagement.php');
    exit;
}

$discount_id = mysqli_insert_id($conn);

// ── BROADCAST VOUCHER NOTIFICATION ────────────────────────────────
if ($voucher_type === 'broadcast') {
    $label         = $discount_type === 'percent'
        ? $discount_value . '% off'
        : '₱' . number_format($discount_value, 2) . ' off';
    $expires_label = date('M d, Y', strtotime($expires_at));

    $users = mysqli_query($conn, "SELECT user_id FROM users WHERE is_blocked = 0");
    while ($u = mysqli_fetch_assoc($users)) {
        insertNotif($conn, $u['user_id'], 'voucher',
            "Limited voucher available: {$code} — {$label}. Valid until {$expires_label}. First {$usage_limit} use(s) only!",
            $discount_id);
    }
}

$_SESSION['success'] = 'Voucher created successfully.';
header('Location: ../../pages/Admin Pages/voucherManagement.php');
exit;