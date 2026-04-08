<?php
include __DIR__ . '/db_connect.php';

header('Content-Type: application/json');

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 1) {
    echo json_encode(['customers' => [], 'products' => []]);
    exit;
}

$safe = mysqli_real_escape_string($conn, $query);

// Search customers
$customerResult = mysqli_query($conn,
    "SELECT user_id, fname, lname, email
     FROM users
     WHERE fname LIKE '%$safe%'
        OR lname LIKE '%$safe%'
        OR CONCAT(fname, ' ', lname) LIKE '%$safe%'
        OR email LIKE '%$safe%'
     ORDER BY fname ASC
     LIMIT 5"
);

$customers = [];
while ($row = mysqli_fetch_assoc($customerResult)) {
    $customers[] = $row;
}

// Search products
$productResult = mysqli_query($conn,
    "SELECT product_id, product_name, price, discounted_price, product_status
     FROM products
     WHERE product_name LIKE '%$safe%'
        OR sku LIKE '%$safe%'
     ORDER BY product_name ASC
     LIMIT 5"
);

$products = [];
while ($row = mysqli_fetch_assoc($productResult)) {
    $products[] = $row;
}

echo json_encode([
    'customers' => $customers,
    'products'  => $products
]);

?>