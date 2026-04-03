<?php
include __DIR__ . '/../db_connect.php';



$filter_status = $_GET['status'] ?? 'all';
$filter_sort = $_GET['sort'] ?? '';
$filter_search = $_GET['search'] ?? '';

$where = "1=1";

if ($filter_status === 'blocked') {
    $where = "u.is_blocked = 1";

} elseif ($filter_status === 'active') {
    $where = "u.is_blocked = 0 AND (SELECT MAX(created_at) FROM orders WHERE user_id = u.user_id AND order_status != 'cancelled') >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";

} elseif ($filter_status === 'inactive') {
    $where = "u.is_blocked = 0 AND ((SELECT COUNT(*) FROM orders WHERE user_id = u.user_id AND order_status != 'cancelled') = 0 OR (SELECT MAX(created_at) FROM orders WHERE user_id = u.user_id AND order_status != 'cancelled') < DATE_SUB(NOW(), INTERVAL 3 MONTH))";
}

if ($filter_search) {
    $safe = mysqli_real_escape_string($conn, $filter_search);
    $where .= " AND (u.fname LIKE '%$safe%' OR u.lname LIKE '%$safe%' OR CONCAT(u.fname, ' ', u.lname) LIKE '%$safe%' OR u.email LIKE '%$safe%')";
}

$sort_sql = "u.created_at DESC";

if ($filter_sort === 'name-asc') {
    $sort_sql = "u.fname ASC";
} elseif ($filter_sort === 'name-desc') {
    $sort_sql = "u.fname DESC";
} elseif ($filter_sort === 'spent-desc') {
    $sort_sql = "total_spent DESC";
} elseif ($filter_sort === 'orders-desc') {
    $sort_sql = "total_orders DESC";
} elseif ($filter_sort === 'joined-desc') {
    $sort_sql = "u.created_at DESC";
}

$customers = mysqli_query($conn,
    "SELECT
        u.user_id, u.fname, u.lname, u.email, u.phone,
        u.profile_photo, u.is_verified, u.is_blocked, u.created_at,
        COUNT(DISTINCT CASE WHEN o.order_status != 'cancelled' THEN o.order_id END) AS total_orders,
        COALESCE(SUM(CASE WHEN o.order_status != 'cancelled' THEN o.total_amount ELSE 0 END), 0) AS total_spent,
        MAX(CASE WHEN o.order_status != 'cancelled' THEN o.created_at END) AS last_order,
        (SELECT COUNT(*) FROM admins a WHERE a.user_id = u.user_id) AS is_admin
     FROM users u
     LEFT JOIN orders o ON u.user_id = o.user_id
     WHERE $where
     GROUP BY u.user_id
     ORDER BY $sort_sql"
);