<?php
session_start();
include '../../db_connect.php';

// Check admin access
include '../../auth/auth_check.php';
checkAdminAccess($conn, 'can_view_customers');

$user_id = $_GET['user_id'] ?? '';
$product_id = $_GET['product_id'] ?? '';

if (!$user_id && !$product_id) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$query = "
    SELECT pr.review_id, pr.rating, pr.comment, pr.created_at, 
           u.fname, u.lname, p.product_name
    FROM product_reviews pr
    JOIN users u ON pr.user_id = u.user_id
    JOIN products p ON pr.product_id = p.product_id
    WHERE 1=1
";

if ($user_id) {
    $query .= " AND pr.user_id = " . intval($user_id);
}
if ($product_id) {
    $query .= " AND pr.product_id = " . intval($product_id);
}

$query .= " ORDER BY pr.created_at DESC";

$result = $conn->query($query);
$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'reviews' => $reviews]);
?>
