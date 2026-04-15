<?php
session_start();
include '../db_connect.php';

// Allow both registered users and guests
$identity = getCurrentUserId();
if ($identity['type'] === 'stranger') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value  = $identity['id'];

// For guests, resolve session string → real integer guest_id
if ($id_column === 'guest_id') {
    $g = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
    $g->bind_param("s", $id_value);
    $g->execute();
    $g_row = $g->get_result()->fetch_assoc();
    $g->close();
    $id_value = $g_row ? intval($g_row['guest_id']) : 0;
}

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
    WHERE o.$id_column = ? AND o.order_status IN ('$statusList')
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$stmt->bind_param("i", $id_value);
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
