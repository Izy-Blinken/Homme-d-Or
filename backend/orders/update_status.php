<?php
session_start();
include __DIR__ . '/../db_connect.php';

$order_id  = $_POST['order_id']  ?? null;
$new_status = $_POST['status']   ?? null;

if (!$order_id || !$new_status) {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

// get current status
$current = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT order_status FROM orders WHERE order_id = '$order_id'"));

if (!$current) {
    $_SESSION['error'] = 'Order not found.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

$current_status = $current['order_status'];

// Kung cancelled na, hindi na mababago
if ($current_status === 'cancelled') {
    $_SESSION['error'] = 'Cancelled orders cannot be updated.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

// Valid manual transitions lang ang allowed. Bawal yung magjjump like, pending->delivered
$allowed = [
    'pending' => ['paid', 'cancelled'],
    
];

if (!isset($allowed[$current_status]) || !in_array($new_status, $allowed[$current_status])) {
    $_SESSION['error'] = 'Invalid status transition.';
    header('Location: ../../pages/Admin Pages/orderManagement.php');
    exit;
}

// update ung status
mysqli_query($conn, "UPDATE orders SET 
    order_status = '$new_status',
    status_updated_at = NOW()
    WHERE order_id = '$order_id'");

$_SESSION['success'] = 'Order status updated.';
header('Location: ../../pages/Admin Pages/orderManagement.php');
exit;
?>