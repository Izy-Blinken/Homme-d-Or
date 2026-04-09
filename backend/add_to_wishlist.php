<?php
session_start();
include_once 'db_connect.php';

header('Content-Type: application/json');

$identity = getCurrentUserId();

// ONLY registered users can use wishlist
if ($identity['type'] !== 'user_id') {
    ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to save items to your wishlist.'
    ]);
    exit;
}

$user_id = $identity['id'];

if (!isset($_POST['product_id'])) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

$product_id = intval($_POST['product_id']);

// Check if already in wishlist
$check = $conn->prepare("SELECT wishlist_id FROM wishlist WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();
$check->close();

if ($result->num_rows > 0) {
    // Already in wishlist — REMOVE it (toggle)
    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->bind_param("ii", $user_id, $product_id);

    if ($delete->execute()) {
        $delete->close();
        ob_clean();
        echo json_encode(['status' => 'removed', 'message' => 'Removed from wishlist.']);
    } else {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove: ' . $delete->error]);
    }
} else {
    // Not in wishlist — ADD it
    $insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $product_id);

    if ($insert->execute()) {
        $insert->close();
        ob_clean();
        echo json_encode(['status' => 'added', 'message' => 'Added to wishlist!']);
    } else {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Failed to add: ' . $insert->error]);
    }
}
?>