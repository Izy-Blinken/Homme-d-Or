<?php
// Safely start session so it doesn't conflict with your header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include '../backend/db_connect.php'; // Keep DB commented out for UI testing

// 1. Get the search query
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

// 2. "Fake Database" of fragrances to test the UI
$dummyProducts = [
    ['id' => 1, 'product_name' => 'Midnight Oud', 'price' => 120.00, 'tag' => 'Best Seller'],
    ['id' => 2, 'product_name' => 'Ocean Breeze', 'price' => 85.50, 'tag' => 'New Arrival'],
    ['id' => 3, 'product_name' => 'Vanilla Dreams', 'price' => 60.00, 'tag' => ''],
    ['id' => 4, 'product_name' => 'Spicy Amber', 'price' => 95.00, 'tag' => ''],
    ['id' => 5, 'product_name' => 'Citrus Bloom', 'price' => 75.00, 'tag' => 'Limited Edition'],
    ['id' => 6, 'product_name' => 'Oud Wood Intense', 'price' => 150.00, 'tag' => 'Premium']
];

// 3. Simulate database search
if ($searchQuery !== '') {
    foreach ($dummyProducts as $product) {
        if (stripos($product['product_name'], $searchQuery) !== false) {
            $results[] = $product;
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search Results | Homme d'Or</title>
        
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
        <link rel="stylesheet" href="../assets/css/CheckoutPageStyle.css">
        <link rel="stylesheet" href="../assets/css/OrderAgainStyle.css">
        <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
        <link rel="stylesheet" href="../assets/css/AboutUsPageStyle.css">
        <link rel="stylesheet" href="../assets/css/ProductDetailsStyle.css">
        <link rel="stylesheet" href="../assets/css/CartPageStyle.css">
        <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
        <link rel="stylesheet" href="../assets/css/ProfilePageStyle.css">
        <link rel="stylesheet" href="../assets/css/searchpage.css">

    </head>

    <body>
        <?php include '../components/header.php'; ?>
        
        <main class="search-main">
            <div class="search-header-container">
                <h2 class="search-title">
                    Search Results for: "<span><?php echo htmlspecialchars($searchQuery); ?></span>"
                </h2>
                <span class="search-count">
                    <?php echo count($results); ?> items found
                </span>
            </div>
            
            <?php if (empty($results) && $searchQuery !== ''): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
                    <h3 style="color: #0e101f; margin-bottom: 10px;">No fragrances found</h3>
                    <p style="font-size: 16px; color: #666;">We couldn't find anything matching "<?php echo htmlspecialchars($searchQuery); ?>". Try searching for "Oud" or "Vanilla" to test the dummy data!</p>
                    <a href="shop.php" class="btn-back">Back to Shop</a>
                </div>
                
            <?php elseif (!empty($results)): ?>
                <div class="product-grid">
                    <?php foreach ($results as $item): ?>
                        <div class="product-card">
                            
                            <?php if(!empty($item['tag'])): ?>
                                <span class="product-tag"><?php echo $item['tag']; ?></span>
                            <?php endif; ?>

                            <div class="product-image-placeholder">
                                <i class="fa-solid fa-bottle-droplet" style="font-size: 50px; color: #d2b231; opacity: 0.5;"></i>
                            </div>
                            
                            <h3 class="product-title"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                            <p class="product-price">$<?php echo number_format($item['price'], 2); ?></p>
                            
                            <div class="action-buttons">
                                <button class="btn-wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="btn-cart">Add to Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>

        <?php include '../components/footer.php'; ?>
    </body>
</html>