<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/auth/auth_check.php';
checkAdminAccess($conn);

if (empty($_SESSION['superadmin_id'])) {
    header('Location: ../../pages/Admin Pages/adminSide.php');
    exit;
}

$superadmin_id = (int) $_SESSION['superadmin_id'];

// Store details
$store = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM store_settings WHERE id = 1"
));

$sadmin = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT email FROM superadmins WHERE superadmin_id = $superadmin_id"
));

// Sale performance
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products"))['val'];
$activeProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE product_status = 'in-stock'"))['val'];
$outOfStock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE product_status = 'out-of-stock'"))['val'];
$totalCategories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM categories"))['val'];
$avgRating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(ROUND(AVG(rating), 1), 0) AS val FROM product_reviews"))['val'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders"))['val'];
$completedOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'completed'"))['val'];
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'pending'"))['val'];
$cancelledOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'cancelled'"))['val'];
$successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

// Financial Summary
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed'"))['val'];

$revenueThisMonth = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders
     WHERE order_status = 'completed'
       AND MONTH(created_at) = MONTH(CURRENT_DATE())
       AND YEAR(created_at) = YEAR(CURRENT_DATE())"))['val'];

$revenueLastMonth = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders
     WHERE order_status = 'completed'
       AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
       AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)"))['val'];

$revenueGrowth = $revenueLastMonth > 0
    ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
    : 0;

$avgOrderValue = $completedOrders > 0
    ? round($totalRevenue / $completedOrders, 2)
    : 0;

// Customer Statistics
$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM users"))['val'];

$returningCustomers = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM (SELECT user_id FROM orders WHERE user_id IS NOT NULL GROUP BY user_id HAVING COUNT(*) > 1)
     AS ret"))['val'];

$newThisMonth = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users
     WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
       AND YEAR(created_at) = YEAR(CURRENT_DATE())"))['val'];

$newLastMonth = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users
     WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
       AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)"))['val'];

$customerGrowth = $newLastMonth > 0
    ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100, 1)
    : 0;

// Customer Lifetime Value: total revenue / total customers
$clv = $totalCustomers > 0
    ? round($totalRevenue / $totalCustomers, 2)
    : 0;

$retentionRate = $totalCustomers > 0
    ? round(($returningCustomers / $totalCustomers) * 100, 1)
    : 0;

// New customers (per week)
$newThisWeek = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM users
     WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)"))['val'];

$brandName = $store['brand_name'] ?? '';
$logoFile = $store['logo'] ?? null;

$words = array_filter(explode(' ', $brandName));
$initials = '';
foreach ($words as $w) {
    $initials .= strtoupper($w[0]);
    if (strlen($initials) >= 2) break;
}

if (empty($initials)) $initials = 'H';
?>