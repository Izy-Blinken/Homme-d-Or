<?php
session_start();
include '../../db_connect.php';
include '../../auth/auth_check.php';

header('Content-Type: application/json');

checkAdminAccess($conn, 'can_view_customers');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if (!$user_id && !$product_id) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

$conditions = [];
$params = [];
$types = '';

if ($user_id) {
    $conditions[] = "pr.user_id = ?";
    $params[] = $user_id;
    $types .= 'i';
}
if ($product_id) {
    $conditions[] = "pr.product_id = ?";
    $params[] = $product_id;
    $types .= 'i';
}

$where = implode(' AND ', $conditions);

$stmt = $conn->prepare("
    SELECT pr.review_id, pr.rating, pr.comment, pr.created_at,
           u.fname, u.lname,
           p.product_name, pi.image_url AS product_image
    FROM product_reviews pr
    JOIN users u ON pr.user_id = u.user_id
    JOIN products p ON pr.product_id = p.product_id
    LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
    WHERE $where
    ORDER BY pr.created_at DESC
");
$stmt->bind_param($types, ...$params);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode(['success' => true, 'reviews' => $reviews]);
?>