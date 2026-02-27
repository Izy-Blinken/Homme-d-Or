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
            <h2 class="page-title">Profile</h2>

            <!-- Profile Header -->
            <div class="profile-header-card">
                <div class="profile-header-left">
                    <div class="profile-avatar">A</div>
                    <div>
                        <div class="profile-name">Admin User</div>
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
                        <span class="info-row-label">Brand Name</span>
                        <span class="info-row-value">Fashion Store</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Email</span>
                        <span class="info-row-value">admin@fashionstore.com</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Phone</span>
                        <span class="info-row-value">+1 234 567 8900</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Address</span>
                        <span class="info-row-value">123 Fashion Ave, NY</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Website</span>
                        <span class="info-row-value">fashionstore.com</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Established</span>
                        <span class="info-row-value">2020</span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">Business Overview</div>
                    <div class="info-row">
                        <span class="info-row-label">Total Products</span>
                        <span class="info-row-value">456</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Active Products</span>
                        <span class="info-row-value">432</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Out of Stock</span>
                        <span class="info-row-value">24</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Categories</span>
                        <span class="info-row-value">5</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Average Rating</span>
                        <span class="info-row-value">4.8 / 5.0</span>
                    </div>
                </div>
            </div>

            <!-- Sales Performance + Financial Summary -->
            <div class="two-col-grid">
                <div class="info-card">
                    <div class="info-card-title">Sales Performance</div>
                    <div class="info-row">
                        <span class="info-row-label">Total Orders</span>
                        <span class="info-row-value">1,234</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Completed Orders</span>
                        <span class="info-row-value">1,156</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Pending Orders</span>
                        <span class="info-row-value">45</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Cancelled Orders</span>
                        <span class="info-row-value">33</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Order Success Rate</span>
                        <span class="info-row-value">93.7%</span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">Financial Summary</div>
                    <div class="info-row">
                        <span class="info-row-label">Total Revenue</span>
                        <span class="info-row-value">$545,231</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">This Month</span>
                        <span class="info-row-value">$45,221</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Last Month</span>
                        <span class="info-row-value">$40,125</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Growth</span>
                        <span class="info-row-value positive">+12.7%</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Average Order Value</span>
                        <span class="info-row-value">$66.34</span>
                    </div>
                </div>
            </div>

            <!-- Customer Statistics -->
            <div class="customer-stats-card">
                <div class="info-card-title" style="border-bottom: 1px solid #f0f0f0; padding-bottom: 0.75rem; margin-bottom: 0;">Customer Statistics</div>
                <div class="customer-stats-grid">
                    <div>
                        <div class="cstat-label">TOTAL CUSTOMERS</div>
                        <div class="cstat-value">892</div>
                        <div class="cstat-change">+23 this week</div>
                    </div>
                    <div>
                        <div class="cstat-label">RETURNING CUSTOMERS</div>
                        <div class="cstat-value">456</div>
                        <div class="cstat-change">51.1% retention rate</div>
                    </div>
                    <div>
                        <div class="cstat-label">NEW THIS MONTH</div>
                        <div class="cstat-value">87</div>
                        <div class="cstat-change">+15.2% growth</div>
                    </div>
                    <div>
                        <div class="cstat-label">CUSTOMER LIFETIME VALUE</div>
                        <div class="cstat-value">$611</div>
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
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">Edit Profile Settings</span>
                <button class="modal-close" id="edit-modal-close">&times;</button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>BRAND NAME</label>
                    <input type="text" value="Fashion Store">
                </div>
                <div class="form-group">
                    <label>EMAIL</label>
                    <input type="email" value="admin@fashionstore.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>PHONE</label>
                    <input type="text" value="+1 234 567 8900">
                </div>
                <div class="form-group">
                    <label>WEBSITE</label>
                    <input type="text" value="fashionstore.com">
                </div>
            </div>
            <div class="form-group">
                <label>ADDRESS</label>
                <input type="text" value="123 Fashion Ave, NY">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>ADMIN NAME</label>
                    <input type="text" value="Admin User">
                </div>
                <div class="form-group">
                    <label>NEW PASSWORD</label>
                    <input type="password" placeholder="Leave blank to keep current">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="edit-modal-cancel">Cancel</button>
                <button class="btn-save">Save Changes</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/AdminPanel.js" defer></script>
</body>
</html>