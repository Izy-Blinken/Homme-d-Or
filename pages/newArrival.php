<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once '../backend/db_connect.php';
include_once '../backend/get_products_by_category.php';

$identity   = getCurrentUserId();
$isLoggedIn = ($identity['type'] === 'user_id');
$isGuest    = ($identity['type'] === 'guest_id');

// ── Fetch all products from DB grouped by category ────────────────────────
$productsByCategory = getProductsByCategory($conn);
$wishlistedIds      = getWishlistedIds($conn);

// ── Tab config: maps page ID → category name + UI labels ─────────────────
$tabConfig = [
    'page1' => [
        'category'          => 'New Arrivals',
        'tabLabel'          => 'New Arrivals',
        'heroLabel'         => 'Homme D\'or — 2026',
        'heroTitle'         => 'New <span>Arrivals</span>',
        'heroText'          => 'Fresh introductions to our fragrance collection',
        'sectionLabel'      => 'Just Landed',
        'sectionTitle'      => 'Fresh <span>Introductions</span>',
        'searchPlaceholder' => 'Search new arrivals...',
    ],
    'page2' => [
        'category'          => 'Top Picks',
        'tabLabel'          => 'Top Picks',
        'heroLabel'         => 'Homme D\'or — 2026',
        'heroTitle'         => 'Top <span>Picks</span>',
        'heroText'          => 'Our most-loved fragrances, curated for you',
        'sectionLabel'      => 'Editor\'s Choice',
        'sectionTitle'      => 'Best <span>Sellers</span>',
        'searchPlaceholder' => 'Search top picks...',
    ],
    'page3' => [
        'category'          => 'Sale',
        'tabLabel'          => 'Sales',
        'heroLabel'         => 'Homme D\'or — 2026',
        'heroTitle'         => 'On <span>Sale</span>',
        'heroText'          => 'Exclusive deals on premium fragrances',
        'sectionLabel'      => 'Limited Offers',
        'sectionTitle'      => 'Sale <span>Picks</span>',
        'searchPlaceholder' => 'Search sale items...',
    ],
    'page4' => [
        'category'          => 'Daily Wear',
        'tabLabel'          => 'Daily Wear',
        'heroLabel'         => 'Homme D\'or — 2026',
        'heroTitle'         => 'Daily <span>Wear</span>',
        'heroText'          => 'Effortless scents for every day',
        'sectionLabel'      => 'Everyday Essentials',
        'sectionTitle'      => 'Daily <span>Favorites</span>',
        'searchPlaceholder' => 'Search daily wear...',
    ],
    'page5' => [
        'category'          => 'Premium',
        'tabLabel'          => 'Premium',
        'heroLabel'         => 'Homme D\'or Privé',
        'heroTitle'         => 'Premium <span>Collection</span>',
        'heroText'          => 'Rare ingredients. Uncompromising luxury.',
        'sectionLabel'      => 'Privé Collection',
        'sectionTitle'      => 'Premium <span>Selection</span>',
        'searchPlaceholder' => 'Search premium...',
    ],
];

