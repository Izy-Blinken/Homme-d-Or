<?php
session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/adminManagement.php');
    exit;
}

$admin_id = intval($_POST['admin_id'] ?? 0);
$permissions = $_POST['permissions'] ?? [];

if (!$admin_id) {
    $_SESSION['error'] = 'Invalid admin.';
    header('Location: ../../pages/Admin Pages/adminManagement.php');
    exit;
}

$allowed_perms = [
    'can_update_orders',
    'can_add_product',
    'can_edit_product',
    'can_delete_product',
    'can_block_customers',
    'can_assign_admins',
    'can_export_report',
    'can_message_customers',
    'can_export_report',
    'can_message_customers',
];

$updates = implode(', ', array_map(fn($p) => "$p = " . (in_array($p, $permissions) ? '1' : '0'), $allowed_perms));

mysqli_query($conn, "UPDATE admin_permissions SET $updates WHERE admin_id = '$admin_id'");

$_SESSION['success'] = 'Permissions updated.';
header('Location: ../../pages/Admin Pages/adminManagement.php?view=admins');
exit;