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
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

if (empty($status)) {
    http_response_code(400);
    echo json_encode(['error' => 'Status parameter required']);
    exit;
}

// Map tab names to actual order statuses
$statusMap = [
    'processing' => ['pending', 'paid', 'shipped', 'delivered'],
    'review' => ['received'],
    'completed' => ['completed'],
    'cancelled' => ['cancelled']
];

// Get the statuses for the requested tab
$requestedStatuses = $statusMap[$status] ?? [];

if (empty($requestedStatuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid status tab']);
    exit;
}

// Build WHERE clause with status list
$statusList = implode("','", array_map(function($s) use ($conn) {
    return mysqli_real_escape_string($conn, $s);
}, $requestedStatuses));

// Fetch orders for the user with the requested statuses
$query = "
    SELECT o.*, p.method, p.payment_status, p.paid_at
    FROM orders o
    LEFT JOIN payments p ON p.order_id = o.order_id
    WHERE o.user_id = ? AND o.order_status IN ('$statusList')
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

// Return as JSON
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'count' => count($orders),
    'orders' => $orders
]);
?>
