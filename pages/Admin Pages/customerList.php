<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/customers/customer_data.php';

include '../../backend/auth/auth_check.php';
checkAnyPermission($conn, ['can_block_customers', 'can_assign_admins', 'can_message_customers']);

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$can_block = hasPermission($conn, 'can_block_customers');
$can_assign = hasPermission($conn, 'can_assign_admins');
$isSuperadmin = !empty($_SESSION['superadmin_id']);

// kung ano lang perms ng current admin, yun lang pwede niyang i-grant sa iba
$my_perms = getAdminPermissions($conn);

// view toggle: customers or admins
$view = $_GET['view'] ?? 'customers';

// admin list query
$all_perms_list = [
    'can_update_orders' => 'Update Orders',
    'can_add_product' => 'Add Product',
    'can_edit_product' => 'Edit Product',
    'can_delete_product' => 'Delete Product',
    'can_block_customers' => 'Block Customers',
    'can_assign_admins' => 'Assign Admins',
    'can_export_report' => 'Export Report',
    'can_message_customers' => 'Message Customers',
];

$admins = mysqli_query($conn,
    "SELECT u.user_id, u.fname, u.lname, u.email, a.admin_id, ap.*
     FROM admins a
     JOIN users u ON a.user_id = u.user_id
     LEFT JOIN admin_permissions ap ON a.admin_id = ap.admin_id
     ORDER BY u.fname ASC");

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Customer List</title>
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
         <link rel="stylesheet" href="../../assets/css/style.css">
    </head>

    <body>

        <?php include '../../components/adminSideBar.php'; ?>

        <div class="main-content">

            <header class="navbar">

                <div class="navbar-left">
                    <button class="hamburger" id="menu-btn"><span></span><span></span><span></span></button>
                    <h1 class="navbar-title">ADMIN PANEL</h1>
                </div>

                <div class="navbar-search">

                    <svg width="16" height="16" fill="none" stroke="#888" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Search...">

                </div>

                <div class="navbar-avatar">A</div>

            </header>

            <main class="container">

                <h2 class="page-title"><?= $view === 'admins' ? 'Admin List' : 'Customer List' ?></h2>

                <?php if ($view === 'customers'): ?>

                <!-- Filter -->
                <form method="GET" action="">

                    <div class="filter-bar">

                        <div class="filter-group">

                            <label>STATUS:</label>
                            <select name="status" id="status-filter">
                                <option value="all" <?= $filter_status === 'all' ? 'selected' : '' ?>>All</option>
                                <option value="active" <?= $filter_status === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $filter_status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="blocked" <?= $filter_status === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                            </select>

                        </div>

                        <div class="filter-group">

                            <label>SORT BY:</label>
                            <select name="sort" id="sort-filter">
                                <option value="">Default</option>
                                <option value="name-asc" <?= $filter_sort === 'name-asc' ? 'selected' : '' ?>>Name: A–Z</option>
                                <option value="name-desc" <?= $filter_sort === 'name-desc' ? 'selected' : '' ?>>Name: Z–A</option>
                                <option value="spent-desc" <?= $filter_sort === 'spent-desc' ? 'selected' : '' ?>>Highest Spent</option>
                                <option value="orders-desc" <?= $filter_sort === 'orders-desc' ? 'selected' : '' ?>>Most Orders</option>
                                <option value="joined-desc" <?= $filter_sort === 'joined-desc' ? 'selected' : '' ?>>Newest Joined</option>
                            </select>

                        </div>

                        <div class="filter-group search-group" style="position:relative;">
                            <label>SEARCH:</label>
                            <input type="text" name="search" placeholder="Search customers..."
                                id="search-input" value="<?= htmlspecialchars($filter_search) ?>">
                        </div>

                        <button type="submit" class="reset-btn">Apply</button>
                        <a href="customerList.php" id="reset-link" class="reset-btn" style="text-decoration:none; color:black;">Reset</a>

                        <?php if ($can_assign || $isSuperadmin): ?>
                        <a href="customerList.php?view=admins" class="btn-edit" style="text-decoration:none; margin-left:auto;">View Admins</a>
                        <?php endif; ?>

                    </div>

                </form>

                <!-- Customer Table -->
                <section class="table-container">

                    <div class="responsive-table">

                        <table style="width:100%; border-collapse:collapse;">

                            <thead>
                                <tr>
                                    <th>CUSTOMER</th>
                                    <th>EMAIL</th>
                                    <th>PHONE</th>
                                    <th>ORDERS</th>
                                    <th>TOTAL SPENT</th>
                                    <th>JOIN DATE</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (mysqli_num_rows($customers) > 0): ?>

                                    <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                                    <?php
                                        $is_active = $c['last_order'] && strtotime($c['last_order']) >= strtotime('-3 months');
                                        $status_label = $c['is_blocked'] ? 'Blocked' : ($is_active ? 'Active' : 'Inactive');
                                        $status_class = $c['is_blocked'] ? 'blocked' : ($is_active ? 'active' : 'inactive');
                                    ?>

                                    <tr>
                                        <td><?= htmlspecialchars($c['fname'] . ' ' . $c['lname']) ?></td>
                                        <td><?= htmlspecialchars($c['email']) ?></td>
                                        <td><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                                        <td><?= $c['total_orders'] ?></td>
                                        <td>₱<?= number_format($c['total_spent'], 2) ?></td>
                                        <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                                        <td><span class="badge badge-<?= $status_class ?>"><?= $status_label ?></span></td>
                                        
                                        <td style="display:flex; gap:0.4rem; flex-wrap:wrap;">

                                            <?php if (!$c['is_blocked']): ?>

                                            <!-- View Details -->
                                            <button class="btn-view-details view-customer-btn"
                                                data-id="<?= $c['user_id'] ?>"
                                                data-name="<?= htmlspecialchars($c['fname'] . ' ' . $c['lname']) ?>"
                                                data-email="<?= htmlspecialchars($c['email']) ?>"
                                                data-phone="<?= htmlspecialchars($c['phone'] ?? '—') ?>"
                                                data-orders="<?= $c['total_orders'] ?>"
                                                data-spent="₱<?= number_format($c['total_spent'], 2) ?>"
                                                data-joined="<?= date('M d, Y', strtotime($c['created_at'])) ?>"
                                                data-last-order="<?= $c['last_order'] ? date('M d, Y', strtotime($c['last_order'])) : '—' ?>"
                                                data-status="<?= $status_label ?>"
                                                data-verified="<?= $c['is_verified'] ? 'Verified' : 'Not Verified' ?>"
                                                data-is-admin="<?= $c['is_admin'] ? '1' : '0' ?>"
                                                data-photo="<?= htmlspecialchars($c['profile_photo'] ?? '') ?>">
                                                View
                                            </button>

                                            <!-- Reviews -->
                                            <button class="btn-edit view-reviews-btn"
                                                data-id="<?= $c['user_id'] ?>"
                                                data-name="<?= htmlspecialchars($c['fname'] . ' ' . $c['lname']) ?>">
                                                Reviews
                                            </button>

                                            <!-- Message -->
                                            <?php if ((hasPermission($conn, 'can_message_customers') || $isSuperadmin) && $c['user_id'] !== ($_SESSION['user_id'] ?? null)): ?>
                                            <a href="messages.php?user_id=<?= $c['user_id'] ?>" class="btn-edit">Message</a>
                                            <?php endif; ?>

                                            <!-- Send Voucher -->
                                            <?php if ($isSuperadmin): ?>
                                            <button class="btn-edit send-voucher-btn"
                                                data-id="<?= $c['user_id'] ?>"
                                                data-name="<?= htmlspecialchars($c['fname'] . ' ' . $c['lname']) ?>">
                                                Send Voucher
                                            </button>
                                            <?php endif; ?>

                                            <!-- Make/Remove Admin -->
                                            <?php if ($can_assign): ?>

                                                <?php if (!$c['is_admin']): ?>

                                                <button class="btn-edit assign-admin-btn"
                                                    data-id="<?= $c['user_id'] ?>"
                                                    data-name="<?= htmlspecialchars($c['fname'] . ' ' . $c['lname']) ?>">
                                                    Make Admin
                                                </button>

                                                <?php else: ?>

                                                <form method="POST" action="../../backend/customers/assign_admin.php" style="display:inline;"
                                                    onsubmit="return confirm('Remove admin access from <?= htmlspecialchars($c['fname']) ?>?')">
                                                    <input type="hidden" name="user_id" value="<?= $c['user_id'] ?>">
                                                    <input type="hidden" name="action" value="remove">
                                                    <button type="submit" class="btn-delete">Remove Admin</button>
                                                </form>

                                                <?php endif; ?>

                                            <?php endif; ?>

                                            <?php endif; ?>

                                            <!-- Block/Unblock -->
                                            <?php if ($can_block): ?>

                                            <form method="POST" action="../../backend/customers/block_customer.php" style="display:inline;"
                                                onsubmit="return confirm('<?= $c['is_blocked'] ? 'Unblock this customer?' : 'Block this customer?' ?>')">
                                                <input type="hidden" name="user_id" value="<?= $c['user_id'] ?>">
                                                <input type="hidden" name="action" value="<?= $c['is_blocked'] ? 'unblock' : 'block' ?>">
                                                <input type="hidden" name="status" value="<?= $filter_status ?>">
                                                <button type="submit" class="<?= $c['is_blocked'] ? 'btn-edit' : 'btn-delete' ?>">
                                                    <?= $c['is_blocked'] ? 'Unblock' : 'Block' ?>
                                                </button>
                                            </form>

                                            <?php endif; ?>

                                        </td>
                                    </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>
                                    <tr><td colspan="8" style="text-align:center;">No customers found.</td></tr>
                                <?php endif; ?>

                            </tbody>
                        </table>

                    </div>

                </section>

                <?php else: ?>

                <!-- Admin List View -->
                <div class="filter-bar" style="margin-bottom:1.5rem;">
                    <a href="customerList.php" class="btn-edit" style="text-decoration:none; margin-left:auto;">← Back to Customers</a>
                </div>

                <section class="table-container">

                    <div class="responsive-table">

                        <table style="width:100%; border-collapse:collapse;">

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
                                        <td style="font-size:0.82rem; color:#555;">
                                            <?= $granted ? implode(', ', $granted) : '—' ?>
                                        </td>
                                        <td style="display:flex; gap:0.4rem; flex-wrap:wrap;">

                                            <!-- Message -->
                                            <?php if ($isSuperadmin): ?>
                                            <a href="messages.php?admin_id=<?= $a['admin_id'] ?>" class="btn-edit">Message</a>
                                            <?php endif; ?>

                                            <!-- View/Edit Permissions -->
                                            <?php if ($can_assign || $isSuperadmin): ?>
                                            <button class="btn-view-details view-permissions-btn"
                                                data-admin-id="<?= $a['admin_id'] ?>"
                                                data-name="<?= htmlspecialchars($a['fname'] . ' ' . $a['lname']) ?>"
                                                data-perms="<?= htmlspecialchars(json_encode($a)) ?>">
                                                View Permissions
                                            </button>
                                            <?php endif; ?>

                                            <!-- Remove Admin -->
                                            <?php if ($can_assign || $isSuperadmin): ?>
                                            <form method="POST" action="../../backend/customers/assign_admin.php" style="display:inline;"
                                                onsubmit="return confirm('Remove admin access from <?= htmlspecialchars($a['fname']) ?>?')">
                                                <input type="hidden" name="user_id" value="<?= $a['user_id'] ?>">
                                                <input type="hidden" name="action" value="remove">
                                                <button type="submit" class="btn-delete">Remove Admin</button>
                                            </form>
                                            <?php endif; ?>

                                        </td>
                                    </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>
                                    <tr><td colspan="4" style="text-align:center;">No admins assigned yet.</td></tr>
                                <?php endif; ?>

                            </tbody>
                        </table>

                    </div>

                </section>

                <?php endif; ?>

            </main>

        </div>


        <!-- View Details Modal -->
        <div class="modal-overlay" id="customer-modal">

            <div class="modal" style="max-width:550px;">

                <div class="modal-header">
                    <span class="modal-title">Customer Details</span>
                    <button class="modal-close" id="customer-modal-close">&times;</button>
                </div>

                <div style="text-align:center; margin-bottom:1rem;">

                    <div id="customer-photo-wrap">

                        <div class="customer-photo-placeholder" id="customer-photo-placeholder">
                            <svg width="48" height="48" fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                            </svg>
                        </div>

                        <img id="customer-photo-img" src="" alt="Profile"
                            style="display:none; width:80px; height:80px; border-radius:50%; object-fit:cover;">

                    </div>

                    <div style="margin-top:0.5rem;">
                        <strong id="modal-cust-name"></strong>
                        <span id="modal-admin-label" style="display:none; margin-left:0.5rem;
                            background:#1a2433; color:white; font-size:0.72rem;
                            padding:2px 8px; font-weight:bold;">ADMIN</span>
                    </div>

                </div>

                <div class="detail-grid">
                    <div class="detail-item"><label>EMAIL</label><span id="modal-cust-email"></span></div>
                    <div class="detail-item"><label>PHONE</label><span id="modal-cust-phone"></span></div>
                    <div class="detail-item"><label>JOIN DATE</label><span id="modal-cust-joined"></span></div>
                    <div class="detail-item"><label>LAST ORDER</label><span id="modal-cust-last-order"></span></div>
                    <div class="detail-item"><label>TOTAL ORDERS</label><span id="modal-cust-orders"></span></div>
                    <div class="detail-item"><label>TOTAL SPENT</label><span id="modal-cust-spent"></span></div>
                    <div class="detail-item"><label>STATUS</label><span id="modal-cust-status"></span></div>
                    <div class="detail-item"><label>VERIFIED</label><span id="modal-cust-verified"></span></div>
                </div>

                <div class="modal-footer">
                    <button class="btn-cancel" id="customer-modal-done">Close</button>
                </div>

            </div>

        </div>


        <!-- Reviews Modal -->
        <div class="modal-overlay" id="reviews-modal">

            <div class="modal" style="max-width:600px;">

                <div class="modal-header">
                    <span class="modal-title">Reviews by <span id="reviews-modal-name"></span></span>
                    <button class="modal-close" id="reviews-modal-close">&times;</button>
                </div>

                <div id="reviews-list" style="max-height:400px; overflow-y:auto;">
                    <p style="text-align:center; color:#888;">Loading...</p>
                </div>

                <div class="modal-footer">
                    <button class="btn-cancel" id="reviews-modal-done">Close</button>
                </div>

            </div>

        </div>


        <!-- Single Review Modal -->
        <div class="modal-overlay" id="single-review-modal">

            <div class="modal" style="max-width:500px;">

                <div class="modal-header">
                    <span class="modal-title">Review</span>
                    <button class="modal-close" id="single-review-modal-close">&times;</button>
                </div>

                <div id="single-review-content"></div>

                <div class="modal-footer">
                    <button class="btn-cancel" id="single-review-back">Back</button>
                </div>

            </div>

        </div>


        <!-- Assign Admin Modal -->
        <?php if ($can_assign || $isSuperadmin): ?>

        <div class="modal-overlay" id="assign-admin-modal">

            <div class="modal" style="max-width:450px;">

                <div class="modal-header">
                    <span class="modal-title">Assign Admin — <span id="assign-admin-name"></span></span>
                    <button class="modal-close" id="assign-admin-modal-close">&times;</button>
                </div>

                <form method="POST" action="../../backend/customers/assign_admin.php">

                    <input type="hidden" name="user_id" id="assign-user-id">
                    <p style="margin-bottom:1rem; font-size:0.9rem; color:#555;">Select permissions to grant:</p>

                    <?php foreach ($all_perms_list as $key => $label): ?>
                        <?php if ($my_perms !== null && empty($my_perms[$key])) continue; ?>

                    <div class="form-group" style="margin-bottom:0.5rem;">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="<?= $key ?>">
                            <?= $label ?>
                        </label>
                    </div>

                    <?php endforeach; ?>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="assign-admin-cancel">Cancel</button>
                        <button type="submit" class="btn-save">Assign Admin</button>
                    </div>

                </form>

            </div>

        </div>


        <!-- View/Edit Permissions Modal -->
        <div class="modal-overlay" id="permissions-modal">

            <div class="modal" style="max-width:450px;">

                <div class="modal-header">
                    <span class="modal-title">Permissions — <span id="permissions-modal-name"></span></span>
                    <button class="modal-close" id="permissions-modal-close">&times;</button>
                </div>

                <form method="POST" action="../../backend/customers/update_permissions.php">

                    <input type="hidden" name="admin_id" id="permissions-admin-id">
                    <p style="margin-bottom:1rem; font-size:0.9rem; color:#555;">Update permissions:</p>

                    <?php foreach ($all_perms_list as $key => $label): ?>
                        <?php if ($my_perms !== null && empty($my_perms[$key])) continue; ?>

                    <div class="form-group" style="margin-bottom:0.5rem;">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="checkbox" class="perm-checkbox" name="permissions[]" value="<?= $key ?>" data-perm="<?= $key ?>">
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
        <?php endif; ?>

        <!-- Send Voucher Modal -->
        <?php if ($isSuperadmin): ?>
 
            <?php
            $available_vouchers = mysqli_query($conn, "SELECT * FROM discounts
                WHERE voucher_type = 'individual'
                AND assigned_to_user_id IS NULL
                AND used_count < usage_limit
                AND expires_at > NOW()
                ORDER BY created_at DESC");
            ?>
            
            <div class="modal-overlay" id="send-voucher-modal">
            
                <div class="modal" style="max-width:420px;">
            
                    <div class="modal-header">
                        <span class="modal-title">Send Voucher — <span id="voucher-recipient-name"></span></span>
                        <button class="modal-close" id="send-voucher-modal-close">&times;</button>
                    </div>
            
                    <input type="hidden" id="voucher-user-id">
            
                    <div class="form-group" style="padding:0 0 1rem;">

                        <label>SELECT VOUCHER</label>
                        <select id="voucher-select">

                            <option value="">Select a voucher...</option>

                            <?php while ($v = mysqli_fetch_assoc($available_vouchers)): ?>
                            
                            <option value="<?= $v['discount_id'] ?>">
                                <?= htmlspecialchars($v['code']) ?> —
                                <?= $v['discount_type'] === 'percent' ? $v['discount_value'] . '%' : '₱' . number_format($v['discount_value'], 2) ?> off
                                (expires <?= date('M d, Y', strtotime($v['expires_at'])) ?>)
                            </option>

                            <?php endwhile; ?>
                            
                        </select>

                    </div>
            
                    <?php if (mysqli_num_rows($available_vouchers) === 0): ?>
                    <p style="font-size:0.85rem; color:#888; padding:0 0 1rem;">
                        No available individual vouchers. Create one in Voucher Management first.
                    </p>
                    <?php endif; ?>
            
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="send-voucher-cancel">Cancel</button>
                        <button type="button" class="btn-save" id="send-voucher-submit">Send</button>
                    </div>
            
                </div>
            
            </div>
        
        <?php endif; ?>


        <div id="generalToast" class="generalToast"></div>

        <script src="../../assets/js/AdminPanel.js"></script>
        <script src="../../assets/js/script.js"></script>
        <script src="../../assets/js/customerList.js"></script>

        <?php if ($success): ?>
            <script>showGeneralToast("<?= htmlspecialchars($success) ?>", "success");</script>
        <?php endif; ?>
        <?php if ($error): ?>
            <script>showGeneralToast("<?= htmlspecialchars($error) ?>", "error");</script>
        <?php endif; ?>

    </body>
</html>