// ── Determine active tab from URL param ───────────────────────────────────
$activeTab = isset($_GET['tab']) && array_key_exists($_GET['tab'], $tabConfig)
             ? $_GET['tab']
             : 'page1';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <link rel="stylesheet" href="../assets/css/ShopPagesStyle.css">
    </head>
    <body>

    <?php include '../components/header.php'; ?>

    <!-- ── Tab Navigation ─────────────────────────────────────────────── -->
    <div class="tabs">
        <?php foreach ($tabConfig as $pageId => $cfg): ?>
            <button
                class="tab <?= $activeTab === $pageId ? 'active' : '' ?>"
                onclick="showPage('<?= $pageId ?>')">
                <?= htmlspecialchars($cfg['tabLabel']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- ── Pages ──────────────────────────────────────────────────────── -->
    <?php foreach ($tabConfig as $pageId => $cfg):
        $catName  = $cfg['category'];
        $products = $productsByCategory[$catName] ?? [];
        $isActive = ($activeTab === $pageId);
    ?>
    <div class="page <?= $isActive ? 'active' : '' ?>" id="<?= $pageId ?>">

        <!-- Hero -->
        <div class="hero">
            <div class="hero-bg-effects"></div>
            <div class="hero-content">
                <div class="hero-label"><?= htmlspecialchars($cfg['heroLabel']) ?></div>
                <h1 class="hero-title"><?= $cfg['heroTitle'] ?></h1>
                <div class="hero-line"></div>
                <p class="hero-text"><?= htmlspecialchars($cfg['heroText']) ?></p>
            </div>
            <div class="hero-decoration"></div>
        </div>

        <!-- Search -->
        <div class="shop-controls">
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input
            type="text"
            class="search-input"
            placeholder="<?= htmlspecialchars($cfg['searchPlaceholder']) ?>"
            autocomplete="off">
        <div class="search-suggestions"></div>
    </div>

    <div class="sort-container">
        <label class="sort-label" for="sort-<?= $pageId ?>">Sort By</label>
        <select class="sort-select" id="sort-<?= $pageId ?>">
            <option value="default">Default</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="name-asc">Alphabetical: A–Z</option>
            <option value="name-desc">Alphabetical: Z–A</option>
            <option value="most-bought">Most Bought</option>
        </select>
    </div>
</div>

        <!-- Section Header -->
        <div class="section-header">
            <div>
                <div class="section-label"><?= htmlspecialchars($cfg['sectionLabel']) ?></div>
                <h2 class="section-title"><?= $cfg['sectionTitle'] ?></h2>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <p class="no-products-msg">No products available in this category yet.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?= renderProductCard($product, '../assets/images/products/', $wishlistedIds) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
    <?php endforeach; ?>

    <script src="../assets/js/shopPages.js"></script>
    <div id="generalToast" class="generalToast"></div>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/HomepageAnimations.js"></script>

    <script>
    // ── User identity passed from PHP ──────────────────────────────────────
    const IS_LOGGED_IN = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    const IS_GUEST     = <?php echo $isGuest    ? 'true' : 'false'; ?>;

    document.addEventListener('DOMContentLoaded', function () {

        // ── Wire up ALL Add to Cart buttons ───────────────────────────────
        document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
            // renderProductCard already uses onclick="addToCart(id, this)"
            // but we wire a fallback here for any card without a product ID
            if (!btn.getAttribute('onclick')) {
                btn.addEventListener('click', function () {
                    showGeneralToast('Product unavailable.', 'error');
                });
            }
        });

        // ── Wire up ALL Wishlist heart buttons ────────────────────────────
        document.querySelectorAll('.wishlist-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                
                if (IS_GUEST) {
                    showGeneralToast('Create a free account to save your wishlist!', 'info');
                    return;
                }
                if (!IS_LOGGED_IN) {
                    window.location.href = 'index.php?login_required=true';
                    return;
                }

                const card      = btn.closest('.product-card');
                const productId = card ? card.dataset.productId : null;

                if (!productId) {
                    showGeneralToast('Unable to save — product ID missing.', 'error');
                    return;
                }

                fetch('../backend/add_to_wishlist.php', {
                    method : 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body   : 'product_id=' + productId
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'added') {
                        toggleHeartVisual(btn, true);
                        showGeneralToast('Saved to wishlist!', 'info');
                    } else if (data.status === 'removed') {
                        toggleHeartVisual(btn, false);
                        showGeneralToast('Removed from wishlist.', 'info');
                    } else {
                        showGeneralToast(data.message, 'error');
                    }
                })
                .catch(err => console.error('Wishlist error:', err));
            });
        });
    });

    // ── Toggle heart icon filled / empty ──────────────────────────────────
    function toggleHeartVisual(btn, forceState) {
        const icon     = btn.querySelector('i');
        const isFilled = icon.classList.contains('fa-solid');
        const makeFill = (forceState !== undefined) ? forceState : !isFilled;

        if (makeFill) {
            icon.classList.replace('fa-regular', 'fa-solid');
            btn.style.color = '#c9a961';
        } else {
            icon.classList.replace('fa-solid', 'fa-regular');
            btn.style.color = '';
        }
    }
    </script>

    <?php include '../components/footer.php'; ?>
</body>
</html>