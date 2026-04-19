<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
$guest_id = $_SESSION['guest_id'] ?? null;

if (!$user_id && !$guest_id) {
    echo json_encode(['success' => false]);
    exit;
}

$notif_id = isset($_POST['notif_id']) ? intval($_POST['notif_id']) : null;

if ($user_id) {
    if ($notif_id) {
        mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE notif_id = '$notif_id' AND user_id = '$user_id'");
    } else {
        mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'");
    }
} elseif ($guest_id) {
    $safe_session = mysqli_real_escape_string($conn, $guest_id);
    if ($notif_id) {
        mysqli_query($conn, "UPDATE guest_notifications SET is_read = 1 WHERE id = '$notif_id' AND session_id = '$safe_session'");
    } else {
        mysqli_query($conn, "UPDATE guest_notifications SET is_read = 1 WHERE session_id = '$safe_session'");
    }
}

echo json_encode(['success' => true]);