<?php
session_start();
include '../db_connect.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Initialize counts
$counts = [
    'processing' => 0,
    'to_review' => 0,
    'completed' => 0,
    'cancelled' => 0
];

// Processing: pending, paid, shipped, delivered
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status IN ('pending', 'paid', 'shipped', 'delivered')");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$counts['processing'] = $result['count'] ?? 0;

// To Review: received
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status = 'received'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$counts['to_review'] = $result['count'] ?? 0;

// Completed: completed
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status = 'completed'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$counts['completed'] = $result['count'] ?? 0;

// Cancelled: cancelled
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status = 'cancelled'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$counts['cancelled'] = $result['count'] ?? 0;

// Return as JSON
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'counts' => $counts
]);
?>
