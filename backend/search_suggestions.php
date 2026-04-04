<?php
// backend/search_suggestions.php
header('Content-Type: application/json');

// Fake data to test the UI design
echo json_encode([
    [
        "product_id" => 1,
        "name" => "Golden Night Special Edition",
        "price" => 1800,
        "image" => "placeholder.jpg"
    ],
    [
        "product_id" => 2,
        "name" => "Midnight Oud Extrait",
        "price" => 2450,
        "image" => "placeholder.jpg"
    ],
    [
        "product_id" => 3,
        "name" => "Velvet Rose & Vanilla",
        "price" => 1200,
        "image" => "placeholder.jpg"
    ]
]);
?>