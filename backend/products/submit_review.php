<?php
session_start();
include '../db_connect.php';

// Check authentication
$identity = getCurrentUserId();
if ($identity['type'] === 'stranger') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = ($identity['type'] === 'user_id') ? $identity['id'] : null;

if (!$user_id || empty($_POST['product_id']) || empty($_POST['rating'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$product_id = intval($_POST['product_id']);
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment'] ?? '');

// Validate rating
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating']);
    exit;
}

// Check if user already reviewed this product
$checkStmt = $conn->prepare("SELECT review_id FROM product_reviews WHERE user_id = ? AND product_id = ?");
$checkStmt->bind_param("ii", $user_id, $product_id);
$checkStmt->execute();
$existingReview = $checkStmt->get_result()->fetch_assoc();
$checkStmt->close();

// Update or insert review
if ($existingReview) {
    $updateStmt = $conn->prepare("UPDATE product_reviews SET rating = ?, comment = ?, created_at = NOW() WHERE review_id = ?");
    $updateStmt->bind_param("isi", $rating, $comment, $existingReview['review_id']);
    $success = $updateStmt->execute();
    $updateStmt->close();
} else {
    $insertStmt = $conn->prepare("INSERT INTO product_reviews (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $insertStmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
    $success = $insertStmt->execute();
    $insertStmt->close();
}

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save review']);
}
?>
