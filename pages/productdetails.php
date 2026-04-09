<?php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../backend/db_connect.php';
$identity = getCurrentUserId();

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product from DB
$product = null;
if ($product_id > 0) {
    $stmt = $conn->prepare("
        SELECT p.*, pi.image_url
        FROM products p
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        WHERE p.product_id = ?
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$product) {
    header("Location: shop.php");
    exit;
}

// Check if this product is already in the user's wishlist
$isWishlisted = false;
if ($identity['type'] === 'user_id') {
    $wCheck = $conn->prepare("SELECT wishlist_id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $wCheck->bind_param("ii", $identity['id'], $product_id);
    $wCheck->execute();
    $isWishlisted = $wCheck->get_result()->num_rows > 0;
    $wCheck->close();
}

$imgSrc = $product['image_url']
    ? '../assets/images/products/' . htmlspecialchars($product['image_url'])
    : '../assets/images/brand_images/nocturne.png';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($product['product_name']); ?> | Homme d'Or</title>
        
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
            /* Wishlist heart button */
            .wishlist-btn {
                background: transparent;
                border: 1px solid #c9a961;
                color: #c9a961;
                padding: 12px 18px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 1.1rem;
                transition: all 0.3s ease;
            }
            .wishlist-btn:hover {
                background: #c9a961;
                color: #fff;
            }
            .wishlist-btn.wishlisted {
                background: #c9a961;
                color: #fff;
            }
            .wishlist-btn.wishlisted i {
                font-weight: 900; /* solid heart */
            }
        </style>
    </head>
    <body>
        <?php include '../components/header.php'; ?>

        <main class="mainBG">
            <a href="javascript:history.back()" class="back-button">
                <i class="fa-solid fa-chevron-left"></i>
            </a>

            <section id="product-details-section">
                <div class="product-details-container">
                    
                    <div class="product-image-section">
                        <img src="<?php echo $imgSrc; ?>"
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                             class="product-image" id="main-product-image">
                        
                        <div class="product-thumbnails">
                            <img src="<?php echo $imgSrc; ?>" alt="Front View" class="thumbnail active-thumb" onclick="changeImage(this)">
                        </div>
                    </div>

                    <div class="product-dtls">
                        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
                        
                        <div class="price-stock-wrapper">
                            <h4>₱<?php echo number_format($product['price'], 2); ?></h4>
                            <?php
                                $status = $product['product_status'] ?? 'in-stock';
                                if ($status === 'in-stock'):
                            ?>
                                <span class="stock-status in-stock">
                                    <i class="fa-solid fa-check-circle"></i> In Stock
                                </span>
                            <?php elseif ($status === 'low-stock'): ?>
                                <span class="stock-status" style="color: orange;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Low Stock
                                </span>
                            <?php else: ?>
                                <span class="stock-status" style="color: red;">
                                    <i class="fa-solid fa-circle-xmark"></i> Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($product['product_desc'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($product['product_desc'])); ?></p>
                        <?php endif; ?>

                        <div class="product-actions">
                            <!-- Buy Now -->
                            <a href="checkout.php?product_id=<?php echo $product_id; ?>">
                                <button class="buynow">
                                    <i class="fa-solid fa-bolt"></i> Buy Now
                                </button>
                            </a>
                            
                            <!-- Add to Cart -->
                            <button class="addtocart" onclick="addToCart(<?php echo $product_id; ?>)">
                                <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                            </button>
                            
                            <!-- ❤️ Wishlist Heart Button (registered users only) -->
                            <?php if ($identity['type'] === 'user_id'): ?>
                                <button class="wishlist-btn <?php echo $isWishlisted ? 'wishlisted' : ''; ?>"
                                        id="wishlist-btn"
                                        onclick="toggleWishlist(<?php echo $product_id; ?>)">
                                    <i class="<?php echo $isWishlisted ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                                </button>
                            <?php elseif ($identity['type'] === 'guest_id'): ?>
                                <button class="wishlist-btn" onclick="openLoginModal(); showGeneralToast('Please log in to save to your Wishlist!', 'info')">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            <?php endif; ?>
                            <!-- Strangers see no heart button at all -->
                        </div>
                    </div>
                </div>
            </section>

            <section id="details-section">
                <h2>The Olfactory Journey</h2>
                <div class="details-grid">
                    <div class="detailsSection-box">
                        <i class="fa-solid fa-wind note-icon"></i>
                        <h4>Top Notes</h4>
                        <p>The initial burst. Bright, fresh citrus and delicate floral whispers that immediately captivate the senses upon the first spray.</p>
                    </div>
                    <div class="detailsSection-box">
                        <i class="fa-solid fa-spa note-icon"></i>
                        <h4>Heart Notes</h4>
                        <p>The core identity. Rich velvet rose and sensual jasmine that unfold elegantly as the fragrance settles onto your skin.</p>
                    </div>
                    <div class="detailsSection-box">
                        <i class="fa-solid fa-tree note-icon"></i>
                        <h4>Base Notes</h4>
                        <p>The lasting impression. Deep amber woods and platinum musk that anchor the scent, leaving an unforgettable, lingering trail.</p>
                    </div>
                </div>
            </section>

            <section id="reviews-section">
                <div class="reviews-header">
                    <h2>Customer Reviews</h2>
                    <div class="aggregate-rating">
                        <div class="rating-score">4.8</div>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                        <p>Based on 24 reviews</p>
                        <a href="viewReview.php" style="text-decoration: none;">
                            <button class="write-review-btn">Read All Reviews</button>
                        </a>
                    </div>
                </div>

                <div class="reviews-container">
                    <div class="review-box">
                        <img src="../assets/images/products_images/customerPic.png" alt="User">
                        <div class="review-text">
                            <div class="review-title-row">
                                <h4>Wally B.</h4>
                                <div class="review-stars">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <p>Long lasting smell. Worth the price.</p>
                        </div>
                    </div>
                    <div class="review-box">
                        <img src="../assets/images/products_images/customerPic.png" alt="User">
                        <div class="review-text">
                            <div class="review-title-row">
                                <h4>Bayola W.</h4>
                                <div class="review-stars">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                                </div>
                            </div>
                            <p>Yiz galing. Highly recommended.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="new-arrivals-section">
                <div class="new-arrivals-container">
                    <div class="section-header">
                        <div class="header-line" style="background-color: lightgrey"></div>
                        <h2 style="color: white; font-weight: 300; letter-spacing: 2px;">You May Also Like</h2>
                        <div class="header-line" style="background-color: lightgrey"></div>
                    </div>
                    <div class="new-arrivals-wrapper">
                        <div class="new-arrivals-overflow">
                            <div class="new-arrivals-grid" id="arrivalsGrid">
                                <div class="new-arrival-card">
                                    <div class="new-arrival-image">
                                        <img src="../assets/images/brand_images/evrland.jpg" alt="New Arrival 1">
                                    </div>
                                    <button class="arrival-add-cart">ADD TO CART</button>
                                </div>
                                <div class="new-arrival-card">
                                    <div class="new-arrival-image">
                                        <img src="../assets/images/brand_images/evrland.jpg" alt="New Arrival 2">
                                    </div>
                                    <button class="arrival-add-cart">ADD TO CART</button>
                                </div>
                                <div class="new-arrival-card">
                                    <div class="new-arrival-image">
                                        <img src="../assets/images/brand_images/evrland.jpg" alt="New Arrival 3">
                                    </div>
                                    <button class="arrival-add-cart">ADD TO CART</button>
                                </div>
                            </div>
                        </div>
                        <button class="scroll-next-btn" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
                        <div class="line-indicators" id="lineIndicators">
                            <span class="indicator active"></span>
                            <span class="indicator"></span>
                            <span class="indicator"></span>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php include '../components/footer.php'; ?>
        <script src="../assets/js/HomepageAnimations.js"></script>
        <script src="../assets/js/productDetails.js"></script>

        <script>
           // ── Add to Cart ──────────────────────────────────────────
            function addToCart(productId) {
                fetch('../backend/add_to_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'product_id=' + productId + '&quantity=1'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showGeneralToast(data.message, 'success');
                    } else {
                        showGeneralToast(data.message, 'error');
                    }
                })
                .catch(err => console.error('Cart error:', err));
            }

            // ── Toggle Wishlist Heart ─────────────────────────────────
            function toggleWishlist(productId) {
                const btn = document.getElementById('wishlist-btn');
                const icon = btn.querySelector('i');

                fetch('../backend/add_to_wishlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'product_id=' + productId
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'added') {
                        btn.classList.add('wishlisted');
                        icon.classList.replace('fa-regular', 'fa-solid');
                        showGeneralToast('Added to wishlist!', 'success');
                    } else if (data.status === 'removed') {
                        btn.classList.remove('wishlisted');
                        icon.classList.replace('fa-solid', 'fa-regular');
                        showGeneralToast('Removed from wishlist.', 'info');
                    } else {
                        showGeneralToast(data.message, 'error');
                    }
                })
                .catch(err => console.error('Wishlist error:', err));
            }

        </script>
        <div id="generalToast" class="generalToast"></div>
        <script src="../assets/js/script.js"></script>
    </body>
</html>