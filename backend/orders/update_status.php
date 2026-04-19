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
$status_label = ucfirst($new_status);
$order_num = str_pad($order_id, 6, '0', STR_PAD_LEFT);

if ($order_user_id) {
    // Registered user — save to DB
    insertNotif($conn, $order_user_id, 'order_status',
        "Your order #{$order_num} has been updated to: {$status_label}.", $order_id);
} else {
    // Guest — fetch guest_id from order and save to their session
    $g_stmt = $conn->prepare("SELECT guest_id FROM orders WHERE order_id = ?");
    $g_stmt->bind_param("i", $order_id);
    $g_stmt->execute();
    $g_row = $g_stmt->get_result()->fetch_assoc();
    $g_stmt->close();

    if ($g_row && $g_row['guest_id']) {
        $guest_id = $g_row['guest_id'];

        // Find the guest's session and inject the notification
        $gs = $conn->prepare("SELECT session_id FROM guests WHERE guest_id = ?");
        $gs->bind_param("i", $guest_id);
        $gs->execute();
        $gs_row = $gs->get_result()->fetch_assoc();
        $gs->close();

        if ($gs_row) {
            // Save pending notif to DB for guest to pick up on next load
            $notif_msg = mysqli_real_escape_string($conn,
                "Your order #{$order_num} has been updated to: {$status_label}.");
            $session_id = mysqli_real_escape_string($conn, $gs_row['session_id']);
            mysqli_query($conn, "
                INSERT INTO guest_notifications (session_id, notif_type, notif_message, reference_id, is_read, created_at)
                VALUES ('$session_id', 'order_status', '$notif_msg', $order_id, 0, NOW())
            ");
        }
    }
}

$_SESSION['success'] = 'Order status updated.';
header('Location: ../../pages/Admin Pages/orderManagement.php');
exit;
?>