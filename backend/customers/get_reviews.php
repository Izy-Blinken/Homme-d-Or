<?php
include __DIR__ . '/../db_connect.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {

    echo json_encode([]);
    exit;
}

$result = mysqli_query($conn, "SELECT r.rating, r.comment, p.product_name, DATE_FORMAT(r.created_at, '%M %d, %Y') AS created_at
    FROM product_reviews r
    JOIN products p ON r.product_id = p.product_id
    WHERE r.user_id = '$user_id'
    ORDER BY r.created_at DESC");

$reviews = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

header('Content-Type: application/json');
echo json_encode($reviews);