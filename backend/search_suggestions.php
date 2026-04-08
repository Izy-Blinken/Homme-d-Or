<?php
include 'db_connect.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

$q = mysqli_real_escape_string($conn, $query);

$sql = "
    SELECT p.product_id, p.product_name, p.price, p.discounted_price, pi.image_url
    FROM products p
    LEFT JOIN product_images pi 
        ON pi.product_id = p.product_id AND pi.is_primary = 1
    WHERE p.product_name LIKE '%$q%'
    AND p.product_status != 'out-of-stock'
    ORDER BY p.product_name ASC
    LIMIT 6
";

$result = mysqli_query($conn, $sql);
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'product_id' => $row['product_id'],
        'name' => $row['product_name'],
        'price' => $row['discounted_price'] ?? $row['price'],
        'image' => $row['image_url'] ?? ''
    ];
}

echo json_encode($products);