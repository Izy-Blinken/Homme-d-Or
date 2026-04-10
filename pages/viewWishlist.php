<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';
$identity = getCurrentUserId();

// Only registered users can view wishlist
if ($identity['type'] !== 'user_id') {
    header("Location: index.php?login_required=true");
    exit;
}

$user_id = $identity['id'];

// Fetch wishlist items from DB
$sql = "SELECT w.wishlist_id, w.product_id, p.product_name, p.price, p.product_desc AS description,
               pi.image_url
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        WHERE w.user_id = ?
        ORDER BY w.added_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlistItems = [];
while ($row = $result->fetch_assoc()) {
    $wishlistItems[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme d'Or - Wishlist</title>
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
    <link rel="stylesheet" href="../assets/css/viewTabs.css">
    <link rel="stylesheet" href="../assets/css/ReviewCancelOrderStyle.css">
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="mainBG">
        <button class="back-btn" onclick="history.back()" title="Go back"><i class="fas fa-arrow-left"></i> Back</button>
        <div class="v-tabs">
            <h1 class="v-header">My Wishlist</h1>

            <div class="wishlist-controls">
                <div class="filter-dropdown">
                    <button class="filter-btn">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <div class="filter-menu">
                        <button class="filter-option" data-sort="name">Alphabetical</button>
                        <button class="filter-option" data-sort="price">By Price</button>
                    </div>
                </div>
            </div>

            <div class="tab-content active">

                <?php if (empty($wishlistItems)): ?>
                    <!-- Empty State -->
                    <div class="empty-wishlist-state">
                        <i class="fa-regular fa-heart"></i>
                        <h2>Your wishlist is empty</h2>
                        <p>Save items you love here to buy them later.</p>
                        <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
                    </div>

                <?php else: ?>
                    <!-- Wishlist Items -->
                    <?php foreach ($wishlistItems as $item):
                        $imgSrc = $item['image_url']
                            ? '../assets/images/products/' . htmlspecialchars($item['image_url'])
                            : '../assets/images/brand_images/nocturne.png';
                    ?>
                    <div class="v-orders" data-wishlist-id="<?php echo $item['wishlist_id']; ?>"
                         data-product-id="<?php echo $item['product_id']; ?>"
                         data-name="<?php echo htmlspecialchars($item['product_name']); ?>"
                         data-price="<?php echo $item['price']; ?>">

                        <div class="v-left">
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <div class="v-ordersinfo">
                                <p class="v-name"><?php echo htmlspecialchars($item['product_name']); ?></p>
                                <small class="v-desc"><?php echo htmlspecialchars($item['description'] ?? ''); ?></small>
                            </div>
                        </div>

                        <div class="v-right">
                            <p class="v-price">₱<?php echo number_format($item['price'], 2); ?></p>
                            <div class="v-actions">
                                <a href="productDetails.php?id=<?php echo $item['product_id']; ?>" class="v-view">View</a>
                                <button class="v-again"
                                        onclick="addToCartFromWishlist(<?php echo $item['product_id']; ?>)">
                                    Add to Cart
                                </button>
                                <button class="v-cancel"
                                        onclick="removeFromWishlist(<?php echo $item['product_id']; ?>, this)">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
    <script src="../assets/js/filterJS.js"></script>
    <script>
        // Remove item from wishlist
        function removeFromWishlist(productId, btn) {
            const card = btn.closest('.v-orders');

            fetch('../backend/add_to_wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'removed') {
                    // Fade out and remove the card
                    card.style.transition = 'opacity 0.3s';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        // Show empty state if no items left
                        const remaining = document.querySelectorAll('.v-orders');
                        if (remaining.length === 0) {
                            document.querySelector('.tab-content').innerHTML = `
                                <div class="empty-wishlist-state">
                                    <i class="fa-regular fa-heart"></i>
                                    <h2>Your wishlist is empty</h2>
                                    <p>Save items you love here to buy them later.</p>
                                    <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
                                </div>`;
                        }
                    }, 300);
                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error('Error:', err));
        }

        // Add to cart directly from wishlist
        function addToCartFromWishlist(productId) {
            fetch('../backend/add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId + '&quantity=1'
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Added to cart!');
                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error('Error:', err));
        }
    </script>
</body>
</html>