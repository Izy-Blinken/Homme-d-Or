<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/auth/auth_check.php';
checkAdminAccess($conn);

if (empty($_SESSION['superadmin_id'])) {
    header('Location: ../../pages/Admin Pages/adminSide.php');
    exit;
}

$superadmin_id = (int) $_SESSION['superadmin_id'];

$store = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM store_settings WHERE id = 1"));
$sadmin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM superadmins WHERE superadmin_id = $superadmin_id"));

$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products"))['val'];
$activeProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE product_status = 'in-stock'"))['val'];
$outOfStock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE product_status = 'out-of-stock'"))['val'];
$totalCategories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM categories"))['val'];
$avgRating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(ROUND(AVG(rating), 1), 0) AS val FROM product_reviews"))['val'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders"))['val'];
$completedOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'completed'"))['val'];
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'pending'"))['val'];
$cancelledOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'cancelled'"))['val'];
$successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed'"))['val'];
$revenueThisWeek = (float) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed' AND DATE(created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE()"))['val'];
$revenueLastWeek = (float) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed' AND DATE(created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 13 DAY) AND DATE_SUB(CURDATE(), INTERVAL 7 DAY)"))['val'];
$revenueGrowth = ($revenueLastWeek > 0 && $revenueThisWeek >= 0) ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1) : (($revenueThisWeek > 0 && $revenueLastWeek == 0) ? 100 : 0);
$avgOrderValue = $completedOrders > 0 ? round($totalRevenue / $completedOrders, 2) : 0;

$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users"))['val'];
$returningCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM (SELECT user_id FROM orders WHERE user_id IS NOT NULL GROUP BY user_id HAVING COUNT(*) > 1) AS ret"))['val'];
$newThisWeek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users WHERE DATE(created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE()"))['val'];
$newLastWeek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users WHERE DATE(created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 13 DAY) AND DATE_SUB(CURDATE(), INTERVAL 7 DAY)"))['val'];
$customerGrowth = ($newLastWeek > 0 && $newThisWeek >= 0) ? round((($newThisWeek - $newLastWeek) / $newLastWeek) * 100, 1) : (($newThisWeek > 0 && $newLastWeek == 0) ? 100 : 0);
$clv = $totalCustomers > 0 ? round($totalRevenue / $totalCustomers, 2) : 0;
$retentionRate = $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100, 1) : 0;
$newThisWeekCount = $newThisWeek;

