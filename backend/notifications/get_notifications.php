<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['notifications' => [], 'unread_count' => 0]);
    exit;
}

$q = mysqli_query($conn,
    "SELECT n.notif_id, n.notif_type, n.notif_message, n.is_read, n.created_at,
            d.code, d.discount_type, d.discount_value, d.expires_at
     FROM notifications n
     LEFT JOIN discounts d ON d.code = n.notif_message
     WHERE n.user_id = '$user_id'
     ORDER BY n.created_at DESC
     LIMIT 20");

$notifications = [];

while ($row = mysqli_fetch_assoc($q)) {
    $notifications[] = $row;
}

$unread = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS cnt FROM notifications WHERE user_id = '$user_id' AND is_read = 0"));

echo json_encode([
    'notifications' => $notifications,
    'unread_count' => (int) $unread['cnt'],
]);