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
            <a href="adminSide.php" class="menu-opt">Dashboard</a>
            <a href="productManagement.php" class="menu-opt">Product Management</a>
            <a href="orderManagement.php" class="menu-opt active">Order Management</a>
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
            <h2 class="page-title">Order Management</h2>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-group">
                    <label>ORDER STATUS:</label>
                    <select id="status-filter">
                        <option value="">All Orders</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>DATE RANGE:</label>
                    <select id="date-filter">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <div class="filter-group search-group">
                    <label>SEARCH:</label>
                    <input type="text" placeholder="Search orders..." id="search-input">
                </div>
                <button class="reset-btn" onclick="resetFilters()">Reset Filters</button>
            </div>

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
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ORD-001</td>
                                <td>John Smith</td>
                                <td>2026-01-28</td>
                                <td>$129.99</td>
                                <td><span class="badge badge-pending">Pending</span></td>
                                <td><button class="btn-view-details view-btn"
                                    data-id="ORD-001" data-customer="John Smith"
                                    data-date="2026-01-28" data-total="$129.99"
                                    data-status="Pending" data-items="Classic T-Shirt x2"
                                    data-address="123 Main St, New York">View Details</button></td>
                            </tr>
                            <tr>
                                <td>ORD-002</td>
                                <td>Sarah Johnson</td>
                                <td>2026-01-27</td>
                                <td>$249.50</td>
                                <td><span class="badge badge-processing">Processing</span></td>
                                <td><button class="btn-view-details view-btn"
                                    data-id="ORD-002" data-customer="Sarah Johnson"
                                    data-date="2026-01-27" data-total="$249.50"
                                    data-status="Processing" data-items="Premium Jacket x1"
                                    data-address="456 Oak Ave, Los Angeles">View Details</button></td>
                            </tr>
                            <tr>
                                <td>ORD-003</td>
                                <td>Mike Brown</td>
                                <td>2026-01-26</td>
                                <td>$89.99</td>
                                <td><span class="badge badge-shipped">Shipped</span></td>
                                <td><button class="btn-view-details view-btn"
                                    data-id="ORD-003" data-customer="Mike Brown"
                                    data-date="2026-01-26" data-total="$89.99"
                                    data-status="Shipped" data-items="Summer Dress x1"
                                    data-address="789 Pine Rd, Chicago">View Details</button></td>
                            </tr>
                            <tr>
                                <td>ORD-004</td>
                                <td>Emily Davis</td>
                                <td>2026-01-25</td>
                                <td>$179.99</td>
                                <td><span class="badge badge-delivered">Delivered</span></td>
                                <td><button class="btn-view-details view-btn"
                                    data-id="ORD-004" data-customer="Emily Davis"
                                    data-date="2026-01-25" data-total="$179.99"
                                    data-status="Delivered" data-items="Slim Fit Jeans x2"
                                    data-address="321 Elm St, Houston">View Details</button></td>
                            </tr>
                            <tr>
                                <td>ORD-005</td>
                                <td>Robert Wilson</td>
                                <td>2026-01-24</td>
                                <td>$59.99</td>
                                <td><span class="badge badge-cancelled">Cancelled</span></td>
                                <td><button class="btn-view-details view-btn"
                                    data-id="ORD-005" data-customer="Robert Wilson"
                                    data-date="2026-01-24" data-total="$59.99"
                                    data-status="Cancelled" data-items="Daily Wear Shirt x1"
                                    data-address="654 Maple Dr, Phoenix">View Details</button></td>
                            </tr>
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
                <span class="modal-title" id="modal-order-id">Order Details</span>
                <button class="modal-close" id="modal-close-btn">&times;</button>
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
                    <label>STATUS</label>
                    <span id="modal-status"></span>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <label>ITEMS</label>
                    <span id="modal-items"></span>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <label>SHIPPING ADDRESS</label>
                    <span id="modal-address"></span>
                </div>
            </div>
            <div style="margin-top:0.5rem;">
                <label style="font-size:0.78rem;font-weight:bold;color:#555;display:block;margin-bottom:0.4rem;">UPDATE STATUS:</label>
                <select class="status-select" id="modal-status-select">
                    <option>Pending</option>
                    <option>Processing</option>
                    <option>Shipped</option>
                    <option>Delivered</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" id="modal-done-btn">Close</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/AdminPanel.js" defer></script>
    
</body>
</html>