<?php
// Returns products grouped by category, with their primary image
// Usage: include this file, then call getProductsByCategory($conn)

function getProductsByCategory($conn) {
    $sql = "
        SELECT
            p.product_id,
            p.product_name,
            p.price,
            p.discounted_price,
            p.product_status,
            c.category_id,
            c.category_name,
            pi.image_url
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi
            ON pi.product_id = p.product_id AND pi.is_primary = 1
        ORDER BY c.category_id ASC, p.created_at DESC
    ";

    $result = $conn->query($sql);
    $grouped = [];

    while ($row = $result->fetch_assoc()) {
        $cat = $row['category_name'] ?? 'Uncategorized';
        $grouped[$cat][] = $row;
    }

    return $grouped;
}

// Renders a single product card — same structure as your existing hardcoded cards
<<<<<<< HEAD
function renderProductCard($product, $imgBasePath = '../assets/images/products/', $wishlistedIds = []) {
    $id       = $product['product_id'];
    $name     = htmlspecialchars($product['product_name']);
    $price    = number_format($product['price'], 2);
    $status   = $product['product_status'];
    $imgFile  = $product['image_url'];
    $imgSrc   = $imgFile 
                ? $imgBasePath . htmlspecialchars($imgFile) 
=======
function renderProductCard($product, $imgBasePath = '../assets/images/products/') {
    $id = $product['product_id'];
    $name = htmlspecialchars($product['product_name']);
    $price = number_format($product['price'], 2);
    $status = $product['product_status'];
    $imgFile = $product['image_url'];
    $imgSrc = $imgFile
                ? $imgBasePath . htmlspecialchars($imgFile)
>>>>>>> 5aabf5346cecce917521bddf278b287c4645bf8e
                : '../assets/images/brand_images/nocturne.png'; // fallback image

    $isSoldOut = ($status === 'out-of-stock');

    ob_start();
    ?>
    <div class="product-card fade-in">
        <div class="shop-product-image">
            <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
            <?php if ($isSoldOut): ?>
                <div class="sold-out-label">SOLD OUT</div>
            <?php else: ?>
                <button class="quick-view-btn"
                    onclick="window.location.href='productDetails.php?id=<?= $id ?>'">
                    Quick View
                </button>
            <?php endif; ?>
        </div>

        <?php if ($isSoldOut): ?>
            <button class="add-to-cart-btn" disabled>ADD TO CART</button>
        <?php else: ?>
<<<<<<< HEAD
            <button class="add-to-cart-btn" 
                onclick="addToCart(<?= $id ?>, this)">
=======
            <button class="add-to-cart-btn"
                onclick="window.location.href='cart.php?product_id=<?= $id ?>'">
>>>>>>> 5aabf5346cecce917521bddf278b287c4645bf8e
                ADD TO CART
            </button>
        <?php endif; ?>

        <div class="shop-product-info">
            <h3 class="shop-product-title"><?= $name ?></h3>
            <?php if ($product['discounted_price']): ?>
                <p class="shop-product-price">
                    <span style="text-decoration:line-through; opacity:0.5;">
                        ₱<?= $price ?>
                    </span>
                    &nbsp;₱<?= number_format($product['discounted_price'], 2) ?>
                </p>
            <?php else: ?>
                <p class="shop-product-price">₱<?= $price ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function getWishlistedIds($conn) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user_id'])) return [];

    $user_id = intval($_SESSION['user_id']);
    $result = $conn->query("SELECT product_id FROM wishlist WHERE user_id = $user_id");

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['product_id'];
    }
    return $ids;
}
?>