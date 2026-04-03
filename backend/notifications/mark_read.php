<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

$notif_id = isset($_POST['notif_id']) ? intval($_POST['notif_id']) : null;

if ($notif_id) {
    mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE notif_id = '$notif_id' AND user_id = '$user_id'");

} else {
    mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'");
}

echo json_encode(['success' => true]);