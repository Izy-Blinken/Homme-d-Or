<?php
    session_start();
    include '../../backend/db_connect.php';

    $success = $_GET['success'] ?? null;
    $error   = $_GET['error'] ?? null;

    $filter_category = $_GET['category'] ?? '';
    $filter_stock    = $_GET['stock'] ?? '';
    $filter_discount = $_GET['discount'] ?? '';
    $filter_sort     = $_GET['sort'] ?? '';
    $filter_search   = $_GET['search'] ?? '';

    $where = "1=1";
    if ($filter_category) $where .= " AND p.category_id = '$filter_category'";
    if ($filter_stock)    $where .= " AND p.product_status = '$filter_stock'";
    if ($filter_discount === 'discounted') $where .= " AND p.discounted_price IS NOT NULL AND p.discounted_price > 0";
    if ($filter_discount === 'nodiscount') $where .= " AND (p.discounted_price IS NULL OR p.discounted_price = 0)";
    if ($filter_search)   $where .= " AND (p.product_name LIKE '%$filter_search%' OR p.sku LIKE '%$filter_search%')";

    $sort_sql = "p.created_at DESC";
    if ($filter_sort === 'price-asc')  $sort_sql = "p.price ASC";
    if ($filter_sort === 'price-desc') $sort_sql = "p.price DESC";
    if ($filter_sort === 'name-asc')   $sort_sql = "p.product_name ASC";
    if ($filter_sort === 'stock-asc')  $sort_sql = "p.stock_qty ASC";

    $products = mysqli_query($conn,
    "SELECT p.*, c.category_name
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.category_id
     WHERE $where
     ORDER BY $sort_sql");

    $categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
    

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
            <a href="adminSide.php" class="menu-opt">Dashboard</a>
            <a href="productManagement.php" class="menu-opt active">Product Management</a>
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
            <h2 class="page-title">Product Management</h2>

            <!-- Category Tabs + Add Button -->
            <div class="category-tabs">
                <div class="tabs-group">
                    <a href="productManagement.php" 
                    class="tab-btn <?= !$filter_category ? 'active' : '' ?>">
                        All Products
                    </a>
                    <?php
                    mysqli_data_seek($categories, 0);
                    while ($cat = mysqli_fetch_assoc($categories)):
                    ?>
                    <a href="productManagement.php?category=<?= $cat['category_id'] ?>" 
                    class="tab-btn <?= $filter_category == $cat['category_id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </a>
                    <?php endwhile; ?>
                </div>

                <button class="add-product-btn" id="add-product-btn">Add Product</button>
            </div>

            <!-- Filter Bar -->
            <form method="GET" action="">
                <?php if ($filter_category): ?>
                    <input type="hidden" name="category" value="<?= $filter_category ?>">
                <?php endif; ?>

                <div class="filter-bar">
                    <div class="filter-group">
                        <label>STOCK STATUS:</label>
                        <select name="stock" id="stock-filter">
                            <option value="">All Stock Status</option>
                            <option value="in-stock"    <?= $filter_stock === 'in-stock'    ? 'selected' : '' ?>>In Stock</option>
                            <option value="low-stock"   <?= $filter_stock === 'low-stock'   ? 'selected' : '' ?>>Low Stock</option>
                            <option value="out-of-stock"<?= $filter_stock === 'out-of-stock'? 'selected' : '' ?>>Out of Stock</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>DISCOUNT STATUS:</label>
                        <select name="discount" id="discount-filter">
                            <option value="">All Products</option>
                            <option value="discounted" <?= $filter_discount === 'discounted' ? 'selected' : '' ?>>Discounted</option>
                            <option value="nodiscount" <?= $filter_discount === 'nodiscount' ? 'selected' : '' ?>>No Discount</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>SORT BY:</label>
                        <select name="sort" id="sort-filter">
                            <option value="">Default</option>
                            <option value="price-asc"  <?= $filter_sort === 'price-asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price-desc" <?= $filter_sort === 'price-desc' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="name-asc"   <?= $filter_sort === 'name-asc'   ? 'selected' : '' ?>>Name: A–Z</option>
                            <option value="stock-asc"  <?= $filter_sort === 'stock-asc'  ? 'selected' : '' ?>>Stock: Low to High</option>
                        </select>
                    </div>
                    <div class="filter-group search-group" style="position: relative;">
                        <label>SEARCH:</label>
                        <input type="text" name="search" placeholder="Search products..." 
                            id="search-input" value="<?= htmlspecialchars($filter_search) ?>">
                        <div id="search-suggestions" class="suggestions-box" style="display:none;"></div>
                    </div>
                    <button type="submit" class="tab-btn">Apply Filters</button>
                    <a href="productManagement.php" class="reset-btn">Reset</a>
                </div>
            </form>

            <!-- Product Table -->
            <section class="table-container">
                <div class="responsive-table">
                    <table class="product-table" style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th>IMAGE</th>
                                <th>PRODUCT NAME</th>
                                <th>CATEGORY</th>
                                <th>PRICE</th>
                                <th>DISCOUNT</th>
                                <th>STOCK</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($products) > 0): ?>
                                <?php while($product = mysqli_fetch_assoc($products)): ?>
                                    <tr>
                                        <td><div class="product-img-cell"></div></td>
                                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                                        <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                                        <td>₱<?= number_format($product['price'], 2) ?></td>
                                        <td><?= $product['discounted_price'] ? '₱'.number_format($product['discounted_price']) : '-' ?></td>
                                        <td><?= $product['stock_qty'] ?></td>
                                        
                                        <td>
                                            <span class="badge badge-<?=$product['product_status'] ?>">
                                                <?= ucfirst($product['product_status']) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <button class="btn-edit"
                                                data-id="<?= $product['product_id'] ?>"
                                                data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                data-category-id="<?= $product['category_id'] ?>"
                                                data-price="<?= $product['price'] ?>"
                                                data-discounted-price="<?= $product['discounted_price'] ?>"
                                                data-stock="<?= $product['stock_qty'] ?>"
                                                data-sku="<?= $product['sku'] ?>"
                                                data-desc="<?= htmlspecialchars($product['product_desc']) ?>"
                                                data-status="<?= $product['product_status'] ?>">Edit
                                            </button>

                                            <button class="btn-delete" data-id="<?= $product['product_id'] ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align:center;">No products yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

   <!-- Add Product Modal -->
    <div class="modal-overlay" id="product-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">Add Product</span>
                <button class="modal-close" id="modal-close-btn">&times;</button>
            </div>

            <form action="../../backend/products/add_product.php" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>PRODUCT NAME</label>
                        <input type="text" name="product_name" placeholder="e.g. Golden Night" required>
                    </div>
                    <div class="form-group">
                        <label>CATEGORY</label>
                        <select name="category_id">
                            <option value="">-- Select Category --</option>
                            <?php
                                mysqli_data_seek($categories, 0);
                                while ($cat = mysqli_fetch_assoc($categories)):
                            ?>
                            <option value="<?= $cat['category_id'] ?>">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>PRICE (₱)</label>
                        <input type="number" name="price" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label>DISCOUNTED PRICE (₱)</label>
                        <input type="number" name="discounted_price" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>STOCK QTY</label>
                        <input type="number" name="stock_qty" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label>SKU (Product Code)</label>
                        <input type="text" name="sku" placeholder="e.g. GN-001" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>DESCRIPTION</label>
                        <input type="text" name="product_desc" placeholder="Product description">
                    </div>
                    
                </div>
                <div class="form-group">
                    <label>PRODUCT IMAGE</label>
                    <input type="file" name="product_image" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="btn-save">Save Product</button>
                </div>
            </form>

        </div>
    </div>

    <!--Edit Product Modal-->
    <div class="modal-overlay" id="edit-product-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">Edit Product</span>
                <button class="modal-close" id="edit-modal-close-btn">&times;</button>
            </div>

            <form action="../../backend/products/edit_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="edit-product-id">
                <div class="form-row">
                    <div class="form-group">
                        <label>PRODUCT NAME</label>
                        <input type="text" name="product_name" id="edit-product-name" required>
                    </div>
                    <div class="form-group">
                        <label>CATEGORY</label>
                        <select name="category_id" id="edit-category-id">
                            <option value="">-- Select Category --</option>
                            <?php
                            mysqli_data_seek($categories, 0);
                            while ($cat = mysqli_fetch_assoc($categories)):
                            ?>
                            <option value="<?= $cat['category_id'] ?>">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>PRICE (₱)</label>
                        <input type="number" name="price" id="edit-price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>DISCOUNTED PRICE (₱)</label>
                        <input type="number" name="discounted_price" id="edit-discounted-price" step="0.01">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>STOCK QTY</label>
                        <input type="number" name="stock_qty" id="edit-stock-qty" required>
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" id="edit-sku" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>DESCRIPTION</label>
                        <input type="text" name="product_desc" id="edit-product-desc">
                    </div>
                    
                </div>
                <div class="form-group">
                    <label>PRODUCT IMAGE</label>
                    <input type="file" name="product_image" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="edit-modal-cancel-btn">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="generalToast"></div>

    
    <?php if ($success): ?>
        <script>
            showGeneralToast("<?= htmlspecialchars($success) ?>", "success");
        </script>
    <?php endif; ?>

    <?php if ($error): ?>
        <script>
            showGeneralToast("<?= htmlspecialchars($error) ?>", "error");
        </script>            
    <?php endif; ?>

    <script src="../../assets/js/AdminPanel.js" defer></script>
    <script src="../../assets/js/script.js"></script>
    <script>
        initLiveSearch('search-input', 'search-suggestions', '../../backend/search_suggestions.php');
    </script>

</body>
</html>