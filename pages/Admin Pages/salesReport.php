<?php
session_start();
include(__DIR__ . '/../../backend/db_connect.php');
include '../../backend/reports/report_data.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
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
        <h2 class="page-title">Sales Report</h2>

        <!-- Tab buttons -->
        <div class="report-tabs">
            <button class="report-tab" data-tab="revenue">Revenue</button>
            <button class="report-tab" data-tab="sales">Sales</button>
            <button class="report-tab" data-tab="orders">Orders</button>
            <button class="report-tab" data-tab="products">Products</button>
            <button class="report-tab" data-tab="customers">Customers</button>
        </div>


        <!-- REVENUE TAB -->
        <div class="tab-panel" id="tab-revenue">
            <div class="report-panel-header">
                <span class="report-panel-title">Revenue Report</span>
                <button class="export-btn" onclick="exportPDF('revenue')">Export PDF</button>
            </div>
            <form method="GET" action="">
                <input type="hidden" name="active_tab" value="revenue">
                <div class="filter-bar">
                    <div class="filter-group"><label>FROM:</label><input type="date" name="rev_from" value="<?= $rev_from ?>"></div>
                    <div class="filter-group"><label>TO:</label><input type="date" name="rev_to" value="<?= $rev_to ?>"></div>
                    <button type="submit" class="reset-btn">Apply</button>
                    <a href="?active_tab=revenue" class="reset-btn" style="text-decoration:none; color:black;">Reset</a>
                </div>
            </form>
            <div class="stats-grid" id="revenue-stats">
                <div class="stat-card">
                    <small class="stat-label">TOTAL REVENUE</small>
                    <h3 class="stat-value">₱<?= number_format($rev_total, 2) ?></h3>
                </div>
                <div class="stat-card">
                    <small class="stat-label">AVG ORDER VALUE</small>
                    <h3 class="stat-value">₱<?= number_format($rev_avg, 2) ?></h3>
                </div>
                <div class="stat-card">
                    <small class="stat-label">REVENUE GROWTH</small>
                    <h3 class="stat-value <?= $rev_growth !== null && $rev_growth >= 0 ? 'positive' : 'negative' ?>">
                        <?= $rev_growth !== null ? ($rev_growth >= 0 ? '+' : '') . $rev_growth . '%' : 'N/A' ?>
                    </h3>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Revenue Over Time</div>
                <canvas id="chart-revenue" height="100"></canvas>
            </div>
            <div class="table-container" id="revenue-table">
                <h3 class="table-title">Revenue Details</h3>
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr><th>DATE</th><th>ORDER ID</th><th>CUSTOMER</th><th>AMOUNT</th><th>PAYMENT METHOD</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($rev_table) > 0): ?>
                                <?php while ($r = mysqli_fetch_assoc($rev_table)): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                                    <td>#<?= $r['order_id'] ?></td>
                                    <td><?= htmlspecialchars($r['fname'] . ' ' . $r['lname']) ?></td>
                                    <td>₱<?= number_format($r['total_amount'], 2) ?></td>
                                    <td><?= ucfirst(str_replace('_', ' ', $r['method'] ?? 'N/A')) ?></td>
                                    <td><span class="badge badge-<?= $r['order_status'] ?>"><?= ucfirst($r['order_status']) ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center;">No data for this period.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- SALES TAB -->
        <div class="tab-panel" id="tab-sales">
            <div class="report-panel-header">
                <span class="report-panel-title">Sales Report</span>
                <button class="export-btn" onclick="exportPDF('sales')">Export PDF</button>
            </div>
            <form method="GET" action="">
                <input type="hidden" name="active_tab" value="sales">
                <div class="filter-bar">
                    <div class="filter-group"><label>FROM:</label><input type="date" name="sal_from" value="<?= $sal_from ?>"></div>
                    <div class="filter-group"><label>TO:</label><input type="date" name="sal_to" value="<?= $sal_to ?>"></div>
                    <button type="submit" class="reset-btn">Apply</button>
                    <a href="?active_tab=sales" class="reset-btn" style="text-decoration:none; color:black;">Reset</a>
                </div>
            </form>
            <div class="stats-grid" id="sales-stats">
                <div class="stat-card"><small class="stat-label">TOTAL SALES</small><h3 class="stat-value"><?= number_format($sal_total) ?></h3></div>
                <div class="stat-card"><small class="stat-label">TOTAL UNITS SOLD</small><h3 class="stat-value"><?= number_format($sal_units) ?></h3></div>
                <div class="stat-card"><small class="stat-label">AVG SALE VALUE</small><h3 class="stat-value">₱<?= number_format($sal_avg, 2) ?></h3></div>
                <div class="stat-card"><small class="stat-label">TOP CATEGORY</small><h3 class="stat-value" style="font-size:1rem;"><?= htmlspecialchars($sal_top_cat['category_name'] ?? 'N/A') ?></h3></div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Sales Over Time</div>
                <canvas id="chart-sales" height="100"></canvas>
            </div>
            <div class="table-container" id="sales-table">
                <h3 class="table-title">Sales Details</h3>
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr><th>DATE</th><th>ORDER ID</th><th>CUSTOMER</th><th>ITEMS</th><th>TOTAL</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($sal_table) > 0): ?>
                                <?php while ($r = mysqli_fetch_assoc($sal_table)): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                                    <td>#<?= $r['order_id'] ?></td>
                                    <td><?= htmlspecialchars($r['fname'] . ' ' . $r['lname']) ?></td>
                                    <td><?= $r['total_items'] ?></td>
                                    <td>₱<?= number_format($r['total_amount'], 2) ?></td>
                                    <td><span class="badge badge-<?= $r['order_status'] ?>"><?= ucfirst($r['order_status']) ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center;">No data for this period.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- ORDERS TAB -->
        <div class="tab-panel" id="tab-orders">
            <div class="report-panel-header">
                <span class="report-panel-title">Orders Report</span>
                <button class="export-btn" onclick="exportPDF('orders')">Export PDF</button>
            </div>
            <form method="GET" action="">
                <input type="hidden" name="active_tab" value="orders">
                <div class="filter-bar">
                    <div class="filter-group"><label>FROM:</label><input type="date" name="ord_from" value="<?= $ord_from ?>"></div>
                    <div class="filter-group"><label>TO:</label><input type="date" name="ord_to" value="<?= $ord_to ?>"></div>
                    <button type="submit" class="reset-btn">Apply</button>
                    <a href="?active_tab=orders" class="reset-btn" style="text-decoration:none; color:black;">Reset</a>
                </div>
            </form>
            <div class="stats-grid" id="orders-stats">
                <div class="stat-card"><small class="stat-label">TOTAL ORDERS</small><h3 class="stat-value"><?= number_format($ord_total) ?></h3></div>
                <div class="stat-card"><small class="stat-label">COMPLETED</small><h3 class="stat-value positive"><?= number_format($ord_completed) ?></h3></div>
                <div class="stat-card"><small class="stat-label">PENDING</small><h3 class="stat-value" style="color:#e65100;"><?= number_format($ord_pending) ?></h3></div>
                <div class="stat-card"><small class="stat-label">CANCELLED</small><h3 class="stat-value negative"><?= number_format($ord_cancelled) ?></h3></div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Orders Over Time</div>
                <canvas id="chart-orders" height="100"></canvas>
            </div>
            <div class="table-container" id="orders-table">
                <h3 class="table-title">Order Details</h3>
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr><th>DATE</th><th>ORDER ID</th><th>CUSTOMER</th><th>ITEMS</th><th>TOTAL</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($ord_table) > 0): ?>
                                <?php while ($r = mysqli_fetch_assoc($ord_table)): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                                    <td>#<?= $r['order_id'] ?></td>
                                    <td><?= htmlspecialchars($r['fname'] . ' ' . $r['lname']) ?></td>
                                    <td><?= $r['item_count'] ?></td>
                                    <td>₱<?= number_format($r['total_amount'], 2) ?></td>
                                    <td><span class="badge badge-<?= $r['order_status'] ?>"><?= ucfirst($r['order_status']) ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center;">No data for this period.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- PRODUCTS TAB -->
        <div class="tab-panel" id="tab-products">
            <div class="report-panel-header">
                <span class="report-panel-title">Products Report</span>
                <button class="export-btn" onclick="exportPDF('products')">Export PDF</button>
            </div>
            <form method="GET" action="">
                <input type="hidden" name="active_tab" value="products">
                <div class="filter-bar">
                    <div class="filter-group"><label>FROM:</label><input type="date" name="prod_from" value="<?= $prod_from ?>"></div>
                    <div class="filter-group"><label>TO:</label><input type="date" name="prod_to" value="<?= $prod_to ?>"></div>
                    <button type="submit" class="reset-btn">Apply</button>
                    <a href="?active_tab=products" class="reset-btn" style="text-decoration:none; color:black;">Reset</a>
                </div>
            </form>
            <div class="stats-grid" id="products-stats">
                <div class="stat-card"><small class="stat-label">TOTAL PRODUCTS</small><h3 class="stat-value"><?= number_format($prod_total) ?></h3></div>
                <div class="stat-card"><small class="stat-label">BEST SELLER</small><h3 class="stat-value" style="font-size:1rem;"><?= htmlspecialchars($prod_best['product_name'] ?? 'N/A') ?></h3></div>
                <div class="stat-card"><small class="stat-label">LOW STOCK</small><h3 class="stat-value" style="color:#a07000;"><?= number_format($prod_low_stock) ?></h3></div>
                <div class="stat-card"><small class="stat-label">OUT OF STOCK</small><h3 class="stat-value negative"><?= number_format($prod_out) ?></h3></div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Top 10 Products by Units Sold</div>
                <canvas id="chart-products" height="100"></canvas>
            </div>
            <div class="table-container" id="products-table">
                <h3 class="table-title">Product Performance</h3>
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr><th>PRODUCT</th><th>CATEGORY</th><th>UNITS SOLD</th><th>REVENUE</th><th>CURRENT STOCK</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($prod_table) > 0): ?>
                                <?php while ($r = mysqli_fetch_assoc($prod_table)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['product_name']) ?></td>
                                    <td><?= htmlspecialchars($r['category_name'] ?? 'Uncategorized') ?></td>
                                    <td><?= number_format($r['units_sold']) ?></td>
                                    <td>₱<?= number_format($r['revenue'], 2) ?></td>
                                    <td><?= $r['stock_qty'] ?></td>
                                    <td><span class="badge badge-<?= $r['product_status'] ?>"><?= ucfirst(str_replace('-', ' ', $r['product_status'])) ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center;">No data for this period.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- CUSTOMERS TAB -->
        <div class="tab-panel" id="tab-customers">
            <div class="report-panel-header">
                <span class="report-panel-title">Customers Report</span>
                <button class="export-btn" onclick="exportPDF('customers')">Export PDF</button>
            </div>
            <form method="GET" action="">
                <input type="hidden" name="active_tab" value="customers">
                <div class="filter-bar">
                    <div class="filter-group"><label>FROM:</label><input type="date" name="cust_from" value="<?= $cust_from ?>"></div>
                    <div class="filter-group"><label>TO:</label><input type="date" name="cust_to" value="<?= $cust_to ?>"></div>
                    <button type="submit" class="reset-btn">Apply</button>
                    <a href="?active_tab=customers" class="reset-btn" style="text-decoration:none; color:black;">Reset</a>
                </div>
            </form>
            <div class="stats-grid" id="customers-stats">
                <div class="stat-card"><small class="stat-label">TOTAL CUSTOMERS</small><h3 class="stat-value"><?= number_format($cust_total) ?></h3></div>
                <div class="stat-card"><small class="stat-label">NEW THIS PERIOD</small><h3 class="stat-value positive"><?= number_format($cust_total) ?></h3></div>
                <div class="stat-card"><small class="stat-label">RETURNING</small><h3 class="stat-value"><?= number_format($cust_returning) ?></h3></div>
                <div class="stat-card"><small class="stat-label">AVG LIFETIME VALUE</small><h3 class="stat-value">₱<?= number_format($cust_ltv, 2) ?></h3></div>
            </div>
            <div class="chart-container">
                <div class="chart-title">New Customers Over Time</div>
                <canvas id="chart-customers" height="100"></canvas>
            </div>
            <div class="table-container" id="customers-table">
                <h3 class="table-title">Customer Details</h3>
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr><th>CUSTOMER</th><th>EMAIL</th><th>TOTAL ORDERS</th><th>TOTAL SPENT</th><th>LAST ORDER</th></tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($cust_table) > 0): ?>
                                <?php while ($r = mysqli_fetch_assoc($cust_table)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['fname'] . ' ' . $r['lname']) ?></td>
                                    <td><?= htmlspecialchars($r['email']) ?></td>
                                    <td><?= $r['total_orders'] ?></td>
                                    <td>₱<?= number_format($r['total_spent'], 2) ?></td>
                                    <td><?= $r['last_order'] ? date('M d, Y', strtotime($r['last_order'])) : '—' ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center;">No data for this period.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</div>



<div id="generalToast" class="generalToast"></div>

<script src="../../assets/js/AdminPanel.js"></script>
<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/salesReport.js"></script>
<script>

    // Pass chart data to JS
    makeChart('chart-revenue',   <?= json_encode($rev_labels) ?>,  <?= json_encode($rev_values) ?>,  'Revenue',       '#1a2433');
    makeChart('chart-sales',     <?= json_encode($sal_labels) ?>,  <?= json_encode($sal_values) ?>,  'Sales',         '#26a69a');
    makeChart('chart-orders',    <?= json_encode($ord_labels) ?>,  <?= json_encode($ord_values) ?>,  'Orders',        '#e65100');
    makeChart('chart-products',  <?= json_encode($prod_labels) ?>, <?= json_encode($prod_values) ?>, 'Units Sold',    '#7b1fa2');
    makeChart('chart-customers', <?= json_encode($cust_labels) ?>, <?= json_encode($cust_values) ?>, 'New Customers', '#2e7d32');
</script>

</body>
</html>