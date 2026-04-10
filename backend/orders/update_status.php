<?php
session_start();
include __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../notifications/notify.php';

$order_id = $_POST['order_id'] ?? null;
$new_status = $_POST['status'] ?? null;

if (!$order_id || !$new_status) {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

$current = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT order_status, user_id FROM orders WHERE order_id = '$order_id'"));

if (!$current) {
    $_SESSION['error'] = 'Order not found.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

$current_status = $current['order_status'];
$order_user_id = $current['user_id'];

if ($current_status === 'cancelled') {
    $_SESSION['error'] = 'Cancelled orders cannot be updated.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

$allowed = [
    'pending' => ['paid', 'cancelled'],
];

if (!isset($allowed[$current_status]) || !in_array($new_status, $allowed[$current_status])) {
    $_SESSION['error'] = 'Invalid status transition.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

mysqli_query($conn, "UPDATE orders SET
    order_status = '$new_status',
    status_updated_at = NOW()
    WHERE order_id = '$order_id'");

// ── ORDER STATUS NOTIFICATION ──────────────────────────────────────
// Only notify registered users (guests have no user_id)
if ($order_user_id) {
    $status_label = ucfirst($new_status);
    insertNotif($conn, $order_user_id, 'order_status',
        "Your order #{$order_id} has been updated to: {$status_label}.", $order_id);
}

$_SESSION['success'] = 'Order status updated.';
header('Location: ../../pages/Admin Pages/orderManagement.php');
exit;
?>