<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/customers/customer_data.php';
include '../../backend/auth/auth_check.php';

checkAnyPermission($conn, ['can_assign_admins']);

$success = $_SESSION['success'] ?? null;
$error   = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$can_assign   = hasPermission($conn, 'can_assign_admins');

// Only admins with can_assign_admins OR superadmins may access this page
if (!$can_assign && !$isSuperadmin) {
    header('Location: customerList.php');
    exit;
}

// Permissions that can be granted; current admin can only grant what they themselves hold
$my_perms = getAdminPermissions($conn);

$all_perms_list = [
    'can_update_orders'      => 'Update Orders',
    'can_add_product'        => 'Add Product',
    'can_edit_product'       => 'Edit Product',
    'can_delete_product'     => 'Delete Product',
    'can_block_customers'    => 'Block Customers',
    'can_assign_admins'      => 'Assign Admins',
    'can_export_report'      => 'Export Report',
    'can_message_customers'  => 'Message Customers',
];

// --- Filter / sort params ---
$filter_search = trim($_GET['search'] ?? '');
$filter_sort   = $_GET['sort'] ?? 'name-asc';

// Build ORDER BY
$order_by = match ($filter_sort) {
    'name-desc' => 'u.fname DESC',
    default     => 'u.fname ASC',
};

// Build WHERE
$where_clauses = [];
$params        = [];
$types         = '';

if ($filter_search !== '') {
    $like = '%' . $filter_search . '%';
    $where_clauses[] = "(u.fname LIKE ? OR u.lname LIKE ? OR u.email LIKE ?)";
    $params = [$like, $like, $like];
    $types  = 'sss';
}

$where_sql = $where_clauses ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

$sql = "SELECT u.user_id, u.fname, u.lname, u.email, a.admin_id, ap.*
        FROM admins a
        JOIN users u ON a.user_id = u.user_id
        LEFT JOIN admin_permissions ap ON a.admin_id = ap.admin_id
        $where_sql
        ORDER BY $order_by";

