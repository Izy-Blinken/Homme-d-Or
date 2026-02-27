<?php
    session_start();
    include '../../backend/db_connect.php';

    $success = $_SESSION['success'] ?? null;
    $error   = $_SESSION['error'] ?? null;
    unset($_SESSION['success']);
    unset($_SESSION['error']);

    $filter_category = $_GET['category'] ?? '';
    $filter_stock    = $_GET['stock'] ?? '';
    $filter_discount = $_GET['discount'] ?? '';
    $filter_sort     = $_GET['sort'] ?? '';
    $filter_search   = $_GET['search'] ?? '';

    $where = "1=1";
    if ($filter_category) {
        $where .= " AND p.category_id = '$filter_category'";
    }
    if ($filter_stock){
        $where .= " AND p.product_status = '$filter_stock'";
    }
    if ($filter_discount === 'discounted'){
        $where .= " AND p.discounted_price IS NOT NULL AND p.discounted_price > 0";
    }
    if ($filter_discount === 'nodiscount'){
        $where .= " AND (p.discounted_price IS NULL OR p.discounted_price = 0)";
    }
    if ($filter_search){
        $where .= " AND (p.product_name LIKE '%$filter_search%' OR p.sku LIKE '%$filter_search%')";
    }

    $sort_sql = "p.created_at DESC";
    if ($filter_sort === 'price-asc')  $sort_sql = "p.price ASC";
    if ($filter_sort === 'price-desc') $sort_sql = "p.price DESC";
    if ($filter_sort === 'name-asc')   $sort_sql = "p.product_name ASC";
    if ($filter_sort === 'stock-asc')  $sort_sql = "p.stock_qty ASC";

    $products = mysqli_query($conn,
    "SELECT p.*, c.category_name,
            (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = 1 LIMIT 1) AS primary_image
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.category_id
     WHERE $where
     ORDER BY $sort_sql");

    $categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Product Management</title>
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
        <link rel="stylesheet" href="../../assets/css/style.css">
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
                <h2 class="page-title">Product Management</h2>

                <!-- Category Tabs + Buttons -->
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

                    <div style="display:flex; gap:0.5rem;">
                        <button class="add-product-btn" id="manage-categories-btn" 
                                style="background:white; color:black; border:1px solid #ccc;">
                            Manage Categories
                        </button>
                        <button class="add-product-btn" id="add-product-btn">Add Product</button>
                    </div>
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
                                <option value="in-stock"     <?= $filter_stock === 'in-stock'     ? 'selected' : '' ?>>In Stock</option>
                                <option value="low-stock"    <?= $filter_stock === 'low-stock'    ? 'selected' : '' ?>>Low Stock</option>
                                <option value="out-of-stock" <?= $filter_stock === 'out-of-stock' ? 'selected' : '' ?>>Out of Stock</option>
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
                        <a href="productManagement.php" class="reset-btn" style="text-decoration: none; color:black;">Reset</a>
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
                                            <td>
                                                <div class="product-img-cell">
                                                    <?php if ($product['primary_image']): ?>
                                                        <img src="../../assets/images/products/<?= htmlspecialchars($product['primary_image']) ?>" 
                                                             alt="<?= htmlspecialchars($product['product_name']) ?>"
                                                             style="width:44px;height:44px;object-fit:cover;">
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                                            <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                                            <td>₱<?= number_format($product['price'], 2) ?></td>
                                            <td><?= $product['discounted_price'] ? '₱'.number_format($product['discounted_price'], 2) : '-' ?></td>
                                            <td><?= $product['stock_qty'] ?></td>
                                            <td>
                                                <span class="badge badge-<?= $product['product_status'] ?>">
                                                    <?= ucfirst(str_replace('-', ' ', $product['product_status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn-view-details prod-view-btn"
                                                    data-id="<?= $product['product_id'] ?>"
                                                    data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                    data-category="<?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>"
                                                    data-price="<?= $product['price'] ?>"
                                                    data-discounted-price="<?= $product['discounted_price'] ?>"
                                                    data-stock="<?= $product['stock_qty'] ?>"
                                                    data-sku="<?= $product['sku'] ?>"
                                                    data-desc="<?= htmlspecialchars($product['product_desc'] ?? '') ?>"
                                                    data-status="<?= $product['product_status'] ?>"
                                                    data-image="<?= htmlspecialchars($product['primary_image'] ?? '') ?>">View
                                                </button>
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
                                                <button class="btn-delete" 
                                                    data-id="<?= $product['product_id'] ?>"
                                                    data-name="<?= htmlspecialchars($product['product_name']) ?>">Delete
                                                </button>
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

        <!-- Category Mangmnt Modal -->
        <div class="modal-overlay" id="category-modal">
            <div class="modal" style="max-width:620px;">
                <div class="modal-header">
                    <span class="modal-title">Manage Categories</span>
                    <button class="modal-close" id="category-modal-close">&times;</button>
                </div>

                <!-- Add Category Form -->
                <form action="../../backend/products/add_category.php" method="POST" style="margin-bottom:1.5rem;">
                    <div style="display:flex; gap:0.75rem; align-items:flex-end;">
                        <div class="form-group" style="flex:1; margin-bottom:0;">
                            <label>NEW CATEGORY NAME</label>
                            <input type="text" name="category_name" placeholder="e.g. Perfume" required>
                        </div>
                        <button type="submit" class="btn-save" style="height:38px; white-space:nowrap;">Add Category</button>
                    </div>
                </form>

                <hr style="border:none; border-top:1px solid #eee; margin-bottom:1.25rem;">

                <!-- Category List -->
                <div style="max-height:340px; overflow-y:auto;">
                    <?php
                        $cat_list = mysqli_query($conn, "SELECT c.*, COUNT(p.product_id) AS product_count 
                                                          FROM categories c 
                                                          LEFT JOIN products p ON p.category_id = c.category_id 
                                                          GROUP BY c.category_id 
                                                          ORDER BY c.category_name ASC");
                        if (mysqli_num_rows($cat_list) > 0):
                            while ($c = mysqli_fetch_assoc($cat_list)):
                    ?>
                    <div class="category-list-item" id="cat-row-<?= $c['category_id'] ?>">

                        <!-- View Cat mode -->
                        <div class="cat-view" id="cat-view-<?= $c['category_id'] ?>">
                            <div style="display:flex; align-items:center; gap:0.5rem; flex:1; min-width:0;">
                                <span style="font-weight:600; font-size:0.92rem;">
                                    <?= htmlspecialchars($c['category_name']) ?>
                                </span>
                                <span style="font-size:0.78rem; color:#888;">
                                    (<?= $c['product_count'] ?> product<?= $c['product_count'] != 1 ? 's' : '' ?>)
                                </span>
                            </div>

                            <div style="display:flex; gap:0.5rem; flex-shrink:0;">
                                <button class="btn-edit-cat cat-edit-btn" style="padding:5px 12px; font-size:0.78rem;"
                                        data-id="<?= $c['category_id'] ?>"
                                        data-name="<?= htmlspecialchars($c['category_name']) ?>">
                                    Edit
                                </button>
                                <?php if ($c['product_count'] == 0): ?>
                                <button class="btn-delete-cat cat-delete-btn" style="padding:5px 12px; font-size:0.78rem;"
                                        data-id="<?= $c['category_id'] ?>"
                                        data-name="<?= htmlspecialchars($c['category_name']) ?>">
                                    Delete
                                </button>
                                <?php else: ?>
                                <button class="btn-delete-cat" style="padding:5px 12px; font-size:0.78rem; opacity:0.4; cursor:not-allowed;" 
                                        disabled title="Cannot delete category has products assigned">
                                    Delete
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Edit mode -->
                        <form class="cat-edit-form" id="cat-edit-form-<?= $c['category_id'] ?>" 
                              action="../../backend/products/edit_category.php" method="POST"
                              style="display:none; align-items:center; gap:0.75rem; width:100%;">

                            <input type="hidden" name="category_id" value="<?= $c['category_id'] ?>">
                            <input type="text" name="category_name" value="<?= htmlspecialchars($c['category_name']) ?>" required>
                            
                            <div style="display:flex; gap:0.4rem; flex-shrink:0;">
                                <button type="submit" class="btn-save" style="padding:5px 14px; font-size:0.78rem;">Save</button>
                                <button type="button" class="btn-cancel cat-edit-cancel" 
                                        style="padding:5px 10px; font-size:0.78rem;"
                                        data-id="<?= $c['category_id'] ?>">Cancel</button>
                            </div>

                        </form>
                    </div>
                    <?php endwhile; else: ?>
                    <p style="text-align:center; color:#888; padding:1rem 0;">No categories yet. Add one above.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- Delete Category Confirmation Modal -->
        <div class="modal-overlay" id="delete-category-modal">
            <div class="modal" style="max-width:400px;">
                <div class="modal-header">
                    <span class="modal-title">Delete Category</span>
                    <button class="modal-close" id="cat-delete-modal-close">&times;</button>
                </div>
                <p style="padding: 1rem 0;">
                    Are you sure you want to delete <strong id="cat-delete-name"></strong>? This cannot be undone.
                </p>
                <div class="modal-footer">
                    <button class="btn-cancel" id="cat-delete-modal-cancel">Cancel</button>
                    <a href="#" id="cat-delete-confirm-btn" class="btn-save" 
                       style="background:#c00; text-decoration:none;">Delete</a>
                </div>
            </div>
        </div>

        <!-- View Product Modal -->
        <div class="modal-overlay" id="view-product-modal">
            <div class="modal" style="max-width:640px;">
                <div class="modal-header">
                    <span class="modal-title">Product Details</span>
                    <button class="modal-close" id="view-modal-close">&times;</button>
                </div>

                <div id="view-loading" style="text-align:center; padding:2rem; display:none;">
                    <span style="color:#888; font-size:0.9rem;">Loading...</span>
                </div>

                <div id="view-content">
                    <!-- Image gallery -->
                    <div style="margin-bottom:1.25rem;">

                        <!-- Primary image (large) -->
                        <div style="text-align:center; margin-bottom:0.75rem;">
                            <img id="view-primary-image" src="" alt=""
                                style="width:160px; height:160px; object-fit:cover;
                                        border:1px solid #eee; display:none;">
                            <div id="view-no-image" style="width:160px; height:160px; background:#f5f5f5;
                                                            border:1px solid #eee; display:inline-flex;
                                                            align-items:center; justify-content:center;
                                                            color:#bbb; font-size:0.8rem; margin:0 auto;">
                                No Image
                            </div>
                        </div>

                        <!-- Thumbnails row -->
                        <div id="view-thumbnails"
                            style="display:flex; gap:0.5rem; justify-content:center; flex-wrap:wrap;"></div>
                    </div>

                    <!-- Info grid -->
                    <div class="detail-grid" style="margin-bottom:0.75rem;">
                        <div class="detail-item"><label>PRODUCT NAME</label><span id="view-name"></span></div>
                        <div class="detail-item"><label>CATEGORY</label><span id="view-category"></span></div>
                        <div class="detail-item"><label>PRICE</label><span id="view-price"></span></div>
                        <div class="detail-item"><label>DISCOUNTED PRICE</label><span id="view-discounted-price"></span></div>
                        <div class="detail-item"><label>STOCK QTY</label><span id="view-stock"></span></div>
                        <div class="detail-item"><label>SKU</label><span id="view-sku"></span></div>
                        <div class="detail-item"><label>STATUS</label><span id="view-status"></span></div>
                    </div>

                    <div class="detail-item" style="margin-bottom:1rem;">
                        <label>DESCRIPTION</label>
                        <span id="view-desc" style="display:block; margin-top:0.25rem; color:#555;"></span>
                    </div>

                    <!-- Variants table -->
                    <div id="view-variants-section" style="display:none;">
                        <label style="font-size:0.78rem; font-weight:bold; color:#555; display:block; margin-bottom:0.5rem;">
                            VARIANTS
                        </label>
                        <div style="overflow-x:auto;">
                            <table class="product-table" style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                                <thead>
                                    <tr>
                                        <th style="text-align:left; padding:6px 10px; background:#f5f5f5; border:1px solid #eee;">SIZE</th>
                                        <th style="text-align:left; padding:6px 10px; background:#f5f5f5; border:1px solid #eee;">PRICE</th>
                                        <th style="text-align:left; padding:6px 10px; background:#f5f5f5; border:1px solid #eee;">STOCK</th>
                                        <th style="text-align:left; padding:6px 10px; background:#f5f5f5; border:1px solid #eee;">SKU</th>
                                    </tr>
                                </thead>
                                <tbody id="view-variants-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="margin-top:1.25rem;">
                    <button class="btn-cancel" id="view-modal-done">Close</button>
                </div>
            </div>
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
                    <div class="form-group">
                        <label>DESCRIPTION</label>
                        <input type="text" name="product_desc" placeholder="Product description">
                    </div>
                    <div class="form-group">
                        <label>PRODUCT IMAGES <span style="font-weight:normal; color:#888; font-size:0.78rem;">(max 5 — click uploaded image to set as primary)</span></label>
                        <input type="file" name="product_images[]" id="add-product-images"
                            accept="image/*" multiple style="margin-bottom:0.5rem;">

                            <div id="add-image-preview"
                            style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.5rem;"></div>
                            <input type="hidden" name="primary_image_index" id="add-primary-image-index" value="0">
                    </div>
                    
                    <hr style="border:none; border-top:1px solid #eee; margin:1rem 0;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                        <label style="font-size:0.78rem; font-weight:bold; color:#555;">VARIANTS (optional)</label>
                        <button type="button" class="btn-save" id="add-variant-row-btn"
                                style="padding:4px 12px; font-size:0.78rem;">+ Add Variant</button>
                    </div>
                    <div id="variants-container"></div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="modal-cancel-btn">Cancel</button>
                        <button type="submit" class="btn-save">Save Product</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Edit Product Modal -->
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
                    <div class="form-group">
                        <label>DESCRIPTION</label>
                        <input type="text" name="product_desc" id="edit-product-desc">
                    </div>
                    
                    <div class="form-group">
                        <label>PRODUCT IMAGES <span style="font-weight:normal; color:#888; font-size:0.78rem;">(max 5 total — click to set primary, ✕ to remove)</span></label>

                        <div id="edit-existing-images"
                            style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.75rem;"></div>

                        <p id="edit-image-slots-hint" style="font-size:0.76rem; color:#888; margin:0 0 0.5rem;"></p>

                        <input type="file" name="product_images[]" id="edit-product-images"
                            accept="image/*" multiple style="margin-bottom:0.5rem;">
                            <div id="edit-new-image-preview"
                            style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.5rem;"></div>
                        <input type="hidden" name="primary_image_index" id="edit-primary-image-index" value="-1">
                    </div>
                    
                    <hr style="border:none; border-top:1px solid #eee; margin:1rem 0;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                        <label style="font-size:0.78rem; font-weight:bold; color:#555;">VARIANTS</label>
                        <button type="button" class="btn-save" id="edit-add-variant-row-btn"
                                style="padding:4px 12px; font-size:0.78rem;">+ Add Variant</button>
                    </div>
                    <div id="edit-existing-variants"></div>
                    <div id="edit-new-variants-container"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="edit-modal-cancel-btn">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Delete Product Confirmation Modal -->
        <div class="modal-overlay" id="delete-modal">
            <div class="modal">
                <div class="modal-header">
                    <span class="modal-title">Delete Product</span>
                    <button class="modal-close" id="delete-modal-close">&times;</button>
                </div>
                <p style="padding: 1rem 0;">
                    Are you sure you want to delete <strong id="delete-product-name"></strong>? This cannot be undone.
                </p>
                <div class="modal-footer">
                    <button class="btn-cancel" id="delete-modal-cancel">Cancel</button>
                    <a href="#" id="delete-confirm-btn" class="btn-save" 
                       style="background:#c00; text-decoration:none;">Delete</a>
                </div>
            </div>
        </div>


        <div id="generalToast" class="generalToast"></div>

        <script src="../../assets/js/AdminPanel.js"></script>
        <script src="../../assets/js/script.js"></script>
        <script>
            initLiveSearch('search-input', 'search-suggestions', '../../backend/productLiveSearch.php');
        </script>

        <?php if ($success): ?>
            <script>showGeneralToast("<?= htmlspecialchars($success) ?>", "success");</script>
        <?php endif; ?>
        <?php if ($error): ?>
            <script>showGeneralToast("<?= htmlspecialchars($error) ?>", "error");</script>
        <?php endif; ?>


    </body>
</html>