$brandName = $store['brand_name'] ?? '';
$logoFile = $store['logo'] ?? null;
$words = array_filter(explode(' ', $brandName));
$initials = '';
foreach ($words as $w) {
    $initials .= strtoupper($w[0]);
    if (strlen($initials) >= 2) break;
}
if (empty($initials)) $initials = 'H';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../components/adminSideBar.php'; ?>

    <div class="main-content">
        <?php include '../../components/adminNavbar.php'; ?>

        <main class="container">
            <h2 class="page-title">Profile</h2>

            <!-- Profile Header -->
            <div class="profile-header-card">
                <div class="profile-header-left">
                    <div class="profile-avatar" id="profile-avatar">
                        <?php if ($logoFile): ?>
                            <img src="../../assets/images/store_images/<?= htmlspecialchars($logoFile) ?>"
                                alt="<?= htmlspecialchars($brandName) ?>"
                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        <?php else: ?>
                            <?= htmlspecialchars($initials) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="profile-name"><?= htmlspecialchars($brandName ?: 'Brand Name') ?></div>
                        <div class="profile-role">System Administrator</div>
                    </div>
                </div>
                <button class="logout-btn" onclick="openLogoutModal(null, 'your session', 'logout', this)">Logout</button>
            </div>

            <!-- Row 1: Brand Info + Business Overview -->
            <div class="two-col-grid">
                <div class="info-card">
                    <div class="info-card-title">Brand Information</div>
                    <div class="info-row">
                        <span class="info-row-label">Brand Name:</span>
                        <span class="info-row-value"><?= htmlspecialchars($store['brand_name'] ?? '—') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Email:</span>
                        <span class="info-row-value"><?= htmlspecialchars($store['email'] ?? '—') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Phone:</span>
                        <span class="info-row-value"><?= htmlspecialchars($store['phone'] ?? '—') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Address:</span>
                        <span class="info-row-value"><?= htmlspecialchars($store['store_address'] ?? '—') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Facebook:</span>
                        <span class="info-row-value"><?= htmlspecialchars($store['facebook'] ?? '—') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Established:</span>
                        <span class="info-row-value"><?= htmlspecialchars($store['established_year'] ?? '—') ?></span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">Business Overview</div>
                    <div class="info-row">
                        <span class="info-row-label">Total Products:</span>
                        <span class="info-row-value"><?= number_format($totalProducts) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Active Products:</span>
                        <span class="info-row-value"><?= number_format($activeProducts) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Out of Stock:</span>
                        <span class="info-row-value"><?= number_format($outOfStock) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Categories:</span>
                        <span class="info-row-value"><?= number_format($totalCategories) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Average Rating:</span>
                        <span class="info-row-value"><?= $avgRating ?> / 5.0</span>
                    </div>
                </div>
            </div>

            <!-- Row 2: Sales Performance + Financial Summary -->
            <div class="two-col-grid">
                <div class="info-card">
                    <div class="info-card-title">Sales Performance</div>
                    <div class="info-row">
                        <span class="info-row-label">Total Orders:</span>
                        <span class="info-row-value"><?= number_format($totalOrders) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Completed Orders:</span>
                        <span class="info-row-value"><?= number_format($completedOrders) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Pending Orders:</span>
                        <span class="info-row-value"><?= number_format($pendingOrders) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Cancelled Orders:</span>
                        <span class="info-row-value"><?= number_format($cancelledOrders) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Order Success Rate:</span>
                        <span class="info-row-value"><?= $successRate ?>%</span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">Financial Summary</div>
                    <div class="info-row">
                        <span class="info-row-label">Total Revenue:</span>
                        <span class="info-row-value">₱<?= number_format($totalRevenue, 2) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">This Week:</span>
                        <span class="info-row-value">₱<?= number_format($revenueThisWeek, 2) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Last Week:</span>
                        <span class="info-row-value">₱<?= number_format($revenueLastWeek, 2) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Growth:</span>
                        <span class="info-row-value <?= $revenueGrowth >= 0 ? 'positive' : 'negative' ?>">
                            <?= $revenueGrowth >= 0 ? '+' : '' ?><?= $revenueGrowth ?>%
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Average Order Value:</span>
                        <span class="info-row-value">₱<?= number_format($avgOrderValue, 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Row 3: Customer Statistics (full width) -->
            <div class="customer-stats-card">
                <div class="info-card-title" style="margin-bottom:0; padding-bottom:0.75rem; border-bottom:1px solid rgba(212,175,55,0.2);">Customer Statistics</div>
                <div class="customer-stats-grid">
                    <div>
                        <div class="cstat-label">TOTAL CUSTOMERS</div>
                        <div class="cstat-value"><?= number_format($totalCustomers) ?></div>
                        <div class="cstat-change">+<?= $newThisWeek ?> this week</div>
                    </div>
                    <div>
                        <div class="cstat-label">RETURNING CUSTOMERS</div>
                        <div class="cstat-value"><?= number_format($returningCustomers) ?></div>
                        <div class="cstat-change"><?= $retentionRate ?>% retention rate</div>
                    </div>
                    <div>
                        <div class="cstat-label">NEW THIS MONTH</div>
                        <div class="cstat-value"><?= number_format($newThisWeek) ?></div>
                        <div class="cstat-change <?= $customerGrowth >= 0 ? 'positive' : 'negative' ?>">
                            <?= $customerGrowth >= 0 ? '+' : '' ?><?= $customerGrowth ?>% growth
                        </div>
                    </div>
                    <div>
                        <div class="cstat-label">CUSTOMER LIFETIME VALUE</div>
                        <div class="cstat-value">₱<?= number_format($clv, 2) ?></div>
                        <div class="cstat-change">Average per customer</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="margin-top:24px; display:flex; gap:12px;">
                <button id="edit-profile-btn" class="btn-save">
                    <i class="fa-solid fa-pen" style="margin-right:8px;"></i>Edit Info
                </button>
                <button id="change-password-btn" class="btn-save" style="background:transparent; border:1px solid rgba(212,175,55,0.5); color:#d4af37;">
                    <i class="fa-solid fa-lock" style="margin-right:8px;"></i>Change Password
                </button>
            </div>

        </main>
    </div>

    <!-- Logout Confirm Modal -->
    <div class="modal-overlay" id="logout-confirm-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">Confirm Logout</span>
                <button class="modal-close" id="logout-confirm-close">&times;</button>
            </div>
            <div class="modal-body">Are you sure you want to logout?</div>
            <div class="modal-footer">
                <button class="btn-cancel" id="logout-confirm-cancel">Cancel</button>
                <button class="btn-save" id="logout-confirm-yes">Yes</button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal (no password fields) -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal" style="max-width:600px;">
            <div class="modal-header">
                <span class="modal-title">Edit Profile Settings</span>
                <button class="modal-close" id="edit-modal-close">&times;</button>
            </div>
            <form id="edit-profile-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label>BRAND LOGO</label>
                    <input type="file" name="logo" id="logo-input" accept="image/*">
                    <?php if ($logoFile): ?>
                        <small style="color:#aaa;margin-top:4px;display:block;">Current: <?= htmlspecialchars($logoFile) ?>. Leave blank to keep.</small>
                    <?php endif; ?>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>BRAND NAME</label>
                        <input type="text" name="brand_name" value="<?= htmlspecialchars($store['brand_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>EMAIL</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($store['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>PHONE</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($store['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>WEBSITE</label>
                        <input type="text" name="website" value="<?= htmlspecialchars($store['website'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>ADDRESS</label>
                        <input type="text" name="store_address" value="<?= htmlspecialchars($store['store_address'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>ESTABLISHED YEAR</label>
                        <input type="text" name="established_year" maxlength="4" value="<?= htmlspecialchars($store['established_year'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>FACEBOOK</label>
                        <input type="text" name="facebook" value="<?= htmlspecialchars($store['facebook'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>INSTAGRAM</label>
                        <input type="text" name="instagram" value="<?= htmlspecialchars($store['instagram'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>TWITTER / X</label>
                        <input type="text" name="twitter" value="<?= htmlspecialchars($store['twitter'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>YOUTUBE</label>
                        <input type="text" name="youtube" value="<?= htmlspecialchars($store['youtube'] ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="edit-modal-cancel">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── Change Password Modal ── -->
    <div class="modal-overlay" id="admin-cp-modal">
        <div class="modal" style="max-width:460px;">

            <!-- Step 1: Enter current password -->
            <div id="adminCpStep1">
                <div class="modal-header">
                    <span class="modal-title">Change Password</span>
                    <button class="modal-close" id="admin-cp-close-1">&times;</button>
                </div>
                <div style="padding:1.25rem 1.5rem 0;">
                    <p style="color:#aaa;font-size:0.875rem;margin:0 0 1rem;">Enter your current password to continue.</p>
                    <div class="form-group" style="position:relative;">
                        <label>CURRENT PASSWORD</label>
                        <input type="password" id="adminCpCurrentPassword" placeholder="Enter current password" style="padding-right:2.5rem;">
                        <button type="button" onclick="adminToggleCpVisibility('adminCpCurrentPassword', this)"
                            style="position:absolute;right:10px;bottom:10px;background:none;border:none;color:#aaa;cursor:pointer;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p class="modal-error" id="adminCpStep1Error" style="display:none;color:#e74c3c;font-size:0.8rem;margin-top:-0.5rem;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="admin-cp-cancel-1">Cancel</button>
                    <button type="button" class="btn-save" onclick="adminSendChangePasswordOtp()">Continue</button>
                </div>
            </div>

            <!-- Step 2: OTP verification -->
            <div id="adminCpStep2" style="display:none;">
                <div class="modal-header">
                    <span class="modal-title">Enter Verification Code</span>
                    <button class="modal-close" id="admin-cp-close-2">&times;</button>
                </div>
                <div style="padding:1.25rem 1.5rem 0;">
                    <p style="color:#aaa;font-size:0.875rem;margin:0 0 1.25rem;">A 6-digit code was sent to your registered email address.</p>
                    <div style="display:flex;gap:8px;justify-content:center;margin-bottom:1rem;">
                        <input type="text" class="adminCpCodeInput" maxlength="1" inputmode="numeric"
                            style="width:44px;height:52px;text-align:center;font-size:1.3rem;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:rgba(255,255,255,0.05);color:#fff;">
                        <input type="text" class="adminCpCodeInput" maxlength="1" inputmode="numeric"
                            style="width:44px;height:52px;text-align:center;font-size:1.3rem;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:rgba(255,255,255,0.05);color:#fff;">
                        <input type="text" class="adminCpCodeInput" maxlength="1" inputmode="numeric"
                            style="width:44px;height:52px;text-align:center;font-size:1.3rem;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:rgba(255,255,255,0.05);color:#fff;">
                        <input type="text" class="adminCpCodeInput" maxlength="1" inputmode="numeric"
                            style="width:44px;height:52px;text-align:center;font-size:1.3rem;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:rgba(255,255,255,0.05);color:#fff;">
                        <input type="text" class="adminCpCodeInput" maxlength="1" inputmode="numeric"
                            style="width:44px;height:52px;text-align:center;font-size:1.3rem;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:rgba(255,255,255,0.05);color:#fff;">
                        <input type="text" class="adminCpCodeInput" maxlength="1" inputmode="numeric"
                            style="width:44px;height:52px;text-align:center;font-size:1.3rem;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:rgba(255,255,255,0.05);color:#fff;">
                    </div>
                    <p class="modal-error" id="adminCpOtpError" style="display:none;color:#e74c3c;font-size:0.8rem;text-align:center;"></p>
                    <p style="text-align:center;margin-top:0.5rem;">
                        <button type="button" id="adminCpResendBtn" onclick="adminResendChangePasswordOtp()"
                            style="background:none;border:none;color:#d4af37;cursor:pointer;font-size:0.85rem;text-decoration:underline;">
                            Resend Code
                        </button>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="admin-cp-cancel-2">Back</button>
                    <button type="button" class="btn-save" onclick="adminVerifyCpOtp()">Verify</button>
                </div>
            </div>

            <!-- Step 3: Set new password -->
            <div id="adminCpStep3" style="display:none;">
                <div class="modal-header">
                    <span class="modal-title">Set New Password</span>
                    <button class="modal-close" id="admin-cp-close-3">&times;</button>
                </div>
                <div style="padding:1.25rem 1.5rem 0;">
                    <div class="form-group" style="position:relative;">
                        <label>NEW PASSWORD</label>
                        <input type="password" id="adminCpNewPassword" placeholder="Enter new password" style="padding-right:2.5rem;">
                        <button type="button" onclick="adminToggleCpVisibility('adminCpNewPassword', this)"
                            style="position:absolute;right:10px;bottom:10px;background:none;border:none;color:#aaa;cursor:pointer;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <!-- Strength bar -->
                    <div style="margin:-0.5rem 0 0.75rem;">
                        <div style="height:4px;background:rgba(255,255,255,0.1);border-radius:2px;overflow:hidden;">
                            <div id="adminCpStrengthBar" style="height:100%;width:0;transition:width 0.3s,background 0.3s;border-radius:2px;"></div>
                        </div>
                        <span id="adminCpStrengthText" style="font-size:0.75rem;color:#aaa;"></span>
                    </div>
                    <!-- Requirements -->
                    <ul style="list-style:none;padding:0;margin:0 0 0.75rem;font-size:0.78rem;color:#aaa;display:flex;flex-wrap:wrap;gap:4px 16px;">
                        <li id="admin-cp-req-length">✗ At least 8 characters</li>
                        <li id="admin-cp-req-upper">✗ Uppercase letter</li>
                        <li id="admin-cp-req-lower">✗ Lowercase letter</li>
                        <li id="admin-cp-req-number">✗ Number</li>
                    </ul>
                    <div class="form-group" style="position:relative;">
                        <label>CONFIRM NEW PASSWORD</label>
                        <input type="password" id="adminCpConfirmPassword" placeholder="Confirm new password" style="padding-right:2.5rem;">
                        <button type="button" onclick="adminToggleCpVisibility('adminCpConfirmPassword', this)"
                            style="position:absolute;right:10px;bottom:10px;background:none;border:none;color:#aaa;cursor:pointer;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p id="adminCpMatchText" style="font-size:0.8rem;margin-top:-0.5rem;margin-bottom:0.5rem;"></p>
                    <p class="modal-error" id="adminCpStep3Error" style="display:none;color:#e74c3c;font-size:0.8rem;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="admin-cp-cancel-3">Cancel</button>
                    <button type="button" class="btn-save" onclick="adminSubmitNewPassword()">Save Password</button>
                </div>
            </div>

            <!-- Step 4: Success -->
            <div id="adminCpStep4" style="display:none;text-align:center;padding:2.5rem 1.5rem;">
                <div style="font-size:3rem;margin-bottom:1rem;">✅</div>
                <div style="font-size:1.1rem;font-weight:600;color:#d4af37;margin-bottom:0.5rem;">Password Changed!</div>
                <p style="color:#aaa;font-size:0.875rem;margin-bottom:1.5rem;">Your admin password has been updated successfully.</p>
                <button type="button" class="btn-save" onclick="closeAdminCpModal()">Done</button>
            </div>

        </div>
    </div>

    <script src="../../assets/js/AdminProfile.js" defer></script>
    <script src="../../assets/js/AdminPanel.js" defer></script>
    <script src="../../assets/js/script.js" defer></script>
</body>
</html>