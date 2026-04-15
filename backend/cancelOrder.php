<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';

header('Content-Type: application/json');

$identity = getCurrentUserId();
if ($identity['type'] === 'stranger') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);
$reason   = trim($_POST['reason'] ?? '');

if (!$order_id || empty($reason)) {
    echo json_encode(['success' => false, 'message' => 'Missing order ID or reason.']);
    exit;
}

// Determine which column to check ownership by
$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value  = $identity['id'];

// For guests, resolve session string → integer guest_id
if ($id_column === 'guest_id') {
    $g = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
    $g->bind_param("s", $id_value);
    $g->execute();
    $g_row = $g->get_result()->fetch_assoc();
    $g->close();
    if (!$g_row) {
        echo json_encode(['success' => false, 'message' => 'Guest session not found.']);
        exit;
    }
    $id_value = $g_row['guest_id'];
}

// Fetch order and verify ownership + status
$stmt = $conn->prepare("SELECT order_id, order_status FROM orders WHERE order_id = ? AND $id_column = ?");
$stmt->bind_param("ii", $order_id, $id_value);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found or access denied.']);
    exit;
}

if ($order['order_status'] !== 'pending') {
    echo json_encode(['success' => false, 'message' => 'Only pending orders can be cancelled.']);
    exit;
}

// Update status to cancelled and store reason
$upd = $conn->prepare("UPDATE orders SET order_status = 'cancelled', cancellation_reason = ?, status_updated_at = NOW() WHERE order_id = ?");
$upd->bind_param("si", $reason, $order_id);

if ($upd->execute()) {
    echo json_encode(['success' => true, 'message' => 'Order cancelled successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}
$upd->close();
?>