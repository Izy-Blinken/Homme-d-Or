<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>New Arrival</title>
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
        <!-- TABS -->
        <div class="tabs">
        <button class="tab active" onclick="showPage('page1')">New Arrivals</button>
        <button class="tab" onclick="showPage('page2')">Sales</button>
        <button class="tab" onclick="showPage('page3')">Top Picks</button>
        <button class="tab" onclick="showPage('page4')">Daily Wear</button>
        <button class="tab" onclick="showPage('page5')">Premium</button>
        </div>

        <!-- PAGE 1: NEW ARRIVALS -->
        <div class="page active" id="page1">
            <!-- Hero Section -->
            <div class="hero">
                <div class="hero-bg-effects"></div>
                <div class="hero-content">
                <div class="hero-label">Homme D'or — 2025</div>
                <h1 class="hero-title">New <span>Arrivals</span></h1>
                <div class="hero-line"></div>
                <p class="hero-text">Fresh introductions to our fragrance collection</p>
                
                </div>
                <div class="hero-decoration">
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <span class="filter-label">Filter:</span>
                <button class="filter-chip active">All</button>
                <button class="filter-chip">Woody</button>
                <button class="filter-chip">Oud</button>
                <button class="filter-chip">Citrus</button>
            </div>

            <!-- Section Header -->
            <div class="section-header">
                <div>
                <div class="section-label">Just Landed</div>
                <h2 class="section-title">Fresh <span>Introductions</span></h2>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="product-grid">
                <!-- Product Card 1 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>


                <!-- Product Card 2 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>

                <!-- Product Card 5 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>


                <!-- Product Card 6 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>


                <!-- Product Card 7 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>


                <!-- Product Card 8 -->
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                        
                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                    </div>
                    <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                        ADD TO CART
                    </button>
                    <div class="shop-product-info">
                        <h3 class="shop-product-title">Golden Night</h3>
                        <p class="shop-product-price" >₱1,800</p>
                    </div>
                </div>

            </div>
        </div>

       
        <!-- PAGE 2: SALES -->
        <div class="page" id="page2">
        <div class="hero sale-hero">
            <div class="hero-bg-effects"></div>
            <div class="hero-content">
            <div class="hero-label">Limited Time</div>
            <h1 class="hero-title">Exclusive <span>Sales</span></h1>
            <div class="hero-line"></div>
            <p class="hero-text">Exceptional fragrances at extraordinary prices</p>
            </div>
        </div>

        <!-- Sale Banner -->
        <div class="sale-banner">
            <div class="sale-info">
            <h3>Flash Sale — Up to 40% Off</h3>
            <p>While stocks last</p>
            </div>
            <div class="countdown">
            <div class="countdown-block">
                <span class="countdown-num" id="hours">08</span>
                <span class="countdown-label">Hours</span>
            </div>
            <span class="countdown-sep">:</span>
            <div class="countdown-block">
                <span class="countdown-num" id="mins">34</span>
                <span class="countdown-label">Mins</span>
            </div>
            <span class="countdown-sep">:</span>
            <div class="countdown-block">
                <span class="countdown-num" id="secs">22</span>
                <span class="countdown-label">Secs</span>
            </div>
            </div>
        </div>

        <div class="section-header">
            <div>
            <div class="section-label">On Sale</div>
            <h2 class="section-title">Featured <span >Deals</span></h2>
            </div>
        </div>

        <div class="product-grid">
            <!-- Product Card 1 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>
        </div>

        <!-- PAGE 3: TOP PICKS 
        <div class="page" id="page3">
        <div class="hero">
            <div class="hero-bg-effects"></div>
            <div class="hero-content">
            <div class="hero-label">Community Favourites</div>
            <h1 class="hero-title">Top <span>Picks</span></h1>
            <div class="hero-line"></div>
            <p class="hero-text">Most beloved fragrances</p>
            </div>
        </div>

        <div class="section-header">
            <div>
            <div class="section-label">Ranked</div>
            <h2 class="section-title">Best <span>Sellers</span></h2>
            </div>
        </div>

         Ranking Layout 
        <div class="rank-layout">
             Left Side 
            <div class="rank-side">
            <div class="rank-item">
                <div class="rank-number">#2</div>
                <div class="rank-thumb"></div>
                <div class="rank-info">
                <div class="rank-name">Product Name</div>
                <div class="rank-price">₱2,800</div>
                </div>
            </div>
            <div class="rank-item">
                <div class="rank-number">#4</div>
                <div class="rank-thumb"></div>
                <div class="rank-info">
                <div class="rank-name">Product Name</div>
                <div class="rank-price">₱2,200</div>
                </div>
            </div>
            </div>

             Center Featured
            <div class="rank-featured">
            <div class="featured-image">
                <div class="featured-rank">#1</div>
                <div class="image-placeholder large"></div>
            </div>
            <div class="featured-body">
                <div class="card-brand">Homme D'or — #1</div>
                <div class="card-name large">Featured Product</div>
                <div class="card-tags">
                <span class="tag">Tag1</span>
                <span class="tag">Tag2</span>
                </div>
                <p class="card-desc">Description here</p>
                <div class="card-price large">₱3,200</div>
                <button class="card-btn">Add to Cart</button>
            </div>
            </div>

            Right Side 
            <div class="rank-side">
            <div class="rank-item">
                <div class="rank-number">#3</div>
                <div class="rank-thumb"></div>
                <div class="rank-info">
                <div class="rank-name">Product Name</div>
                <div class="rank-price">₱1,950</div>
                </div>
            </div>
            <div class="rank-item">
                <div class="rank-number">#5</div>
                <div class="rank-thumb"></div>
                <div class="rank-info">
                <div class="rank-name">Product Name</div>
                <div class="rank-price">₱1,800</div>
                </div>
            </div>
            </div>
        </div>
        </div>
        -->

        <!-- PAGE 4: DAILY WEAR -->
        <div class="page" id="page4">
        <div class="hero">
            <div class="hero-bg-effects"></div>
            <div class="hero-content">
            <div class="hero-label">Everyday</div>
            <h1 class="hero-title">Daily <span>Wear</span></h1>
            <div class="hero-line"></div>
            <p class="hero-text">Refined fragrances for everyday</p>
            </div>
        </div>

        <div class="section-header">
            <div>
            <div class="section-label">Occasions</div>
            <h2 class="section-title">Choose Your <span>Moment</span></h2>
            </div>
        </div>

        <!-- Occasion Grid -->
        <div class="occasion-grid">
            <div class="occasion-card">
                <div class="occasion-name">Office</div>
                <div class="occasion-count">8 Fragrances</div>
            </div>    <div class="occasion-card">
                <div class="occasion-name">Casual</div>
                <div class="occasion-count">12 Fragrances</div>
            
            </div>
                <div class="occasion-card">
                <div class="occasion-name">Sport</div>
                <div class="occasion-count">6 Fragrances</div>
            </div>
                <div class="occasion-card">
                <div class="occasion-name">Date Night</div>
                <div class="occasion-count">9 Fragrances</div>
            </div>
        </div>

        <div class="section-header">
            <div>
            <div class="section-label">Essentials</div>
            <h2 class="section-title">Daily <span>Collection</span></h2>
            </div>
        </div>

        <div class="product-grid">
            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>
        </div>

        <!-- PAGE 5: PREMIUM -->
        <div class="page" id="page5">
        <div class="hero premium-hero">
            <div class="hero-bg-effects"></div>
            <div class="hero-content">
            <div class="hero-label">Exclusive</div>
            <h1 class="hero-title">Premium <span>Collection</span></h1>
            <div class="hero-line"></div>
            <p class="hero-text">Masterpieces for the discerning</p>
            </div>
        </div>

        <!-- Feature Ribbon 
        <div class="feature-ribbon">
            <div class="feature-item">
            <div class="feature-icon">◆</div>
            <div>
                <div class="feature-title">Premium Ingredients</div>
                <div class="feature-sub">World's finest</div>
            </div>
            </div>
            <div class="feature-item">
            <div class="feature-icon">◆</div>
            <div>
                <div class="feature-title">Long-Lasting</div>
                <div class="feature-sub">12-24 hours</div>
            </div>
            </div>
            <div class="feature-item">
            <div class="feature-icon">◆</div>
            <div>
                <div class="feature-title">Award Winning</div>
                <div class="feature-sub">2024 Awards</div>
            </div>
            </div>
        </div>
        -->

        <div class="section-header">
            <div>
            <div class="section-label">Haute</div>
            <h2 class="section-title">Prestige <span>Masterpieces</span></h2>
            </div>
        </div>

        <div class="product-grid premium-grid">
            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="product-card">
                <div class="product-card-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                    
                    <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                </div>
                <button class="add-to-cart-btn" onclick="showGeneralToast('Added to cart!', 'info')">
                    ADD TO CART
                </button>
                <div class="shop-product-info">
                    <h3 class="shop-product-title">Golden Night</h3>
                    <p class="shop-product-price" >₱1,800</p>
                </div>
            </div>
        </div>
        </div>

    <script src="../assets/js/shopPages.js"></script>
    <div id="generalToast" class="generalToast"></div>
    <script src="../assets/js/script.js"></script>

    <script src="../assets/js/HomepageAnimations.js"></script>
    <?php include '../components/footer.php'; ?>
</body>
</html>