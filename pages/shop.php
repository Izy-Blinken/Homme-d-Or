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

    <!-- Welcome Section - Fixed Background with Image -->
    <section class="shop-welcome-section">
        <div class="shop-welcome-overlay"></div>
        <div class="shop-welcome-content">
            <h1><span class="greeting-text">Welcome</span></h1>
            <nav class="shop-category-nav">
                <a href="#new-arrivals" class="shop-category-link">New Arrivals</a>
                <a href="#top-picks" class="shop-category-link">Top Picks</a>
                <a href="#sale" class="shop-category-link">Sale</a>
                <a href="#daily-wear" class="shop-category-link">Daily Wear</a>
                <a href="#premium" class="shop-category-link">Premium</a>
            </nav>
        </div>
    </section>

    <div class="shop-scroll-spacer"></div>

    <!-- New Arrivals Section - LEFT LAYOUT -->
    <section class="shop-products-section shop-layout-left fade-in" id="new-arrivals">
        <div class="shop-section-left">
            <div class="shop-section-image">
                <h2>New Arrivals</h2>
                <button class="shop-discover-btn" onclick="window.location.href='newArrival.php'">DISCOVER</button>
            </div>
        </div>

        <div class="shop-products-grid">
            <!-- Product Card 1 -->
            <div class="product-card fade-in">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>


            <!-- Product Card 3 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Picks Section - RIGHT LAYOUT -->
    <section class="shop-products-section shop-layout-right fade-in" id="top-picks">
        <div class="shop-products-grid">
            <!-- Product Card 1 -->
           <div class="product-card fade-in">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>

        <div class="shop-section-right">
            <div class="shop-section-image">
                <h2>Top Picks</h2>
                <button class="shop-discover-btn">DISCOVER</button>
            </div>
        </div>
    </section>

    <!-- Sale Section - LEFT LAYOUT -->
    <section class="shop-products-section shop-layout-left fade-in" id="sale">
        <div class="shop-section-left">
            <div class="shop-section-image">
                <h2>Sale</h2>
                <button class="shop-discover-btn" onclick="window.location.href='Sale.php'">DISCOVER</button>
            </div>
        </div>

        <div class="shop-products-grid">
            <!-- Product Cards 1-6 (same structure as above) -->
            <div class="product-card fade-in">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Daily Wear Section - RIGHT LAYOUT -->
    <section class="shop-products-section shop-layout-right fade-in" id="daily-wear">
        <div class="shop-products-grid">
            <!-- Product Cards 1-6 -->
            <div class="product-card fade-in">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>

        <div class="shop-section-right">
            <div class="shop-section-image">
                <h2>Daily Wear</h2>
                <button class="shop-discover-btn">DISCOVER</button>
            </div>
        </div>
    </section>

    <!-- Premium Section - LEFT LAYOUT -->
    <section class="shop-products-section shop-layout-left fade-in" id="premium">
        <div class="shop-section-left">
            <div class="shop-section-image">
                <h2>Premium</h2>
                <button class="shop-discover-btn" onclick="window.location.href='Discover.php'">DISCOVER</button>
            </div>
        </div>

        <div class="shop-products-grid">
            <!-- Product Cards 1-6 -->
            <div class="product-card fade-in">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <div class="product-card">
                <div class="shop-product-image">

                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>
    </section>

    
        <?php include '../components/footer.php'; ?>
        <script src="../assets/js/shopAnimations.js"></script>
        <script src="../assets/js/script.js"></script>
        <script src="../assets/js/HomepageAnimations.js"></script>
</body>
</html>