<?php
include 'db_connect.php';

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

$safe  = mysqli_real_escape_string($conn, $query);
$result = mysqli_query($conn,
    "SELECT product_id, product_name, price, discounted_price
     FROM products
     WHERE product_name LIKE '%$safe%'
     AND product_status != 'out-of-stock'
     ORDER BY product_name ASC
     LIMIT 10");

$suggestions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $suggestions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($suggestions);
?>