<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$sql = "SELECT fname, lname, bday, phone, email, profile_photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Processing orders (pending, paid, shipped, delivered)
$stmt2 = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status IN ('pending', 'paid', 'shipped', 'delivered')");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$processing = $stmt2->get_result()->fetch_assoc();

// Completed orders
$stmt3 = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status = 'completed'");
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$completed = $stmt3->get_result()->fetch_assoc();

// To Review = received orders (ready for customer review/feedback)
$stmt4 = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND order_status = 'received'");
$stmt4->bind_param("i", $user_id);
$stmt4->execute();
$to_review = $stmt4->get_result()->fetch_assoc();

// Recent order history (last 3)
$stmt5 = $conn->prepare("SELECT o.order_id, o.created_at, o.total_amount, p.product_name
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.order_id
    JOIN products p ON p.product_id = oi.product_id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
    LIMIT 3");
$stmt5->bind_param("i", $user_id);
$stmt5->execute();
$history_result = $stmt5->get_result();
$history = [];
while ($row = $history_result->fetch_assoc()) {
    $history[] = $row;
}

// Wishlist (last 3)
$stmt6 = $conn->prepare("SELECT p.product_id, p.product_name, p.price, p.discounted_price, pi.image_url
    FROM wishlist w
    JOIN products p ON p.product_id = w.product_id
    LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
    WHERE w.user_id = ?
    ORDER BY w.added_at DESC
    LIMIT 3");
$stmt6->bind_param("i", $user_id);
$stmt6->execute();
$wishlist_result = $stmt6->get_result();
$wishlist = [];
while ($row = $wishlist_result->fetch_assoc()) {
    $wishlist[] = $row;
}

// Format birthday for display
$bday_display = '';
if ($user['bday']) {
    $bday_display = date('F j, Y', strtotime($user['bday']));
}

echo json_encode([
    'success' => true,
    'user' => [
        'fname' => $user['fname'],
        'lname' => $user['lname'],
        'bday' => $user['bday'],
        'bday_display' => $bday_display,
        'phone' => $user['phone'],
        'email' => $user['email'],
        'profile_photo' => $user['profile_photo']
    ],
    'orders' => [
        'processing' => $processing['count'],
        'to_review' => $to_review['count'],
        'completed' => $completed['count']
    ],
    'history' => $history,
    'wishlist' => $wishlist
]);
?>