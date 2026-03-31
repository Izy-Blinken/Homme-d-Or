<?php
include __DIR__ . '/../db_connect.php';


function getAdminPermissions($conn) {

    // check if superadmin ang nakalogin, if yes walang rereturn na perms since all access sya
    if (!empty($_SESSION['superadmin_id'])) {
        return null;
    }

    $admin_id = $_SESSION['admin_id'] ?? null;

    if (!$admin_id){
        return [];
    } 

    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin_permissions WHERE admin_id = '$admin_id'"));

    return $result ?? [];
}

// check kung yung current admin has a specific permission
function hasPermission($conn, $permission) {

    $perms = getAdminPermissions($conn);

    if ($perms === null) {
        return true; // superadmin
    }

    return !empty($perms[$permission]);
}


// cust list
$filter_status = $_GET['status'] ?? 'active'; 
$filter_sort = $_GET['sort']   ?? '';
$filter_search = $_GET['search'] ?? '';

// ordered within 3mts = active, otherwise inactive
$active_condition = "AND (SELECT MAX(created_at) FROM orders WHERE user_id = u.user_id) >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
$inactive_condition = "AND (SELECT MAX(created_at) FROM orders WHERE user_id = u.user_id) < DATE_SUB(NOW(), INTERVAL 3 MONTH)
                       OR (SELECT COUNT(*) FROM orders WHERE user_id = u.user_id) = 0";

$where = "u.is_blocked = 0";

if ($filter_status === 'blocked') {
    $where = "u.is_blocked = 1";
    
} elseif ($filter_status === 'active') {
    $where = "u.is_blocked = 0 AND (SELECT MAX(created_at) FROM orders WHERE user_id = u.user_id) >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";

} elseif ($filter_status === 'inactive') {
    $where = "u.is_blocked = 0 AND ((SELECT MAX(created_at) FROM orders WHERE user_id = u.user_id) < DATE_SUB(NOW(), INTERVAL 3 MONTH)
              OR (SELECT COUNT(*) FROM orders WHERE user_id = u.user_id) = 0)";
}

if ($filter_search) {

    $safe = mysqli_real_escape_string($conn, $filter_search);
    $where .= " AND (u.fname LIKE '%$safe%' OR u.lname LIKE '%$safe%' OR CONCAT(u.fname, ' ', u.lname) LIKE '%$safe%' OR u.email LIKE '%$safe%')";
}

$sort_sql = "u.created_at DESC"; // default
if ($filter_sort === 'name-asc') {
    $sort_sql = "u.fname ASC";
}

if ($filter_sort === 'name-desc') {
    $sort_sql = "u.fname DESC";
}

if ($filter_sort === 'spent-desc') {
    $sort_sql = "total_spent DESC";
}

if ($filter_sort === 'orders-desc') {
    $sort_sql = "total_orders DESC";
}

if ($filter_sort === 'joined-desc') {
    $sort_sql = "u.created_at DESC";
}

$customers = mysqli_query($conn,
    "SELECT u.user_id, u.fname, u.lname, u.email, u.phone, u.profile_photo, u.is_verified, u.is_blocked, u.created_at,
            COUNT(DISTINCT o.order_id) AS total_orders,
            COALESCE(SUM(o.total_amount), 0) AS total_spent,
            MAX(o.created_at) AS last_order,
            (SELECT COUNT(*) FROM admins a WHERE a.user_id = u.user_id) AS is_admin
     FROM users u
     LEFT JOIN orders o ON u.user_id = o.user_id
     WHERE $where
     GROUP BY u.user_id
     ORDER BY $sort_sql"
);