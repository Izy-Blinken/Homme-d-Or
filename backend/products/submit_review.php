<?php
session_start();
include '../db_connect.php';

header('Content-Type: application/json');

$identity = getCurrentUserId();
if ($identity['type'] !== 'user_id') {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a review.']);
    exit;
}

$user_id = intval($identity['id']);
$product_id = intval($_POST['product_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if (!$product_id || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid fields.']);
    exit;
}

// Check if product exists
$chkProduct = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
$chkProduct->bind_param("i", $product_id);
$chkProduct->execute();
if (!$chkProduct->get_result()->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}
$chkProduct->close();

// Verify the user has actually purchased and received/completed this product
$chkPurchase = $conn->prepare("
    SELECT oi.item_id
    FROM order_items oi
    JOIN orders o ON o.order_id = oi.order_id
    WHERE oi.product_id = ?
      AND o.user_id = ?
      AND o.order_status IN ('received', 'completed')
    LIMIT 1
");

$chkPurchase->bind_param("ii", $product_id, $user_id);
$chkPurchase->execute();
$purchased = $chkPurchase->get_result()->fetch_assoc();
$chkPurchase->close();

if (!$purchased) {
    echo json_encode(['success' => false, 'message' => 'You can only review products you have purchased and received.']);
    exit;
}

// Check if already reviewed
$chkStmt = $conn->prepare("SELECT review_id FROM product_reviews WHERE user_id = ? AND product_id = ?");
$chkStmt->bind_param("ii", $user_id, $product_id);
$chkStmt->execute();
$existing = $chkStmt->get_result()->fetch_assoc();
$chkStmt->close();

if ($existing) {
    $stmt = $conn->prepare("UPDATE product_reviews SET rating = ?, comment = ?, created_at = NOW() WHERE review_id = ?");
    $stmt->bind_param("isi", $rating, $comment, $existing['review_id']);
} else {
    $stmt = $conn->prepare("INSERT INTO product_reviews (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Review submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save review.']);
}
$stmt->close();
?>