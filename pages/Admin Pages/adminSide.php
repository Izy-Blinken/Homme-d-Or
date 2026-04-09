<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/auth/auth_check.php';
checkAdminAccess($conn);
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
                <h2 class="page-title">Dashboard</h2>

                <?php
                    // Total revenue
                    $TotalRevenue = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE order_status = 'completed'")
                    )['total'];

                    // Total orders
                    $TotalOrders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM orders"))['total'];

                    // Total products
                    $TotalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];

                    // Total Customers
                    $TotalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];


                    // Compare this month and last month (yung mga anik2 sa baba na % etc)

                    // REVENUE COMPARISON
                    $RevThisMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                    )['val'];

                    $RevLastMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'completed' AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                    )['val'];

                    // ORDERS COMPARISON
                    $OrdersThisMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                    )['val'];

                    $OrdersLastMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                    )['val'];

                    // PRODUCTS COMPARISON
                    $ProductsThisMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                    )['val'];

                    $ProductsLastMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                    )['val'];

                    // CUSTOMERS COMPARISON
                    $CustomersThisMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COUNT(*) AS val FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                    )['val'];

                    $CustomersLastMonth = mysqli_fetch_assoc(
                        mysqli_query($conn, "SELECT COUNT(*) AS val FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                    )['val'];

                    // Show current month's performance (no comparison)

                    // Recent Orders
                    $RecentOrders = mysqli_query($conn, "SELECT order_id, fname, lname, total_amount, order_status, created_at
                        FROM orders
                        ORDER BY created_at DESC
                        LIMIT 5");
                ?>

                <section class="stats-grid">

                    <button onclick="window.location.href='salesReport.php?active_tab=revenue'">
                        <div class="stat-card">
                            <small class="stat-label">TOTAL REVENUE</small>
                            <h3 class="stat-value">₱<?= number_format($RevThisMonth, 2) ?></h3>
                            <small class="stat-change">This month</small>
                        </div>
                    </button>
                    
                    <button onclick="window.location.href='salesReport.php?active_tab=orders'">
                        
                    <div class="stat-card">
                            <small class="stat-label">TOTAL ORDERS</small>
                            <h3 class="stat-value"><?= number_format($OrdersThisMonth) ?></h3>
                            <small class="stat-change">This month</small>
                        </div>

                    </button>
                    
                    <button onclick="window.location.href='salesReport.php?active_tab=products'">
                        
                        <div class="stat-card">
                            <small class="stat-label">TOTAL PRODUCTS</small>
                            <h3 class="stat-value"><?= number_format($ProductsThisMonth) ?></h3>
                            <small class="stat-change">This month</small>
                        </div>

                    </button>
                    
                    
                    <button onclick="window.location.href='salesReport.php?active_tab=customers'">
                        
                        <div class="stat-card">
                            <small class="stat-label">TOTAL CUSTOMERS</small>
                            <h3 class="stat-value"><?= number_format($CustomersThisMonth) ?></h3>
                            <small class="stat-change">This month</small>
                        </div>

                    </button>

                </section>

                <section class="table-container">

                    <h3 class="table-title">Recent Activities</h3>
                    <div class="responsive-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>RECENT ORDERS</th>
                                    <th>DETAILS</th>
                                    <th>TIME</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if(mysqli_num_rows($RecentOrders) > 0): ?>
                                    <?php while($orders = mysqli_fetch_assoc($RecentOrders)): ?>

                                        <tr>
                                            <td>New Order</td>
                                            <td>Order #<?= $orders['order_id']?> Placed By <?=htmlspecialchars($orders['fname']. ' '.$orders['lname'] )?></td>
                                            <td><?= date('M d, Y', strtotime($orders['created_at'])) ?></td>
                                        </tr>

                                    <?php endwhile;?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align:center;">No recent orders.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>

        <script src="../../assets/js/AdminPanel.js" defer></script>
        <script src="../../assets/js/script.js" defer></script>
        
    </body>
</html>