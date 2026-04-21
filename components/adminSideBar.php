<!-- This is to limit the links na makikita lang ni reg admin sa sidebar
 base sa permission na meron sya from superadmin(developer) -->

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$isSuperadmin = !empty($_SESSION['superadmin_id']);

$sidebarPerms = [];

if (!$isSuperadmin && !empty($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $sidebarPerms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin_permissions WHERE admin_id = '$admin_id'")) ?? [];
}

function canSee($perm) {
    global $isSuperadmin, $sidebarPerms;
    return $isSuperadmin || !empty($sidebarPerms[$perm]);
}

function canSeeAny(array $perms) {
    global $isSuperadmin, $sidebarPerms;

    if ($isSuperadmin) return true;

    foreach ($perms as $p) {
        if (!empty($sidebarPerms[$p])) return true;
    }
    return false;
}
?>

<div class="overlay" id="sidebar-overlay"></div>

<div class="sidebar" id="admin-sidebar">

    <div class="sidebar-header">
        <span>ADMIN PANEL</span>
        <button type="button" class="close-icon" id="close-btn">&times;</button>
    </div>

    <nav class="sidebar-nav">

        <!-- dashboard: everyone -->
        <a href="adminSide.php" class="menu-opt <?= $currentPage === 'adminSide.php' ? 'active' : '' ?>">Dashboard</a>

        <!-- permissions -->
        <?php if (canSeeAny(['can_add_product', 'can_edit_product', 'can_delete_product'])): ?>
        <a href="productManagement.php" class="menu-opt <?= $currentPage === 'productManagement.php' ? 'active' : '' ?>">Product Management</a>
        <?php endif; ?>

        <?php if (canSee('can_update_orders')): ?>
        <a href="orderManagement.php" class="menu-opt <?= $currentPage === 'orderManagement.php' ? 'active' : '' ?>">Order Management</a>
        <?php endif; ?>

        <?php if (canSeeAny(['can_block_customers', 'can_assign_admins', 'can_message_customers'])): ?>
        <a href="customerList.php" class="menu-opt <?= $currentPage === 'customerList.php' ? 'active' : '' ?>">Customer List</a>
        <?php endif; ?>

        <?php if (canSee('can_export_report')): ?>
        <a href="salesReport.php" class="menu-opt <?= $currentPage === 'salesReport.php' ? 'active' : '' ?>">Sales Report</a>
        <?php endif; ?>

        <?php if ($isSuperadmin): ?>
        <a href="adminManagement.php" class="menu-opt <?= $currentPage === 'adminManagement.php' ? 'active' : '' ?>">Assign Admin</a>
        <a href="voucherManagement.php" class="menu-opt <?= $currentPage === 'voucherManagement.php' ? 'active' : '' ?>">Vouchers</a>
        <a href="admin_blog.php" class="menu-opt <?= $currentPage === 'admin_blog.php' ? 'active' : '' ?>">Blog</a>
        <a href="admin_about.php" class="menu-opt <?= $currentPage === 'admin_about.php' ? 'active' : '' ?>">About Us</a>
        <a href="admin_newsletter.php" class="menu-opt <?= $currentPage === 'admin_newsletter.php' ? 'active' : '' ?>">Newsletter</a>
        <?php endif; ?>

        <!-- chats: lahat -->
        <a href="messages.php" class="menu-opt <?= $currentPage === 'messages.php' ? 'active' : '' ?>">Chats</a>

        <!-- profile: superadmin only -->
        <?php if ($isSuperadmin): ?>
        <a href="adminProfile.php" class="menu-opt <?= $currentPage === 'adminProfile.php' ? 'active' : '' ?>">Profile</a>
        <?php endif; ?>

        <!-- logout: reg admin only -->
        <?php if (!$isSuperadmin): ?>
        <button type="button" class="menu-opt" id="logout-btn" style="background:none; border:none; text-align:left; width:100%; cursor:pointer;">Logout</button>
        <?php endif; ?>

    </nav>

</div>