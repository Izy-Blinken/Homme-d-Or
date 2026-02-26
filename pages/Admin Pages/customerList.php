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
            <a href="orderManagement.php" class="menu-opt">Order Management</a>
            <a href="customerList.php" class="menu-opt active">Customer List</a>
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
            <h2 class="page-title">Customer List</h2>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-group">
                    <label>SORT BY:</label>
                    <select id="sort-filter">
                        <option value="">Default</option>
                        <option value="name-asc">Name: A–Z</option>
                        <option value="name-desc">Name: Z–A</option>
                        <option value="orders-desc">Most Orders</option>
                        <option value="spent-desc">Highest Spent</option>
                        <option value="joined-desc">Newest Joined</option>
                    </select>
                </div>
                <div class="filter-group search-group">
                    <label>SEARCH:</label>
                    <input type="text" placeholder="Search customers..." id="search-input">
                </div>
                <button class="reset-btn" onclick="resetFilters()">Reset Filters</button>
            </div>

            <!-- Customer Table -->
            <section class="table-container">
                <div class="responsive-table">
                    <table class="customer-table" style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th>CUSTOMER NAME</th>
                                <th>EMAIL</th>
                                <th>PHONE</th>
                                <th>TOTAL ORDERS</th>
                                <th>TOTAL SPENT</th>
                                <th>JOIN DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Smith</td>
                                <td>john@example.com</td>
                                <td>+1 234 567 8900</td>
                                <td>12</td>
                                <td>$1234.50</td>
                                <td>2025-06-15</td>
                            </tr>
                            <tr>
                                <td>Sarah Johnson</td>
                                <td>sarah@example.com</td>
                                <td>+1 234 567 8901</td>
                                <td>8</td>
                                <td>$892.00</td>
                                <td>2025-08-22</td>
                            </tr>
                            <tr>
                                <td>Mike Brown</td>
                                <td>mike@example.com</td>
                                <td>+1 234 567 8902</td>
                                <td>15</td>
                                <td>$2145.75</td>
                                <td>2025-04-10</td>
                            </tr>
                            <tr>
                                <td>Emily Davis</td>
                                <td>emily@example.com</td>
                                <td>+1 234 567 8903</td>
                                <td>6</td>
                                <td>$645.30</td>
                                <td>2025-09-30</td>
                            </tr>
                            <tr>
                                <td>Robert Wilson</td>
                                <td>robert@example.com</td>
                                <td>+1 234 567 8904</td>
                                <td>20</td>
                                <td>$3420.00</td>
                                <td>2025-01-20</td>
                            </tr>
                            <tr>
                                <td>Anna Lee</td>
                                <td>anna@example.com</td>
                                <td>+1 234 567 8905</td>
                                <td>4</td>
                                <td>$310.00</td>
                                <td>2025-11-05</td>
                            </tr>
                            <tr>
                                <td>Carlos Reyes</td>
                                <td>carlos@example.com</td>
                                <td>+1 234 567 8906</td>
                                <td>9</td>
                                <td>$975.50</td>
                                <td>2025-07-18</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="../../assets/js/AdminPanel.js" defer></script>
    
</body>
</html>