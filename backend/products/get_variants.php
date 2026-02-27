<?php
include __DIR__ . '/../db_connect.php';

$product_id = $_GET['product_id'] ?? 0;

$result = mysqli_query($conn, "SELECT * FROM product_variants WHERE product_id = '$product_id' ORDER BY variant_id ASC");

$variants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $variants[] = $row;
}

header('Content-Type: application/json');
echo json_encode($variants);
?>