if ($params) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $admins = $stmt->get_result();
} else {
    $admins = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Management</title>
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include '../../components/adminSideBar.php'; ?>

<div class="main-content">
    <?php include '../../components/adminNavbar.php'; ?>

    <main class="container">

        <h2 class="page-title">Admin Management</h2>

        <!-- Filter Bar -->
        <form method="GET" action="">
            <div class="filter-bar">

                <div class="filter-group" style="position:relative;">
                    <label>SEARCH:</label>
                    <input type="text" name="search" id="admin-search-input"
                           placeholder="Search by name or email..."
                           value="<?= htmlspecialchars($filter_search) ?>">
                    <div id="admin-search-suggestions" class="suggestions-box" style="display:none;"></div>
                </div>

                <div class="filter-group">
                    <label>SORT BY:</label>
                    <select name="sort" id="sort-filter">
                        <option value="name-asc"  <?= $filter_sort === 'name-asc'  ? 'selected' : '' ?>>Name: A–Z</option>
                        <option value="name-desc" <?= $filter_sort === 'name-desc' ? 'selected' : '' ?>>Name: Z–A</option>
                    </select>
                </div>

                <button type="submit" class="reset-btn">Apply</button>
                <a href="adminManagement.php" class="reset-btn" style="text-decoration:none;">Reset</a>

                <!-- Assign Admin button on the far right -->
                <button type="button" class="btn-save"
                        id="open-assign-admin-btn"
                        style="margin-left:auto;">
                    + Assign Admin
                </button>

            </div>
        </form>

        <!-- Admin Table -->
        <section class="table-container">
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>PERMISSIONS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($admins) > 0): ?>
                        <?php while ($a = mysqli_fetch_assoc($admins)): ?>
                        <?php
                            $granted = [];
                            foreach ($all_perms_list as $key => $label) {
                                if (!empty($a[$key])) $granted[] = $label;
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($a['fname'] . ' ' . $a['lname']) ?></td>
                            <td><?= htmlspecialchars($a['email']) ?></td>
                            <td style="font-size:0.82rem;">
                                <?php if ($granted): ?>
                                    <?php foreach ($granted as $g): ?>
                                        <span class="badge badge-active" style="margin:2px;"><?= htmlspecialchars($g) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span style="color:#aaa;">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="display:flex; gap:0.4rem; flex-wrap:wrap;">

                                <!-- Message (superadmin only) -->
                                <?php if ($isSuperadmin): ?>
                                <a href="messages.php?admin_id=<?= $a['admin_id'] ?>" class="btn-edit">Message</a>
                                <?php endif; ?>

                                <!-- View / Edit Permissions -->
                                <button class="btn-view-details view-permissions-btn"
                                    data-admin-id="<?= $a['admin_id'] ?>"
                                    data-name="<?= htmlspecialchars($a['fname'] . ' ' . $a['lname']) ?>"
                                    data-perms="<?= htmlspecialchars(json_encode($a)) ?>">
                                    View Permissions
                                </button>

                                <!-- Remove Admin -->
                                <form method="POST" action="../../backend/customers/assign_admin.php" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $a['user_id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="button" class="btn-delete"
                                        onclick="openConfirmModal('<?= $a['user_id'] ?>', '<?= htmlspecialchars($a['fname'] . ' ' . $a['lname']) ?>', 'remove-admin', this)">
                                        Remove Admin
                                    </button>
                                </form>

                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center; color:#aaa;">No admins found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

<!--  MODALS  -->

<!-- Confirm Action Modal -->
<div class="modal-overlay" id="confirm-action-modal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Confirm Action</span>
            <button class="modal-close" id="confirm-close">&times;</button>
        </div>
        <div class="modal-body" id="confirm-body">Are you sure?</div>
        <div class="modal-footer">
            <button class="btn-cancel" id="confirm-cancel">Cancel</button>
            <button class="btn-save"   id="confirm-yes">Yes</button>
        </div>
    </div>
</div>

<!-- Assign Admin Modal -->
<div class="modal-overlay" id="assign-admin-modal">
    <div class="modal" style="max-width:480px;">
        <div class="modal-header">
            <span class="modal-title">Assign Admin</span>
            <button class="modal-close" id="assign-admin-modal-close">&times;</button>
        </div>

        <form method="POST" action="../../backend/customers/assign_admin.php">
            <input type="hidden" name="user_id" id="assign-user-id">

            <!-- Customer live-search -->
            <div class="form-group" style="position:relative; margin-bottom:0.5rem;">
                <label>SEARCH CUSTOMER:</label>
                <input type="text" id="customer-search-input"
                       placeholder="Type name or email..."
                       autocomplete="off">
                <div id="customer-search-results"
                     style="position:absolute; top:100%; left:0; right:0; z-index:4000;
                            background:rgb(14,16,31); border:1px solid rgba(201,169,97,0.4);
                            border-radius:4px; max-height:220px; overflow-y:auto; display:none;">
                </div>
            </div>

            <!-- Selected customer display -->
            <div id="selected-customer-display"
                 style="display:none; margin-bottom:1rem; padding:0.6rem 0.9rem;
                        background:rgba(201,169,97,0.08); border:1px solid rgba(201,169,97,0.3);
                        border-radius:4px; font-size:0.9rem; color:#c9a961;">
            </div>

            <p style="margin-bottom:0.75rem; font-size:0.85rem; color:#aaa;">Select permissions to grant:</p>

            <?php foreach ($all_perms_list as $key => $label): ?>
                <?php if ($my_perms !== null && empty($my_perms[$key])) continue; ?>
                <div class="form-group" style="margin-bottom:0.4rem;">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" name="permissions[]" value="<?= $key ?>">
                        <?= $label ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="assign-admin-cancel">Cancel</button>
                <button type="submit" class="btn-save" id="assign-admin-submit">Assign Admin</button>
            </div>
        </form>
    </div>
</div>

<!-- View / Edit Permissions Modal -->
<div class="modal-overlay" id="permissions-modal">
    <div class="modal" style="max-width:450px;">
        <div class="modal-header">
            <span class="modal-title">Permissions — <span id="permissions-modal-name"></span></span>
            <button class="modal-close" id="permissions-modal-close">&times;</button>
        </div>

        <form method="POST" action="../../backend/customers/update_permissions.php">
            <input type="hidden" name="admin_id" id="permissions-admin-id">
            <p style="margin-bottom:1rem; font-size:0.9rem; color:#aaa;">Update permissions:</p>

            <?php foreach ($all_perms_list as $key => $label): ?>
                <?php if ($my_perms !== null && empty($my_perms[$key])) continue; ?>
                <div class="form-group" style="margin-bottom:0.4rem;">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" class="perm-checkbox"
                               name="permissions[]" value="<?= $key ?>"
                               data-perm="<?= $key ?>">
                        <?= $label ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="permissions-modal-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast -->
<div id="generalToast" class="generalToast"></div>

<script src="../../assets/js/AdminPanel.js"></script>
<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/adminManagement.js"></script>

<?php if ($success): ?>
    <script>showGeneralToast("<?= htmlspecialchars($success) ?>", "success");</script>
<?php endif; ?>
<?php if ($error): ?>
    <script>showGeneralToast("<?= htmlspecialchars($error) ?>", "error");</script>
<?php endif; ?>

</body>
</html>