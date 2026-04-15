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

$stmt_check = $conn->prepare("SELECT order_status, user_id FROM orders WHERE order_id = ?");
$stmt_check->bind_param("i", $order_id);
$stmt_check->execute();
$current = $stmt_check->get_result()->fetch_assoc();
$stmt_check->close();

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

$stmt_upd = $conn->prepare("UPDATE orders SET order_status = ?, status_updated_at = NOW() WHERE order_id = ?");
$stmt_upd->bind_param("si", $new_status, $order_id);
$stmt_upd->execute();
$stmt_upd->close();

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