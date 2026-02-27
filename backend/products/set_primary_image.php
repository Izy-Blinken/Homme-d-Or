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

// Remove primary from all images of this product
mysqli_query($conn, "UPDATE product_images SET is_primary = 0 WHERE product_id = '$product_id'");

// Set selected image as primary
mysqli_query($conn, "UPDATE product_images SET is_primary = 1 WHERE image_id = '$image_id' AND product_id = '$product_id'");

echo json_encode(['success' => true]);