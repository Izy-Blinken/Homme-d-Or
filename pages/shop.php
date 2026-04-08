<?php
include '../backend/db_connect.php';
include '../backend/get_products_by_category.php';

$productsByCategory = getProductsByCategory($conn);

// Map your exact category names to their section IDs and layout sides
// Key = category_name in DB, value = [anchor_id, layout_class, label_side]
$categoryConfig = [
    'New Arrivals' => ['id' => 'new-arrivals', 'layout' => 'shop-layout-left', 'side' => 'left', 'tab' => 'newArrival.php'],
    'Top Picks' => ['id' => 'top-picks', 'layout' => 'shop-layout-right', 'side' => 'right', 'tab' => 'newArrival.php?tab=page2'],
    'Sale' => ['id' => 'sale', 'layout' => 'shop-layout-left', 'side' => 'left', 'tab' => 'newArrival.php?tab=page3'],
    'Daily Wear' => ['id' => 'daily-wear', 'layout' => 'shop-layout-right', 'side' => 'right', 'tab' => 'newArrival.php?tab=page4'],
    'Premium' => ['id' => 'premium', 'layout' => 'shop-layout-left', 'side' => 'left', 'tab' => 'newArrival.php?tab=page5'],
];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Shop</title>
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

    <!-- Welcome Section -->
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

    <?php
    $layoutToggle = 'left'; 

    foreach ($categoryConfig as $catName => $config):
        if (empty($productsByCategory[$catName])) continue; 

        $products  = $productsByCategory[$catName];
        $layout = $config['layout'];
        $side = $config['side'];
        $anchorId = $config['id'];
        $discoverUrl = $config['tab'];
    ?>

    <section class="shop-products-section <?= $layout ?> fade-in" id="<?= $anchorId ?>">

        <?php if ($side === 'left'): ?>
        <!-- Label panel on the LEFT -->
        <div class="shop-section-left">
            <div class="shop-section-image">
                <h2><?= htmlspecialchars($catName) ?></h2>
                <button class="shop-discover-btn" 
                    onclick="window.location.href='<?= $discoverUrl ?>'">
                    DISCOVER
                </button>
            </div>
        </div>
        
        <?php endif; ?>

        <!-- Product Cards -->
        <div class="shop-products-grid">
            <?php foreach ($products as $product): ?>
                <?= renderProductCard($product) ?>
            <?php endforeach; ?>
        </div>

        <?php if ($side === 'right'): ?>
        <!-- Label panel on the RIGHT -->
        <div class="shop-section-right">
            <div class="shop-section-image">
                <h2><?= htmlspecialchars($catName) ?></h2>
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
    </body>
</html>