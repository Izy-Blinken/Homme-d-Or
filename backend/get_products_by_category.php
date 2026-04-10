<?php
// Returns products grouped by category, with their primary image + total bought count
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
            pi.image_url,
            COALESCE(ob.total_bought, 0) AS total_bought
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi
            ON pi.product_id = p.product_id AND pi.is_primary = 1
        LEFT JOIN (
            SELECT
                oi.product_id,
                SUM(oi.quantity) AS total_bought
            FROM order_items oi
            INNER JOIN orders o ON o.order_id = oi.order_id
            WHERE o.order_status IN ('placed','processing','shipped','delivered','completed')
            GROUP BY oi.product_id
        ) ob ON ob.product_id = p.product_id
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

// Renders a single product card
function renderProductCard($product, $imgBasePath = '../assets/images/products/', $wishlistedIds = []) {
    $id       = (int)$product['product_id'];
    $nameRaw  = $product['product_name'] ?? '';
    $name     = htmlspecialchars($nameRaw);
    $priceVal = (float)$product['price'];
    $price    = number_format($priceVal, 2);
    $status   = $product['product_status'];
    $imgFile  = $product['image_url'];
    $imgSrc   = $imgFile
                ? $imgBasePath . htmlspecialchars($imgFile)
                : '../assets/images/brand_images/nocturne.png';

    $isSoldOut     = ($status === 'out-of-stock');
    $isWishlisted  = in_array($id, $wishlistedIds);
    $discountedVal = isset($product['discounted_price']) && $product['discounted_price'] !== null
        ? (float)$product['discounted_price']
        : null;

    // For sorting
    $sortPrice  = $discountedVal !== null && $discountedVal > 0 ? $discountedVal : $priceVal;
    $sortName   = mb_strtolower(trim($nameRaw), 'UTF-8');
    $totalBought = isset($product['total_bought']) ? (int)$product['total_bought'] : 0;

    ob_start();
    ?>
    <div class="product-card fade-in"
         data-product-id="<?= $id ?>"
         data-name="<?= htmlspecialchars($sortName, ENT_QUOTES, 'UTF-8') ?>"
         data-price="<?= htmlspecialchars((string)$sortPrice, ENT_QUOTES, 'UTF-8') ?>"
         data-bought="<?= $totalBought ?>">
        <div class="shop-product-image">
            <img src="<?= $imgSrc ?>" alt="<?= $name ?>">

            <?php if (!$isSoldOut): ?>
                <button
                    class="wishlist-btn <?= $isWishlisted ? 'wishlisted' : '' ?>"
                    type="button"
                    aria-label="Toggle wishlist"
                    style="color: <?= $isWishlisted ? '#c9a961' : '#fff' ?>">
                    <i class="<?= $isWishlisted ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                </button>
            <?php endif; ?>

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
            <button class="add-to-cart-btn" onclick="addToCart(<?= $id ?>, this)">
                ADD TO CART
            </button>
        <?php endif; ?>

        <div class="shop-product-info">
            <h3 class="shop-product-title"><?= $name ?></h3>
            <?php if ($discountedVal !== null && $discountedVal > 0): ?>
                <p class="shop-product-price">
                    <span style="text-decoration:line-through; opacity:0.5;">₱<?= $price ?></span>
                    &nbsp;₱<?= number_format($discountedVal, 2) ?>
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