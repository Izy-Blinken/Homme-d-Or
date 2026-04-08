<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/auth/auth_check.php';

if (empty($_SESSION['superadmin_id'])) {
    header('Location: adminSide.php');
    exit;
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$vouchers = mysqli_query($conn, "SELECT d.*, u.fname, u.lname
     FROM discounts d
     LEFT JOIN users u ON d.assigned_to_user_id = u.user_id
     ORDER BY d.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Voucher Management</title>
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>

    <body>

        <?php include '../../components/adminSideBar.php'; ?>

        <div class="main-content">

        <?php include '../../components/adminNavbar.php'; ?>


            <main class="container">

                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 class="page-title" style="margin-bottom:0;">Voucher Management</h2>
                    <button class="add-product-btn" id="add-voucher-btn">+ Add Voucher</button>
                </div>

                <section class="table-container">

                    <div class="responsive-table">

                        <table style="width:100%; border-collapse:collapse;">

                            <thead>

                                <tr>
                                    <th>CODE</th>
                                    <th>TYPE</th>
                                    <th>VALUE</th>
                                    <th>USAGE</th>
                                    <th>ASSIGNED TO</th>
                                    <th>EXPIRY</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (mysqli_num_rows($vouchers) > 0): ?>

                                    <?php while ($v = mysqli_fetch_assoc($vouchers)): ?>
                                    <?php
                                        $expired = strtotime($v['expires_at']) < time();
                                        $maxed = $v['used_count'] >= $v['usage_limit'];
                                        $status = $expired ? 'Expired' : ($maxed ? 'Maxed Out' : 'Active');
                                        $status_class = $expired || $maxed ? 'badge-cancelled' : 'badge-delivered';
                                    ?>

                                    <tr>
                                        <td><strong><?= htmlspecialchars($v['code']) ?></strong></td>
                                        <td><?= ucfirst($v['discount_type']) ?></td>
                                        <td>
                                            <?= $v['discount_type'] === 'percent'
                                                ? $v['discount_value'] . '%'
                                                : '₱' . number_format($v['discount_value'], 2) ?>
                                        </td>
                                        <td><?= $v['used_count'] ?> / <?= $v['usage_limit'] ?></td>
                                        <td>
                                            <?= $v['fname']
                                                ? htmlspecialchars($v['fname'] . ' ' . $v['lname'])
                                                : '<span style="color:#aaa;">—</span>' ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($v['expires_at'])) ?></td>
                                        <td><span class="badge <?= $status_class ?>"><?= $status ?></span></td>
                                        <td>
                                            <form method="POST" action="../../backend/customers/delete_voucher.php" style="display:inline;"
                                                onsubmit="return confirm('Delete voucher <?= htmlspecialchars($v['code']) ?>?')">
                                                <input type="hidden" name="discount_id" value="<?= $v['discount_id'] ?>">
                                                <button type="submit" class="btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>
                                    <tr><td colspan="8" style="text-align:center;">No vouchers yet.</td></tr>
                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </section>

            </main>

        </div>


        <!-- Add Voucher Modal -->
        <div class="modal-overlay" id="add-voucher-modal">

            <div class="modal" style="max-width:420px;">

                <div class="modal-header">
                    <span class="modal-title">Add Voucher</span>
                    <button class="modal-close" id="add-voucher-modal-close">&times;</button>
                </div>

                <form method="POST" action="../../backend/customers/create_voucher.php">

                    <div class="form-group">
                        <label>VOUCHER TYPE</label>
                        <select name="voucher_type" id="voucher-type-select">
                            <option value="individual">Individual (assign to customer later)</option>
                            <option value="broadcast">Broadcast (send to all customers)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>DISCOUNT TYPE</label>
                        <select name="discount_type">
                            <option value="percent">Percent (%)</option>
                            <option value="fixed">Fixed (₱)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>DISCOUNT VALUE</label>
                        <input type="number" name="discount_value" min="1" placeholder="e.g. 20" required>
                    </div>

                    <div class="form-group" id="limit-group">
                        <label>USAGE LIMIT</label>
                        <input type="number" name="usage_limit" min="1" value="1" placeholder="e.g. 5" required>
                    </div>

                    <div class="form-group">
                        <label>EXPIRY DATE</label>
                        <input type="date" name="expires_at" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="add-voucher-cancel">Cancel</button>
                        <button type="submit" class="btn-save">Create Voucher</button>
                    </div>

                </form>

            </div>

        </div>


        <div id="generalToast" class="generalToast"></div>

        <script src="../../assets/js/AdminPanel.js"></script>
        <script src="../../assets/js/script.js"></script>

        <script src="../../assets/js/voucher.js"></script>

        <?php if ($success): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showGeneralToast("<?= htmlspecialchars($success) ?>", "success"));</script>
        <?php endif; ?>

        <?php if ($error): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showGeneralToast("<?= htmlspecialchars($error) ?>", "error"));</script>
        <?php endif; ?>

    </body>

</html>