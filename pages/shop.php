<?php
session_start();
include '../backend/db_connect.php';
include '../backend/get_products_by_category.php';

$productsByCategory = getProductsByCategory($conn);

// Fetch wishlist IDs for the current user (empty array for guests/strangers)
$wishlistedIds = getWishlistedIds($conn);

$categoryConfig = [
    'New Arrivals' => ['id' => 'new-arrivals', 'layout' => 'shop-layout-left',  'side' => 'left',  'tab' => 'newArrival.php'],
    'Top Picks'    => ['id' => 'top-picks',    'layout' => 'shop-layout-right', 'side' => 'right', 'tab' => 'newArrival.php?tab=page2'],
    'Sale'         => ['id' => 'sale',          'layout' => 'shop-layout-left',  'side' => 'left',  'tab' => 'newArrival.php?tab=page3'],
    'Daily Wear'   => ['id' => 'daily-wear',    'layout' => 'shop-layout-right', 'side' => 'right', 'tab' => 'newArrival.php?tab=page4'],
    'Premium'      => ['id' => 'premium',       'layout' => 'shop-layout-left',  'side' => 'left',  'tab' => 'newArrival.php?tab=page5'],
];

// Maximum products shown per category in the shop preview
define('SHOP_PREVIEW_LIMIT', 6);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Shop | Homme d'Or</title>
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
        <link rel="stylesheet" href="../assets/css/CheckoutPageStyle.css">
        <link rel="stylesheet" href="../assets/css/OrderAgainStyle.css">
        <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
        <link rel="stylesheet" href="../assets/css/AboutUsPageStyle.css">
        <link rel="stylesheet" href="../assets/css/ProductDetailsStyle.css">
        <link rel="stylesheet" href="../assets/css/CartPageStyle.css">
        <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
        <link rel="stylesheet" href="../assets/css/ProfilePageStyle.css">
        <link rel="stylesheet" href="../assets/css/HomepageStyle.css">
       
    </head>
    <body>
    <?php include '../components/header.php'; ?>

    <section class="shop-welcome-section">
        <div class="shop-welcome-overlay"></div>
        <div class="shop-welcome-content">
            <h1><span class="greeting-text">Welcome</span></h1>
            <nav class="shop-category-nav">
                <?php foreach ($categoryConfig as $catName => $config): ?>
                    <?php if (isset($productsByCategory[$catName])): ?>
                        <a href="#<?= $config['id'] ?>" class="shop-category-link">
                            <?= htmlspecialchars($catName) ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>
    </section>

    <div class="shop-scroll-spacer"></div>

    <?php foreach ($categoryConfig as $catName => $config):
        if (empty($productsByCategory[$catName])) continue;

        // ── Limit to preview max ──────────────────────────────────────────
        $products    = array_slice($productsByCategory[$catName], 0, SHOP_PREVIEW_LIMIT);
        $totalInCat  = count($productsByCategory[$catName]);

        $layout      = $config['layout'];
        $side        = $config['side'];
        $anchorId    = $config['id'];
        $discoverUrl = $config['tab'];
    ?>

    <section class="shop-products-section <?= $layout ?> fade-in" id="<?= $anchorId ?>">

        <?php if ($side === 'left'): ?>
        <div class="shop-section-left">
            <div class="shop-section-image">
                <h2><?= htmlspecialchars($catName) ?></h2>
                <?php if ($totalInCat > SHOP_PREVIEW_LIMIT): ?>
                    <p class="shop-preview-count">
                        Showing <?= SHOP_PREVIEW_LIMIT ?> of <?= $totalInCat ?>
                    </p>
                <?php endif; ?>
                <button class="shop-discover-btn"
                    onclick="window.location.href='<?= $discoverUrl ?>'">
                    DISCOVER
                </button>
            </div>
        </div>
        <?php endif; ?>

        <div class="shop-products-grid">
            <?php foreach ($products as $product): ?>
                <?= renderProductCard($product, '../assets/images/products/', $wishlistedIds) ?>
            <?php endforeach; ?>
        </div>

        <?php if ($side === 'right'): ?>
        <div class="shop-section-right">
            <div class="shop-section-image">
                <h2><?= htmlspecialchars($catName) ?></h2>
                <?php if ($totalInCat > SHOP_PREVIEW_LIMIT): ?>
                    <p class="shop-preview-count">
                        Showing <?= SHOP_PREVIEW_LIMIT ?> of <?= $totalInCat ?>
                    </p>
                <?php endif; ?>
                <button class="shop-discover-btn"
                    onclick="window.location.href='<?= $discoverUrl ?>'">
                    DISCOVER
                </button>
            </div>
        </div>
        <?php endif; ?>

    </section>

    <?php endforeach; ?>

    <?php include '../components/footer.php'; ?>
    <script src="../assets/js/shopAnimations.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/HomepageAnimations.js"></script>
    <div id="generalToast" class="generalToast"></div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.shop-products-grid .wishlist-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const card = btn.closest('.product-card');
            const productId = card?.dataset?.productId;

            if (!productId) {
                showGeneralToast('Unable to save — product ID missing.', 'error');
                return;
            }

            fetch('../backend/add_to_wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(r => r.json())
            .then(data => {
                const icon = btn.querySelector('i');
                if (data.status === 'added') {
                    icon.classList.replace('fa-regular', 'fa-solid');
                    btn.style.color = '#c9a961';
                    showGeneralToast('Saved to wishlist!', 'success');
                } else if (data.status === 'removed') {
                    icon.classList.replace('fa-solid', 'fa-regular');
                    btn.style.color = '#fff';
                    showGeneralToast('Removed from wishlist.', 'info');
                } else {
                    showGeneralToast(data.message || 'Wishlist failed.', 'error');
                }
            })
            .catch(() => showGeneralToast('Wishlist request failed.', 'error'));
        });
    });
});
</script>
    </body>
</html>