<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="overlay" id="sidebar-overlay"></div>
<div class="sidebar" id="admin-sidebar">
    <div class="sidebar-header">
        <span>ADMIN PANEL</span>
        <button type="button" class="close-icon" id="close-btn">&times;</button>
    </div>
    <nav class="sidebar-nav">
        <a href="adminSide.php" class="menu-opt <?= $currentPage === 'adminSide.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="productManagement.php" class="menu-opt <?= $currentPage === 'productManagement.php' ? 'active' : '' ?>">Product Management</a>
        <a href="orderManagement.php" class="menu-opt <?= $currentPage === 'orderManagement.php' ? 'active' : '' ?>">Order Management</a>
        <a href="customerList.php" class="menu-opt <?= $currentPage === 'customerList.php' ? 'active' : '' ?>">Customer List</a>
        <a href="salesReport.php" class="menu-opt <?= $currentPage === 'salesReport.php' ? 'active' : '' ?>">Sales Report</a>
        <a href="adminProfile.php" class="menu-opt <?= $currentPage === 'adminProfile.php' ? 'active' : '' ?>">Profile</a>    
    </nav>
</div>