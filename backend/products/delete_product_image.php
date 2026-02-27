<?php
session_start();
include '../db_connect.php';

header('Content-Type: application/json');

if (!isset($_POST['image_id']) || !isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$image_id   = (int) $_POST['image_id'];
$product_id = (int) $_POST['product_id'];

// Get image info before deleting
$img = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT image_url, is_primary FROM product_images WHERE image_id = '$image_id' AND product_id = '$product_id'"
));

if (!$img) {
    echo json_encode(['success' => false, 'message' => 'Image not found']);
    exit;
}

// Count total images for each product
$count = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM product_images WHERE product_id = '$product_id'"
));

if ($count['total'] <= 1) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete the only image']);
    exit;
}

// Delete file from server
$file_path = '../../assets/images/products/' . $img['image_url'];
if (file_exists($file_path)) {
    unlink($file_path);
}

// Delete
mysqli_query($conn, "DELETE FROM product_images WHERE image_id = '$image_id'");

// If primary ung dinelete, yung next img na ung primary
if ($img['is_primary']) {
    $next = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT image_id FROM product_images WHERE product_id = '$product_id' LIMIT 1"
    ));
    if ($next) {
        mysqli_query($conn, "UPDATE product_images SET is_primary = 1 WHERE image_id = '{$next['image_id']}'");
    }
}

echo json_encode(['success' => true]);