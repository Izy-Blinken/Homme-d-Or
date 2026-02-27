<?php
include __DIR__ . '/../db_connect.php';

// Auto-advance sequence
$sequence = [
    'paid'      => 'shipped',
    'shipped'   => 'delivered',
    'delivered' => 'received',
    'received'  => 'completed',
];

$interval_sec = 10;

foreach ($sequence as $from => $to) {
    mysqli_query($conn,
        "UPDATE orders SET
            order_status = '$to',
            status_updated_at = NOW()
         WHERE order_status = '$from'
         AND TIMESTAMPDIFF(SECOND, status_updated_at, NOW()) >= $interval_sec");
}
?>