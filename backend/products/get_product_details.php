<?php
session_start();
include '../db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No product ID provided']);
    exit;
}

$product_id = (int) $_GET['id'];

// Get product and category
$product = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT p.*, c.category_name
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.category_id
     WHERE p.product_id = '$product_id'
     LIMIT 1"
));

if (!$product) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

// Get all images
$images = [];
$img_result = mysqli_query($conn,
    "SELECT image_id, image_url, is_primary
     FROM product_images
     WHERE product_id = '$product_id'
     ORDER BY is_primary DESC, image_id ASC"
);
while ($img = mysqli_fetch_assoc($img_result)) {
    $images[] = $img;
}

// Get variants
$variants = [];
$var_result = mysqli_query($conn,
    "SELECT variant_id, size_label, price, stock_qty, sku
     FROM product_variants
     WHERE product_id = '$product_id'
     ORDER BY variant_id ASC"
);
while ($var = mysqli_fetch_assoc($var_result)) {
    $variants[] = $var;
}

echo json_encode([
    'product'  => $product,
    'images'   => $images,
    'variants' => $variants,
]);