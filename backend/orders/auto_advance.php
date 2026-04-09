<?php
include __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../notifications/notify.php';

$sequence = [
    'paid'      => 'shipped',
    'shipped'   => 'delivered',
    'delivered' => 'received',
    'received'  => 'completed',
];

$interval_sec = 10;

foreach ($sequence as $from => $to) {
    // Fetch affected orders before updating
    $affected = mysqli_query($conn,
        "SELECT order_id, user_id FROM orders
         WHERE order_status = '$from'
         AND TIMESTAMPDIFF(SECOND, status_updated_at, NOW()) >= $interval_sec");

    while ($order = mysqli_fetch_assoc($affected)) {
        mysqli_query($conn, "UPDATE orders SET
            order_status = '$to',
            status_updated_at = NOW()
            WHERE order_id = '{$order['order_id']}'");

        if ($order['user_id']) {
            $status_label = ucfirst($to);
            insertNotif($conn, $order['user_id'], 'order_status',
                "Your order #{$order['order_id']} has been updated to: {$status_label}.",
                $order['order_id']);
        }
    }
}
?>