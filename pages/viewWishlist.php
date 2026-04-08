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
</head>

<body> 
    <?php include '../components/header.php'; ?>

    <main class="mainBG">
        
        <div class="v-tabs">
            <h1 class="v-header">My Wishlist</h1>

            <div class="wishlist-controls">
                <div class="filter-dropdown">
                    <button class="filter-btn">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <div class="filter-menu">
                        <button class="filter-option">Alphabetical</button>
                        <button class="filter-option">By Price</button>
                    </div>
                </div>
            </div>

            <div class="tab-content active">
                
                <div class="empty-wishlist-state">
                    <i class="fa-regular fa-heart"></i>
                    <h2>Your wishlist is empty</h2>
                    <p>Save items you love here to buy them later.</p>
                    <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume">
                        <div class="v-ordersinfo">
                            <p class="v-name">Product Name</p>
                            <small class="v-desc">250ml • Variant ng perfume</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱4,200.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume">
                        <div class="v-ordersinfo">
                            <p class="v-name">Product Name</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱1,800.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume">
                        <div class="v-ordersinfo">
                            <p class="v-name">Product Name</p>
                            <small class="v-desc">500ml • Variant ng perfume</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱5,500.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Another Product">
                        <div class="v-ordersinfo">
                            <p class="v-name">Another Product</p>
                            <small class="v-desc">100ml • Limited Edition</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Premium Scent">
                        <div class="v-ordersinfo">
                            <p class="v-name">Premium Scent</p>
                            <small class="v-desc">150ml • Exclusive</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

     <div id="viewOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
            <span class="view-close-btn" onclick="closeViewModal()">&times;</span>
                <h2>Product Details</h2>
            </div>
            <div class="romcomDivider"></div>

            <div class="romcomBody">
                <!-- Product Info -->
                <div class="view-section">
                    <h4>PRODUCT</h4>
                    <div class="view-product">
                        <img id="viewImage" src="" width="70" alt="Product Image">
                        <div>
                            <p id="viewName"></p>
                            <small id="viewVariant"></small>
                        </div>
                    </div>
                </div>

    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/filterJS.js"></script>
</body>
</html>