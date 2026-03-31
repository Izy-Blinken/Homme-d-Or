<?php
session_start();
include __DIR__ . '/../db_connect.php';

$user_id = $_POST['user_id'] ?? null;
$permissions = $_POST['permissions'] ?? [];

if (!$user_id) {

    $_SESSION['error'] = 'Invalid request.';
    header('Location: ../../pages/Admin Pages/customerList.php');

    exit;
}

// check kung admin na
$existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT admin_id FROM admins WHERE user_id = '$user_id'"));

if ($existing) {

    $_SESSION['error'] = 'User is already an admin.';
    header('Location: ../../pages/Admin Pages/customerList.php');

    exit;
}

// else
mysqli_query($conn, "INSERT INTO admins (user_id) VALUES ('$user_id')");
$admin_id = mysqli_insert_id($conn);

// assign permissions
$all_perms = [
    'can_update_orders', 'can_add_product', 'can_edit_product',
    'can_delete_product', 'can_block_customers', 'can_assign_admins'
];

$perm_values = [];
foreach ($all_perms as $perm) {
    $perm_values[$perm] = in_array($perm, $permissions) ? 1 : 0;
}

mysqli_query($conn, "INSERT INTO admin_permissions (admin_id, can_update_orders, can_add_product, can_edit_product, can_delete_product, can_block_customers, can_assign_admins)
    VALUES ('$admin_id', {$perm_values['can_update_orders']}, {$perm_values['can_add_product']}, {$perm_values['can_edit_product']}, {$perm_values['can_delete_product']}, {$perm_values['can_block_customers']}, {$perm_values['can_assign_admins']})");

$_SESSION['success'] = 'Admin assigned successfully.';
header('Location: ../../pages/Admin Pages/customerList.php');

exit;