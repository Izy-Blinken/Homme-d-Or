<?php 
    session_start();
    include '../../backend/db_connect.php';
    include '../../backend/orders/auto_advance.php'; //trigger lng kapag naka-open file n to

    $success = $_SESSION['success'] ?? null;
    $error   = $_SESSION['error'] ?? null;
    unset($_SESSION['success']);
    unset($_SESSION['error']);

    $filter_status    = $_GET['status']    ?? '';
    $filter_date_from = $_GET['date_from'] ?? '';
    $filter_date_to   = $_GET['date_to']   ?? '';
    $filter_search    = $_GET['search']    ?? '';

    $where = "1=1";
    if ($filter_status){
        $where .= " AND o.order_status = '$filter_status'";
    }
    if ($filter_search){
        $safe_search = mysqli_real_escape_string($conn, $filter_search);
        $where .= " AND (o.fname LIKE '%$safe_search%' 
                    OR o.lname LIKE '%$safe_search%' 
                    OR CONCAT(o.fname, ' ', o.lname) LIKE '%$safe_search%'
                    OR o.order_id LIKE '%$safe_search%')";
    }
    if ($filter_date_from){
        $where .= " AND DATE(o.created_at) >= '$filter_date_from'";
    }
    if ($filter_date_to){
        $where .= " AND DATE(o.created_at) <= '$filter_date_to'";
    }

    $orders = mysqli_query($conn,
        "SELECT o.*, p.method AS payment_method, p.payment_status
         FROM orders o
         LEFT JOIN payments p ON o.order_id = p.order_id
         WHERE $where
         ORDER BY o.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    </head>
    <body>

        <?php include '../../components/adminSideBar.php'; ?>

        <div class="main-content">
            <header class="navbar">
                <div class="navbar-left">
                    <button class="hamburger" id="menu-btn">
                        <span></span><span></span><span></span>
                    </button>
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
                <h2 class="page-title">Order Management</h2>

                <!-- Filter Bar -->
                <form method="GET" action="">
                    <div class="filter-bar">
                        <div class="filter-group">
                            <label>ORDER STATUS:</label>
                            <select name="status" id="status-filter">
                                <option value="">All Orders</option>
                                <option value="pending"   <?= $filter_status === 'pending'   ? 'selected' : '' ?>>Pending</option>
                                <option value="paid"      <?= $filter_status === 'paid'      ? 'selected' : '' ?>>Paid</option>
                                <option value="shipped"   <?= $filter_status === 'shipped'   ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $filter_status === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="received"  <?= $filter_status === 'received'  ? 'selected' : '' ?>>Received</option>
                                <option value="completed" <?= $filter_status === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $filter_status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>FROM:</label>
                            <input type="date" name="date_from" value="<?= $filter_date_from ?>">
                        </div>
                        <div class="filter-group">
                            <label>TO:</label>
                            <input type="date" name="date_to" value="<?= $filter_date_to ?>">
                        </div>
                        <div class="filter-group search-group" style="position:relative;">
                            <label>SEARCH:</label>
                            <input type="text" name="search" placeholder="Search orders..." 
                                id="search-input" value="<?= htmlspecialchars($filter_search) ?>">
                            <div id="search-suggestions" class="suggestions-box" style="display:none;"></div>
                        </div>
                        <button type="submit" class="tab-btn">Apply Filters</button>
                        <a href="orderManagement.php" class="reset-btn" style="text-decoration: none; color: black;">Reset</a>
                    </div>
                </form>

                <!-- Order Table -->
                <section class="table-container">
                    <div class="responsive-table">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th>ORDER ID</th>
                                    <th>CUSTOMER</th>
                                    <th>DATE</th>
                                    <th>TOTAL</th>
                                    <th>PAYMENT</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($orders) > 0): ?>
                                    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                                    <tr>
                                        <td>#<?= $order['order_id'] ?></td>
                                        <td><?= htmlspecialchars($order['fname'] . ' ' . $order['lname']) ?></td>
                                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                        <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                                        <td><?= ucfirst(str_replace('_', ' ', $order['payment_method'] ?? 'N/A')) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $order['order_status'] ?>">
                                                <?= ucfirst($order['order_status']) ?>
                                            </span>
                                        </td>
                                        <td style="display:flex; gap:0.4rem; flex-wrap:wrap;">
                                            <!-- View Details -->
                                            <button class="btn-view-details view-btn"
                                                data-id="<?= $order['order_id'] ?>"
                                                data-customer="<?= htmlspecialchars($order['fname'] . ' ' . $order['lname']) ?>"
                                                data-date="<?= date('M d, Y', strtotime($order['created_at'])) ?>"
                                                data-total="₱<?= number_format($order['total_amount'], 2) ?>"
                                                data-status="<?= $order['order_status'] ?>"
                                                data-payment="<?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?>"
                                                data-address="<?= htmlspecialchars(($order['street'] ?? '') . ', ' . ($order['city'] ?? '') . ', ' . ($order['province'] ?? '')) ?>">
                                                View
                                            </button>

                                            <!-- Mark as Paid (pending only) -->
                                            <?php if ($order['order_status'] === 'pending'): ?>
                                            <form method="POST" action="../../backend/orders/update_status.php" style="display:inline;">
                                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit" class="btn-edit">Mark Paid</button>
                                            </form>
                                            <?php endif; ?>

                                            <!-- Cancel (pending only) -->
                                            <?php if ($order['order_status'] === 'pending'): ?>
                                            <form method="POST" action="../../backend/orders/update_status.php" style="display:inline;">
                                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn-delete">Cancel</button>
                                            </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center;">No orders found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>

        <!-- Order Detail Modal -->
        <div class="modal-overlay" id="order-modal">
            <div class="modal">
                <div class="modal-header">
                    <span class="modal-title">Order #<span id="modal-order-id"></span></span>
                    <button class="modal-close" id="order-modal-close">&times;</button>
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>CUSTOMER</label>
                        <span id="modal-customer"></span>
                    </div>
                    <div class="detail-item">
                        <label>DATE</label>
                        <span id="modal-date"></span>
                    </div>
                    <div class="detail-item">
                        <label>TOTAL</label>
                        <span id="modal-total"></span>
                    </div>
                    <div class="detail-item">
                        <label>PAYMENT METHOD</label>
                        <span id="modal-payment"></span>
                    </div>
                    <div class="detail-item">
                        <label>STATUS</label>
                        <span id="modal-status"></span>
                    </div>
                    <div class="detail-item" style="grid-column: span 2;">
                        <label>SHIPPING ADDRESS</label>
                        <span id="modal-address"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel" id="order-modal-done">Close</button>
                </div>
            </div>
        </div>

        <div id="generalToast" class="generalToast"></div>
        
        <script src="../../assets/js/AdminPanel.js"></script>
        <script src="../../assets/js/script.js"></script>
        <script>
            initLiveSearch('search-input', 'search-suggestions', '../../backend/ordersLiveSearch.php');
        </script>

        <?php if ($success): ?>
            <script>showGeneralToast("<?= htmlspecialchars($success) ?>", "success");</script>
        <?php endif; ?>
        <?php if ($error): ?>
            <script>showGeneralToast("<?= htmlspecialchars($error) ?>", "error");</script>
        <?php endif; ?>

        

    </body>
</html>