
<?php
// This is to check the permissions na meron lang si reg admin
function getAdminPermissions($conn) {

    // superadmin = full access, no perms needed
    if (!empty($_SESSION['superadmin_id'])) {
        return null;
    }

    $admin_id = $_SESSION['admin_id'] ?? null;

    if (!$admin_id) {
        return [];
    }

    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin_permissions WHERE admin_id = '$admin_id'"));

    return $result ?? [];
}


function hasPermission($conn, $permission) {

    $perms = getAdminPermissions($conn);

    if ($perms === null) {
        return true; // superadmin
    }

    return !empty($perms[$permission]);
}


function checkAdminAccess($conn, $required_permission = null) {

    if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
        header('Location: ../../pages/Admin Pages/adminLogin.php');
        exit;
    }

    // superadmin
    if (!empty($_SESSION['superadmin_id'])) {
        return;
    }

    // reg admin
    if ($required_permission !== null) {

        $admin_id = $_SESSION['admin_id'];
        $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin_permissions WHERE admin_id = '$admin_id'"));

        if (!$result || empty($result[$required_permission])) {
            header('Location: ../../pages/Admin Pages/adminSide.php');
            exit;
        }
    }
}

//check perms in array, allow if has at least one
function checkAnyPermission($conn, array $permissions) {

    if (!empty($_SESSION['superadmin_id'])) {
        return;
    }

    $admin_id = $_SESSION['admin_id'];
    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin_permissions WHERE admin_id = '$admin_id'"));

    if (!$result) {
        header('Location: ../../pages/Admin Pages/adminSide.php');
        exit;
    }

    foreach ($permissions as $perm) {
        if (!empty($result[$perm])) {
            return; // has at least one
        }
    }

    header('Location: ../../pages/Admin Pages/adminSide.php');
    exit;
}