<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
$voucher_id = intval($_POST['voucher_id'] ?? 0);

if (!$user_id || !$voucher_id) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

// get voucher details
$voucher = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM discounts WHERE discount_id = '$voucher_id'"));

if (!$voucher) {
    echo json_encode(['success' => false, 'error' => 'Voucher not found.']);
    exit;
}

if ($voucher['assigned_to_user_id']) {
    echo json_encode(['success' => false, 'error' => 'Voucher already assigned to another customer.']);
    exit;
}

// assign voucher to specific cust lang
mysqli_query($conn, "UPDATE discounts SET assigned_to_user_id = '$user_id', voucher_type = 'individual'
     WHERE discount_id = '$voucher_id'");

// send notification
$label = $voucher['discount_type'] === 'percent' ? $voucher['discount_value'] . '% off' : '₱' . number_format($voucher['discount_value'], 2) . ' off';

$expires = date('M d, Y', strtotime($voucher['expires_at']));
$msg = mysqli_real_escape_string($conn, "You received a voucher: {$voucher['code']} — $label. Valid until $expires.");

mysqli_query($conn, "INSERT INTO notifications (user_id, notif_type, notif_message)
     VALUES ('$user_id', 'general', '$msg')");

echo json_encode(['success' => true, 'code' => $voucher['code']]);