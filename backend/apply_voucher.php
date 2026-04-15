<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../backend/db_connect.php';
header('Content-Type: application/json');

$code = strtoupper(trim($_POST['code'] ?? ''));
if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'No code provided.']);
    exit;
}

$stmt = $conn->prepare("
    SELECT * FROM discounts
    WHERE code = ?
      AND (expires_at IS NULL OR expires_at >= NOW())
      AND (usage_limit IS NULL OR used_count < usage_limit)
    LIMIT 1
");
$stmt->bind_param("s", $code);
$stmt->execute();
$voucher = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$voucher) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired promo code.']);
    exit;
}

// Calculate discount against current subtotal from session
// The subtotal is re-calculated server-side for security if needed, but
// for the JS preview we return the discount value so JS can update the display.
$discount_type  = $voucher['discount_type'];   // 'fixed' or 'percent'
$discount_value = floatval($voucher['discount_value']);

// We need the current subtotal — store it in session during checkout page load
// For now, return the voucher details and let the JS compute
$subtotal = floatval($_POST['subtotal'] ?? 0);

if ($discount_type === 'percent') {
    $discount = round($subtotal * ($discount_value / 100), 2);
} else {
    $discount = min($discount_value, $subtotal);
}

$shipping = $subtotal > 0 ? 150.00 : 0.00;
$new_total = max(0, $subtotal + $shipping - $discount);

// Store in session for orderConfirmation.php to honour
$_SESSION['applied_voucher'] = [
    'code'     => $code,
    'discount' => $discount,
];

echo json_encode([
    'success'   => true,
    'discount'  => $discount,
    'new_total' => $new_total,
    'message'   => 'Promo code applied!',
]);
?>