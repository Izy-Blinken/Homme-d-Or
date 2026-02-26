<?php
    session_start();
    include '../../backend/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    
</head>
<body>

    <div class="overlay" id="sidebar-overlay"></div>
    <div class="sidebar" id="admin-sidebar">
        <div class="sidebar-header">
            <span>ADMIN PANEL</span>
            <button type="button" class="close-icon" id="close-btn">&times;</button>
        </div>
        <nav class="sidebar-nav">
            <a href="adminSide.php" class="menu-opt active">Dashboard</a>
            <a href="productManagement.php" class="menu-opt">Product Management</a>
            <a href="orderManagement.php" class="menu-opt">Order Management</a>
            <a href="customerList.php" class="menu-opt">Customer List</a>
            <a href="salesReport.php" class="menu-opt">Sales Report</a>
            <a href="adminProfile.php" class="menu-opt">Profile</a>
        </nav>
    </div>

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
            <h2 class="page-title">Dashboard</h2>

            <?php
                //Total revenue
                $TotalRevenue = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE order_status = 'Completed'")
                    )['total'];

                //Total orders
                $TotalOrders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM orders"))['total'];

                //Total products
                $TotalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];

                //Total Customers
                $TotalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];


                //Compare this month and last month (yung mga anik2 sa baba na % etc)

                //REVENUE COMPARISON
                $RevThisMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'Completed' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                )['val'];

                $RevLastMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS val FROM orders WHERE order_status = 'Completed' AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                )['val'];

                //ORDERS COMPARISON
                $OrdersThisMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                )['val'];

                $OrdersLastMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                )['val'];

                //PRODUCTS COMPARISON
                $ProductsThisMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                )['val'];

                $ProductsLastMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                )['val'];

                //CUSTOMERS COMPARISON
                $CustomersThisMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COUNT(*) AS val FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")
                )['val'];

                $CustomersLastMonth = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT COUNT(*) AS val FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")
                )['val'];

                //PERCENTAGE COMPUTATION
                $RevPercent = $RevLastMonth > 0 ? round((($RevThisMonth - $RevLastMonth) / $RevLastMonth) * 100, 2) : 0;
                $OrdersPercent = $OrdersLastMonth > 0 ? round((($OrdersThisMonth - $OrdersLastMonth) / $OrdersLastMonth) * 100, 2) : 0;
                $ProductsPercent = $ProductsLastMonth > 0 ? round((($ProductsThisMonth - $ProductsLastMonth) / $ProductsLastMonth) * 100, 2) : 0;
                $CustomersPercent = $CustomersLastMonth > 0 ? round((($CustomersThisMonth - $CustomersLastMonth) / $CustomersLastMonth) * 100, 2) : 0;

                //Recent Orders
                $RecentOrders = mysqli_query($conn, "SELECT order_id, fname, lname, total_amount, order_status, created_at
                    FROM orders 
                    ORDER BY created_at DESC
                    LIMIT 5");
            ?>

            <section class="stats-grid">
                <div class="stat-card">
                    <small class="stat-label">TOTAL REVENUE</small>
                    <h3 class="stat-value">₱<?= number_format($TotalRevenue, 2) ?></h3>
                    
                    <?php if($RevPercent !== NULL): ?>
                        <small class="stat-change <?= $RevPercent >= 0 ? 'positive' : 'negative' ?>">
                            <?= $RevPercent >= 0 ? '+' : '' ?><?= $RevPercent ?>% from last month
                        </small>
                    <?php else: ?>
                        <small class="stat-change">No data for comparison</small>
                    <?php endif; ?>

                </div>
                
                <div class="stat-card">
                    <small class="stat-label">TOTAL ORDERS</small>
                    <h3 class="stat-value"><?= number_format($TotalOrders) ?></h3>
                    <?php if($OrdersPercent !== NULL): ?>
                        <small class="stat-change <?= $OrdersPercent >= 0 ? 'positive' : 'negative' ?>">
                            <?= $OrdersPercent >= 0 ? '+' : '' ?><?= $OrdersPercent ?>% from last month
                        </small>
                    <?php else: ?>
                        <small class="stat-change">No data for comparison</small>
                    <?php endif; ?>
                </div>

                <div class="stat-card">
                    <small class="stat-label">TOTAL PRODUCTS</small>
                    <h3 class="stat-value"><?= number_format($TotalProducts) ?></h3>
                    <?php if($ProductsPercent !== NULL): ?>
                        <small class="stat-change <?= $ProductsPercent >= 0 ? 'positive' : 'negative' ?>">
                            <?= $ProductsPercent >= 0 ? '+' : '' ?><?= $ProductsPercent ?>% from last month
                        </small>
                    <?php else: ?>
                        <small class="stat-change">No data for comparison</small>
                    <?php endif; ?>
                </div>
                
                <div class="stat-card">
                    <small class="stat-label">TOTAL CUSTOMERS</small>
                    <h3 class="stat-value"><?= number_format($TotalCustomers) ?></h3>
                    <?php if($CustomersPercent !== NULL): ?>
                        <small class="stat-change <?= $CustomersPercent >= 0 ? 'positive' : 'negative' ?>">
                            <?= $CustomersPercent >= 0 ? '+' : '' ?><?= $CustomersPercent ?>% from last month
                        </small>
                    <?php else: ?>
                        <small class="stat-change">No data for comparison</small>
                    <?php endif; ?>
                </div>
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
    
</body>
</html>