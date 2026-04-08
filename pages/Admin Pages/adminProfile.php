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

//Store details
$store = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM store_settings WHERE id = 1"
));

$sadmin = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT email FROM superadmins WHERE superadmin_id = $superadmin_id"
));

//Sale performance
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

//Financial Summary
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed'"))['val'];

$revenueThisMonth = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders
     WHERE order_status = 'completed'
       AND MONTH(created_at) = MONTH(CURRENT_DATE())
       AND YEAR(created_at) = YEAR(CURRENT_DATE())"))['val'];

$revenueLastMonth = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders
     WHERE order_status = 'completed'
       AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
       AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)"))['val'];

$revenueGrowth = $revenueLastMonth > 0
    ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
    : 0;

$avgOrderValue = $completedOrders > 0
    ? round($totalRevenue / $completedOrders, 2)
    : 0;

// Customer Statistics 
$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM users"))['val'];

$returningCustomers = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM (SELECT user_id FROM orders WHERE user_id IS NOT NULL GROUP BY user_id HAVING COUNT(*) > 1) 
     AS ret"))['val'];

$newThisMonth = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users
     WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
       AND YEAR(created_at) = YEAR(CURRENT_DATE())"))['val'];

$newLastMonth = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM users
     WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
       AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)"))['val'];

$customerGrowth = $newLastMonth > 0
    ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100, 1)
    : 0;

// Customer Lifetime Value: total revenue / total customers
$clv = $totalCustomers > 0
    ? round($totalRevenue / $totalCustomers, 2)
    : 0;

$retentionRate = $totalCustomers > 0
    ? round(($returningCustomers / $totalCustomers) * 100, 1)
    : 0;

// New customers (per week)
$newThisWeek = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM users
     WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)"))['val'];

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

                    <button class="logout-btn" id="logout-btn">Logout</button>

                </div>

                <!-- Brand Info + Business Overview -->
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

                <!-- Sales Performance + Financial Summary -->
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
                            <span class="info-row-label">This Month:</span>
                            <span class="info-row-value">₱<?= number_format($revenueThisMonth, 2) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Last Month:</span>
                            <span class="info-row-value">₱<?= number_format($revenueLastMonth, 2) ?></span>
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

                <!-- Customer Statistics -->
                <div class="customer-stats-card">
                    <div class="info-card-title" style="border-bottom: 1px solid #f0f0f0; padding-bottom: 0.75rem; margin-bottom: 0;">Customer Statistics</div>

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
                            <div class="cstat-value"><?= number_format($newThisMonth) ?></div>
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

                <!-- Edit Profile Button -->
                <button class="edit-profile-btn" id="edit-profile-btn">Edit Profile Settings</button>

            </main>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal-overlay" id="edit-modal">
            <div class="modal" style="max-width:600px;">

                <div class="modal-header">
                    <span class="modal-title">Edit Profile Settings</span>
                    <button class="modal-close" id="edit-modal-close">&times;</button>
                </div>

                <form id="edit-profile-form" enctype="multipart/form-data">

                    <!-- Logo Upload -->
                    <div class="form-group">
                        <label>BRAND LOGO</label>
                        <input type="file" name="logo" id="logo-input" accept="image/*">
                        <?php if ($logoFile): ?>
                            <small style="color:#aaa;margin-top:4px;display:block;">
                                Current: <?= htmlspecialchars($logoFile) ?>. Leave blank to keep.
                            </small>
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
                            <input type="text" name="established_year" maxlength="4"
                                value="<?= htmlspecialchars($store['established_year'] ?? '') ?>">
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

                    <hr style="border-color:rgba(255,255,255,0.1);margin:1rem 0;">

                    <div class="form-row">
                        <div class="form-group">
                            <label>CURRENT PASSWORD</label>
                            <input type="password" name="current_password" placeholder="Required to change password">
                        </div>
                        <div class="form-group">
                            <label>NEW PASSWORD</label>
                            <input type="password" name="new_password" placeholder="Leave blank to keep current">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="edit-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>

                </form>

            </div>
        </div>

        <script src="../../assets/js/AdminProfile.js" defer></script>
        <script src="../../assets/js/AdminPanel.js" defer></script>
        <script src="../../assets/js/script.js" defer></script>
    </body>
</html>