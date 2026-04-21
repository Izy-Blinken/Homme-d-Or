<?php
session_start();
include __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../notifications/notify.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/adminManagement.php');
    exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
$action = $_POST['action'] ?? 'assign';

if (!$user_id) {
    $_SESSION['error'] = 'Invalid user.';
    header('Location: ../../pages/Admin Pages/adminManagement.php');
    exit;
}

if ($action === 'remove') {
    $row = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT admin_id FROM admins WHERE user_id = '$user_id'"));

    if ($row) {
        $admin_id = $row['admin_id'];
        mysqli_query($conn, "DELETE FROM admin_permissions WHERE admin_id = '$admin_id'");
        mysqli_query($conn, "DELETE FROM admins WHERE admin_id = '$admin_id'");

        if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) {
            session_destroy();
            header('Location: ../../pages/Admin Pages/adminLogin.php');
            exit;
        }
    }

    $_SESSION['success'] = 'Admin access removed.';
    header('Location: ../../pages/Admin Pages/adminManagement.php');
    exit;
}

// assign flow
$permissions = $_POST['permissions'] ?? [];

$allowed_perms = [
    'can_update_orders',
    'can_add_product',
    'can_edit_product',
    'can_delete_product',
    'can_block_customers',
    'can_assign_admins',
    'can_export_report',
    'can_message_customers',
];

$existing = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT admin_id FROM admins WHERE user_id = '$user_id'"));

if ($existing) {
    $_SESSION['error'] = 'User is already an admin.';
    header('Location: ../../pages/Admin Pages/adminManagement.php');
    exit;
}

mysqli_query($conn, "INSERT INTO admins (user_id) VALUES ('$user_id')");
$admin_id = mysqli_insert_id($conn);

$cols = implode(', ', $allowed_perms);
$vals = implode(', ', array_map(fn($p) => in_array($p, $permissions) ? '1' : '0', $allowed_perms));

mysqli_query($conn, "INSERT INTO admin_permissions (admin_id, $cols) VALUES ('$admin_id', $vals)");

// ── ADMIN ASSIGNMENT NOTIFICATION ─────────────────────────────────
insertNotif($conn, $user_id, 'admin_assignment',
    "You have been assigned as an admin. Welcome to the team!", null);

$_SESSION['success'] = 'Admin assigned successfully.';
header('Location: ../../pages/Admin Pages/adminManagement.php');
exit;
?>