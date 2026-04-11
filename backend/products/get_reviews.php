<?php
session_start();
include '../db_connect.php';

header('Content-Type: application/json');

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$for_homepage = isset($_GET['homepage']) && $_GET['homepage'] === '1';

if ($for_homepage) {
    // Fetch latest 6 reviews across all products for testimonials section
    $stmt = $conn->prepare("
        SELECT pr.review_id, pr.rating, pr.comment, pr.created_at,
               u.fname, u.lname, u.profile_photo,
               p.product_name, pi.image_url AS product_image
        FROM product_reviews pr
        JOIN users u ON pr.user_id = u.user_id
        JOIN products p ON pr.product_id = p.product_id
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        ORDER BY pr.created_at DESC
        LIMIT 6
    ");
    $stmt->execute();
    $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode(['success' => true, 'reviews' => $reviews]);
    exit;
}

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Missing product_id']);
    exit;
}

// Fetch reviews for a specific product
$stmt = $conn->prepare("
    SELECT pr.review_id, pr.rating, pr.comment, pr.created_at,
           u.fname, u.lname, u.profile_photo
    FROM product_reviews pr
    JOIN users u ON pr.user_id = u.user_id
    WHERE pr.product_id = ?
    ORDER BY pr.created_at DESC
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Aggregate stats
$total = count($reviews);
$avg = $total > 0 ? round(array_sum(array_column($reviews, 'rating')) / $total, 1) : 0;

$breakdown = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
foreach ($reviews as $r) {
    $breakdown[(int)$r['rating']]++;
}

echo json_encode([
    'success' => true,
    'reviews' => $reviews,
    'stats' => [
        'total' => $total,
        'average' => $avg,
        'breakdown' => $breakdown
    ]
]);
?>