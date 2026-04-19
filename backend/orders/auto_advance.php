<?php
include __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../notifications/notify.php';

$sequence = [
    'paid' => 'shipped',
    'shipped' => 'delivered',
    'delivered' => 'received',
    'received' => 'completed',
];

$interval_sec = 10;

foreach ($sequence as $from => $to) {
    // Fetch affected orders before updating
    $affected = mysqli_query($conn,
        "SELECT order_id, user_id, guest_id FROM orders
         WHERE order_status = '$from'
         AND TIMESTAMPDIFF(SECOND, status_updated_at, NOW()) >= $interval_sec");

    while ($order = mysqli_fetch_assoc($affected)) {
        mysqli_query($conn, "UPDATE orders SET
            order_status = '$to',
            status_updated_at = NOW()
            WHERE order_id = '{$order['order_id']}'");

        $status_label = ucfirst($to);
        $order_num = str_pad($order['order_id'], 6, '0', STR_PAD_LEFT);
        $notif_msg = "Your order #{$order_num} has been updated to: {$status_label}.";

        if ($order['user_id']) {
            insertNotif($conn, $order['user_id'], 'order_status',
                $notif_msg, $order['order_id']);
        } else {
            // Guest — fetch session_id via guest_id
            $g_row = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT g.session_id FROM guests g
                 JOIN orders o ON o.guest_id = g.guest_id
                 WHERE o.order_id = '{$order['order_id']}' LIMIT 1"));

            if ($g_row && $g_row['session_id']) {
                $safe_session = mysqli_real_escape_string($conn, $g_row['session_id']);
                $safe_msg = mysqli_real_escape_string($conn, $notif_msg);
                mysqli_query($conn, "
                    INSERT INTO guest_notifications
                        (session_id, notif_type, notif_message, reference_id, is_read, created_at)
                    VALUES
                        ('$safe_session', 'order_status', '$safe_msg', {$order['order_id']}, 0, NOW())
                ");
            }
        }
    }
}
?>