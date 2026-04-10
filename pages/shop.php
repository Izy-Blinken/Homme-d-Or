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
        <style>
        /* ── shop.php preview grid ─────────────────────────────────────────────
           Every rule is scoped to .shop-products-grid and uses !important where
           HomepageStyle.css is known to fight back with the same class names:
             .product-card        — flex sizing, overflow:hidden, wrong bg/border
             .product-card:hover  — only -4px lift, wrong bg
             .quick-view-btn      — floating centered bubble (bottom:15px, left:50%)
             .sold-out-label      — vertically centered, not pinned to bottom
             .add-to-cart-btn     — solid dark bg + gold border
             .shop-product-info   — white-tint bg, wrong sizes/colours
        ─────────────────────────────────────────────────────────────────────── */

        @keyframes shopCardIn {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* --- Wishlist button fix for shop.php --- */
        .shop-products-grid .wishlist-btn {
            position: absolute !important;
            top: 12px !important;
            right: 12px !important;
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
            z-index: 60 !important;          /* above quick view/sold out */
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            pointer-events: auto !important;
        }

        .shop-products-grid .wishlist-btn i {
            pointer-events: none !important; /* click goes to button */
        }

        /* keep overlays below heart */
        .shop-products-grid .quick-view-btn { z-index: 20 !important; }
        .shop-products-grid .sold-out-label { z-index: 15 !important; }
        .shop-products-grid .shop-product-image { position: relative !important; }
        
        /* ── Card base ─────────────────────────────────────────────────────── */
        .shop-products-grid .product-card {
            /* Reset HomepageStyle flex-item sizing */
            flex: none !important;
            /* Corner brackets need to spill outside the card */
            overflow: visible !important;
            /* Correct dark-navy card bg (HomepageStyle uses rgba(255,255,255,.08)) */
            background: rgba(7, 21, 37, 0.9) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            /* Reset HomepageStyle white border */
            border: none !important;
            border-radius: 0 !important;
            position: relative !important;
            cursor: pointer !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            animation: shopCardIn 0.6s ease both !important;
        }

        /* ── Corner bracket decorations ────────────────────────────────────── */
        .shop-products-grid .product-card::before,
        .shop-products-grid .product-card::after {
            content: '' !important;
            position: absolute !important;
            z-index: 5 !important;
            width: clamp(10px, 2vw, 18px) !important;
            height: clamp(10px, 2vw, 18px) !important;
            transition: all 0.35s !important;
            /* Reset any HomepageStyle pseudo-element styles */
            background: none !important;
            border-radius: 0 !important;
        }

        .shop-products-grid .product-card::before {
            top: 0 !important; left: 0 !important;
            border-top: 1px solid rgba(201, 168, 76, 0.5) !important;
            border-left: 1px solid rgba(201, 168, 76, 0.5) !important;
            border-bottom: none !important;
            border-right: none !important;
        }

        .shop-products-grid .product-card::after {
            bottom: 0 !important; right: 0 !important;
            border-bottom: 1px solid rgba(201, 168, 76, 0.5) !important;
            border-right: 1px solid rgba(201, 168, 76, 0.5) !important;
            border-top: none !important;
            border-left: none !important;
        }

        /* ── Card hover ─────────────────────────────────────────────────────── */
        /* HomepageStyle only lifts -4px with rgba(255,255,255,.12) bg — override both */
        .shop-products-grid .product-card:hover {
            transform: translateY(-8px) !important;
            background: rgba(7, 21, 37, 0.9) !important;
            border-color: rgba(201, 168, 76, 0.45) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
                        0 0 80px rgba(201, 168, 76, 0.06) !important;
        }

        .shop-products-grid .product-card:hover::before,
        .shop-products-grid .product-card:hover::after {
            width: clamp(18px, 3.5vw, 30px) !important;
            height: clamp(18px, 3.5vw, 30px) !important;
        }

        /* ── Product image container ────────────────────────────────────────── */
        .shop-products-grid .shop-product-image {
            position: relative !important;
            aspect-ratio: 1 / 1 !important;
            background: linear-gradient(145deg, #0a1e36, #071525) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;  /* clips the Quick View slide-up within the image */
        }

        .shop-products-grid .shop-product-image img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
            transition: transform 0.5s !important;
        }

        .shop-products-grid .product-card:hover .shop-product-image img {
            transform: scale(1.07) translateY(-4px) !important;
        }

        /* ── Quick View button ──────────────────────────────────────────────────
           HomepageStyle makes this a floating centered bubble:
             bottom: 15px; left: 50%; transform: translateX(-50%); border: 1px solid gold
           We need a full-width bar pinned to the bottom that slides up.
        ─────────────────────────────────────────────────────────────────────── */
        .shop-products-grid .quick-view-btn {
            position: absolute !important;
            /* Pin to bottom edge — override HomepageStyle's bottom:15px */
            bottom: 0 !important;
            /* Full width — override HomepageStyle's left:50% centering */
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            /* Typography */
            font-size: clamp(0.5rem, 0.8vw, 0.62rem) !important;
            letter-spacing: 0.2em !important;
            text-transform: uppercase !important;
            font-weight: 400 !important;
            /* Colours — override HomepageStyle's rgba(14,16,31,.6) + border */
            background: rgba(4, 13, 26, 0.85) !important;
            color: #c9a84c !important;
            border: none !important;
            border-top: 1px solid rgba(201, 168, 76, 0.3) !important;
            border-radius: 0 !important;
            padding: clamp(9px, 1.3vw, 13px) !important;
            cursor: pointer !important;
            /* Hidden state: invisible + slid down 6px */
            opacity: 0 !important;
            /* Override HomepageStyle's translateX(-50%) with our slide-up transform */
            transform: translateY(6px) !important;
            transition: opacity 0.3s, transform 0.3s !important;
            z-index: 10 !important;
            display: block !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        /* On hover: slide into view
           HomepageStyle sets bottom:20px here — we override with transform instead */
        .shop-products-grid .product-card:hover .quick-view-btn {
            opacity: 1 !important;
            transform: translateY(0) !important;
            bottom: 0 !important;  /* prevent HomepageStyle's bottom:20px from firing */
        }

        /* HomepageStyle has .quick-view-btn:hover { background: rgb(3,3,84) }
           which flips the button dark-blue the moment the cursor lands on it.
           Lock it to the same dark-navy + gold used in newArrival.php. */
        .shop-products-grid .quick-view-btn:hover {
            background: rgba(4, 13, 26, 0.85) !important;
            border: none !important;
            border-top: 1px solid rgba(201, 168, 76, 0.3) !important;
            color: #c9a84c !important;
        }

        /* ── Sold Out label ─────────────────────────────────────────────────────
           HomepageStyle centers it: top:50%; left:50%; transform:translate(-50%,-50%)
           We pin it to the bottom edge like the Quick View bar.
        ─────────────────────────────────────────────────────────────────────── */
        .shop-products-grid .sold-out-label {
            position: absolute !important;
            /* Override HomepageStyle's vertical centering */
            top: auto !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            transform: none !important;
            text-align: center !important;
            font-size: clamp(0.5rem, 0.8vw, 0.62rem) !important;
            letter-spacing: 0.2em !important;
            text-transform: uppercase !important;
            font-weight: 400 !important;
            /* Override HomepageStyle's rgba(0,0,0,.6) + blurred bg */
            background: rgba(139, 26, 26, 0.85) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            color: #f0e8d5 !important;
            border: none !important;
            padding: clamp(9px, 1.3vw, 13px) !important;
            z-index: 4 !important;
        }

        /* ── Add to Cart button ─────────────────────────────────────────────────
           HomepageStyle: solid dark bg, gold border, 15px padding, 14px font.
           We want: transparent bg, top/bottom hairlines only, gold fill on hover.
        ─────────────────────────────────────────────────────────────────────── */
        .shop-products-grid .add-to-cart-btn {
            width: 100% !important;
            font-size: clamp(0.5rem, 0.8vw, 0.62rem) !important;
            letter-spacing: 0.2em !important;
            text-transform: uppercase !important;
            font-weight: 400 !important;
            /* Override HomepageStyle's rgb(14,16,31) solid background */
            background: transparent !important;
            /* Override HomepageStyle's full gold border */
            border: none !important;
            border-top: 1px solid rgba(201, 168, 76, 0.15) !important;
            border-bottom: 1px solid rgba(201, 168, 76, 0.15) !important;
            border-radius: 0 !important;
            color: #c9a84c !important;
            cursor: pointer !important;
            padding: clamp(9px, 1.3vw, 13px) !important;
            transition: color 0.3s !important;
            position: relative !important;
            overflow: hidden !important;
            z-index: 1 !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        /* Gold fill sweep — pseudo-element acts as the background */
        .shop-products-grid .add-to-cart-btn::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: #c9a84c !important;
            transform: scaleX(0) !important;
            transform-origin: left !important;
            transition: transform 0.35s !important;
            z-index: -1 !important;
        }

        /* Override HomepageStyle's hover (dark blue bg + gold border) */
        .shop-products-grid .add-to-cart-btn:hover {
            background: transparent !important;
            border-color: rgba(201, 168, 76, 0.15) !important;
            color: #040d1a !important;
        }

        .shop-products-grid .add-to-cart-btn:hover::before {
            transform: scaleX(1) !important;
        }

        /* Override HomepageStyle's disabled (grey bg) */
        .shop-products-grid .add-to-cart-btn:disabled {
            background: transparent !important;
            border-color: rgba(201, 168, 76, 0.08) !important;
            color: rgba(201, 168, 76, 0.3) !important;
            opacity: 0.35 !important;
            cursor: not-allowed !important;
        }

        .shop-products-grid .add-to-cart-btn:disabled::before {
            display: none !important;
        }

        /* ── Product info ────────────────────────────────────────────────────────
           HomepageStyle: white-tint bg, centred text, 20px/18px font sizes
        ─────────────────────────────────────────────────────────────────────── */
        /* ── Product info ──────────────────────────────────────────────────────── */
        .shop-products-grid .shop-product-info {
            padding: clamp(12px, 1.8vw, 18px) !important;
            background: transparent !important;
            text-align: left !important; /* Forces everything inside to align left */
        }

        .shop-products-grid .shop-product-title {
            font-size: clamp(0.78rem, 1.2vw, 1rem) !important;
            font-weight: 600 !important;
            color: #f0e8d5 !important;
            letter-spacing: 0.04em !important;
            margin-bottom: clamp(5px, 0.8vw, 8px) !important;
        }

        .shop-products-grid .shop-product-price {
            font-size: clamp(0.9rem, 1.4vw, 1.15rem) !important;
            font-weight: 700 !important;
            color: #c9a84c !important;
            margin: 0 !important;
        }
        </style>
    </head>
    <body>
    <?php include '../components/header.php'; ?>
    <button class="back-btn" onclick="history.back()" title="Go back" style="position: absolute; top: 100px; left: 20px; z-index: 100;"><i class="fas fa-arrow-left"></i> Back</button>

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