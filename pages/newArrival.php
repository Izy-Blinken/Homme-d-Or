<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once '../backend/db_connect.php';
$identity = getCurrentUserId();
$isLoggedIn = ($identity['type'] === 'user_id');
$isGuest = ($identity['type'] === 'guest_id');
?>
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
        <div class="tabs">
        <button class="tab active" onclick="showPage('page1')">New Arrivals</button>
        <button class="tab" onclick="showPage('page2')">Top Picks</button>
        <button class="tab" onclick="showPage('page3')">Sales</button>
        <button class="tab" onclick="showPage('page4')">Daily Wear</button>
        <button class="tab" onclick="showPage('page5')">Premium</button>
        </div>

        <div class="page active" id="page1">
            <div class="hero">
                <div class="hero-bg-effects"></div>
                <div class="hero-content">
                <div class="hero-label">Homme D'or — 2026</div>
                <h1 class="hero-title">New <span>Arrivals</span></h1>
                <div class="hero-line"></div>
                <p class="hero-text">Fresh introductions to our fragrance collection</p>
                </div>
                <div class="hero-decoration"></div>
            </div>

            <div class="shop-controls">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search new arrivals..." autocomplete="off">
                    <div class="search-suggestions"></div>
                </div>
            </div>

            <div class="section-header">
                <div>
                <div class="section-label">Just Landed</div>
                <h2 class="section-title">Fresh <span>Introductions</span></h2>
                </div>
            </div>

            <div class="product-grid">
                
                <div class="product-card" data-category="woody" data-name="Golden Night">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Golden Night" class="image-placeholder">
                        <div class="badge">New</div>
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Golden Night</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Evening</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,800</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="citrus" data-name="Morning Citrus">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Morning Citrus" class="image-placeholder">
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Morning Citrus</h3>
                        <div class="card-tags"><span class="tag">Citrus</span><span class="tag">Fresh</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,500</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="oud" data-name="Oud Majesty">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Oud Majesty" class="image-placeholder">
                        <div class="badge sale-badge">-15%</div>
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Oud Majesty</h3>
                        <div class="card-tags"><span class="tag">Oud</span><span class="tag">Premium</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,800</span><span class="card-price-old">₱3,300</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Midnight Timber">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Midnight Timber" class="image-placeholder">
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Midnight Timber</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Intense</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,100</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="spicy" data-name="Desert Gold">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Desert Gold" class="image-placeholder">
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Desert Gold</h3>
                        <div class="card-tags"><span class="tag">Oud</span><span class="tag">Spicy</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,400</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                 <div class="product-card" data-category="fresh" data-name="Ocean Breeze">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Ocean Breeze" class="image-placeholder">
                        <div class="badge">New</div>
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Ocean Breeze</h3>
                        <div class="card-tags"><span class="tag">Citrus</span><span class="tag">Aquatic</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,650</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Sapphire Sky">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Sapphire Sky" class="image-placeholder">
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Sapphire Sky</h3>
                        <div class="card-tags"><span class="tag">Citrus</span><span class="tag">Airy</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,750</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Crimson Leather">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Crimson Leather" class="image-placeholder">
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Crimson Leather</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Leather</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,200</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Velvet Iris">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Velvet Iris" class="image-placeholder">
                        <div class="badge">New</div>
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Velvet Iris</h3>
                        <div class="card-tags"><span class="tag">Floral</span><span class="tag">Woody</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,900</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Tobacco Whisper">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Tobacco Whisper" class="image-placeholder">
                    </div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div>
                        <h3 class="card-name">Tobacco Whisper</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Sweet</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,050</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

            </div>
        </div>
    <div class="page" id="page2">
            <div class="hero">
                <div class="hero-bg-effects"></div>
                <div class="hero-content">
                <div class="hero-label">Community Favourites</div>
                <h1 class="hero-title">Top <span>Picks</span></h1>
                <div class="hero-line"></div>
                <p class="hero-text">Our definitive top 10 most beloved and highly rated fragrances of the season.</p>
                </div>
            </div>

            <div class="shop-controls">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search top picks..." autocomplete="off">
                    <div class="search-suggestions"></div>
                </div>
            </div>

            <div class="section-header">
                <div>
                <div class="section-label">Ranked</div>
                <h2 class="section-title">Best <span>Sellers</span></h2>
                </div>
            </div>

            <div class="rank-layout">
                <div class="rank-side">
                    <div class="rank-item" data-category="woody" data-name="Nocturne Elite" onclick="window.location.href='productDetails.php'">
                        <div class="rank-number">#2</div>
                        <div class="rank-thumb"><img src="../assets/images/brand_images/nocturne.png" alt="Perfume" style="width:100%; height:100%; object-fit:cover; padding: 5px;"></div>
                        <div class="rank-info"><div class="rank-name">Nocturne Elite</div><div class="rank-price">₱2,800</div></div>
                    </div>
                    <div class="rank-item" data-category="woody" data-name="Cedar Mist" onclick="window.location.href='productDetails.php'">
                        <div class="rank-number">#4</div>
                        <div class="rank-thumb"><img src="../assets/images/brand_images/nocturne.png" alt="Perfume" style="width:100%; height:100%; object-fit:cover; padding: 5px;"></div>
                        <div class="rank-info"><div class="rank-name">Cedar Mist</div><div class="rank-price">₱2,200</div></div>
                    </div>
                </div>

                <div class="rank-featured" data-category="oud" data-name="Homme D'or Absolute">
                    <div class="featured-image">
                        <div class="featured-rank">#1</div>
                        <img src="../assets/images/brand_images/nocturne.png" alt="Featured Perfume" class="image-placeholder large">
                    </div>
                    <div class="featured-body">
                        <div class="card-brand">Homme D'or — Signature</div>
                        <h3 class="card-name large">Homme D'or Absolute</h3>
                        <div class="card-tags"><span class="tag">Masterpiece</span><span class="tag">Woody</span></div>
                        <p class="card-desc">Our undisputed champion. A perfect harmony of aged oud, rich vanilla, and smoky cedarwood.</p>
                        <div class="card-price large">₱3,500</div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="rank-side">
                    <div class="rank-item" data-category="floral" data-name="Silver Musk" onclick="window.location.href='productDetails.php'">
                        <div class="rank-number">#3</div>
                        <div class="rank-thumb"><img src="../assets/images/brand_images/nocturne.png" alt="Perfume" style="width:100%; height:100%; object-fit:cover; padding: 5px;"></div>
                        <div class="rank-info"><div class="rank-name">Silver Musk</div><div class="rank-price">₱2,600</div></div>
                    </div>
                    <div class="rank-item" data-category="floral" data-name="Vanilla Noir" onclick="window.location.href='productDetails.php'">
                        <div class="rank-number">#5</div>
                        <div class="rank-thumb"><img src="../assets/images/brand_images/nocturne.png" alt="Perfume" style="width:100%; height:100%; object-fit:cover; padding: 5px;"></div>
                        <div class="rank-info"><div class="rank-name">Vanilla Noir</div><div class="rank-price">₱2,100</div></div>
                    </div>
                </div>
            </div>

            <div class="section-header" style="padding-top: 0; margin-top: -20px;">
                <div>
                <div class="section-label">Honorable Mentions</div>
                <h2 class="section-title">The <span>Runners Up</span></h2>
                </div>
            </div>

            <div class="product-grid">
                <div class="product-card" data-category="fresh" data-name="Azure Depths">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Azure Depths" class="image-placeholder"><div class="badge" style="background: rgba(240, 232, 213, 0.2); color: var(--gold);">#6</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Azure Depths</h3>
                        <div class="card-tags"><span class="tag">Aquatic</span><span class="tag">Fresh</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,050</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Midnight Timber">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Midnight Timber" class="image-placeholder"><div class="badge" style="background: rgba(240, 232, 213, 0.2); color: var(--gold);">#7</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Midnight Timber</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Dark</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,950</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="spicy" data-name="Crimson Spices">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Crimson Spices" class="image-placeholder"><div class="badge" style="background: rgba(240, 232, 213, 0.2); color: var(--gold);">#8</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Crimson Spices</h3>
                        <div class="card-tags"><span class="tag">Spicy</span><span class="tag">Warm</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,850</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Vetiver Blanc">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Vetiver Blanc" class="image-placeholder"><div class="badge" style="background: rgba(240, 232, 213, 0.2); color: var(--gold);">#9</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Vetiver Blanc</h3>
                        <div class="card-tags"><span class="tag">Earthy</span><span class="tag">Clean</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,750</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="citrus" data-name="Golden Horizon">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Golden Horizon" class="image-placeholder"><div class="badge" style="background: rgba(240, 232, 213, 0.2); color: var(--gold);">#10</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Golden Horizon</h3>
                        <div class="card-tags"><span class="tag">Citrus</span><span class="tag">Amber</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,600</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>


    <div class="page" id="page3">
            <div class="hero sale-hero">
                <div class="hero-bg-effects"></div>
                <div class="hero-content">
                <div class="hero-label">Limited Time</div>
                <h1 class="hero-title">Exclusive <span>Sales</span></h1>
                <div class="hero-line"></div>
                <p class="hero-text">Exceptional fragrances at extraordinary prices</p>
                </div>
            </div>

            <div class="shop-controls">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search sales..." autocomplete="off">
                    <div class="search-suggestions"></div>
                </div>
            </div>

            <div class="sale-banner">
                <div class="sale-info">
                <h3>Flash Sale — Up to 40% Off</h3>
                <p>While stocks last</p>
                </div>
                <div class="countdown">
                <div class="countdown-block"><span class="countdown-num" id="hours">08</span><span class="countdown-label">Hours</span></div>
                <span class="countdown-sep">:</span>
                <div class="countdown-block"><span class="countdown-num" id="mins">34</span><span class="countdown-label">Mins</span></div>
                <span class="countdown-sep">:</span>
                <div class="countdown-block"><span class="countdown-num" id="secs">22</span><span class="countdown-label">Secs</span></div>
                </div>
            </div>

            <div class="section-header">
                <div>
                <div class="section-label">On Sale</div>
                <h2 class="section-title">Featured <span class="red">Deals</span></h2>
                </div>
            </div>

            <div class="product-grid">
                <div class="product-card" data-category="oud" data-name="Oud Mirage">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Oud Mirage" class="image-placeholder"><div class="badge sale-badge">-20%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Oud Mirage</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Spice</span></div>
                        <div class="card-price-row"><span class="card-price">₱2,400</span><span class="card-price-old">₱3,000</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Velvet Rose">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Velvet Rose" class="image-placeholder"><div class="badge sale-badge">-30%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Velvet Rose</h3>
                        <div class="card-tags"><span class="tag">Floral</span><span class="tag">Evening</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,750</span><span class="card-price-old">₱2,500</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="spicy" data-name="Amber Glow">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Amber Glow" class="image-placeholder"><div class="badge sale-badge">-15%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Amber Glow</h3>
                        <div class="card-tags"><span class="tag">Amber</span><span class="tag">Warm</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,700</span><span class="card-price-old">₱2,000</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="citrus" data-name="Summer Zest">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Summer Zest" class="image-placeholder"><div class="badge sale-badge">-40%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Summer Zest</h3>
                        <div class="card-tags"><span class="tag">Citrus</span><span class="tag">Fresh</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,080</span><span class="card-price-old">₱1,800</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Frost Pine">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Frost Pine" class="image-placeholder"><div class="badge sale-badge">-25%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Frost Pine</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Crisp</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,425</span><span class="card-price-old">₱1,900</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="spicy" data-name="Desert Spice">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Desert Spice" class="image-placeholder"><div class="badge sale-badge">-10%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Desert Spice</h3>
                        <div class="card-tags"><span class="tag">Spicy</span><span class="tag">Warm</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,980</span><span class="card-price-old">₱2,200</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="citrus" data-name="Neon Citrus">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Neon Citrus" class="image-placeholder"><div class="badge sale-badge">-20%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Neon Citrus</h3>
                        <div class="card-tags"><span class="tag">Citrus</span><span class="tag">Electric</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,280</span><span class="card-price-old">₱1,600</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Obsidian Wood">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Obsidian Wood" class="image-placeholder"><div class="badge sale-badge">-35%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Obsidian Wood</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Dark</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,690</span><span class="card-price-old">₱2,600</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Royal Jasmine">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Royal Jasmine" class="image-placeholder"><div class="badge sale-badge">-15%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Royal Jasmine</h3>
                        <div class="card-tags"><span class="tag">Floral</span><span class="tag">Rich</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,870</span><span class="card-price-old">₱2,200</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Lunar Musk">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Lunar Musk" class="image-placeholder"><div class="badge sale-badge">-50%</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Lunar Musk</h3>
                        <div class="card-tags"><span class="tag">Musk</span><span class="tag">Night</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,150</span><span class="card-price-old">₱2,300</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        

        <div class="page" id="page4">
            <div class="hero">
                <div class="hero-bg-effects"></div>
                <div class="hero-content">
                <div class="hero-label">Everyday</div>
                <h1 class="hero-title">Daily <span>Wear</span></h1>
                <div class="hero-line"></div>
                <p class="hero-text">Refined fragrances for your everyday routine. Subtle, lasting, and perfectly balanced.</p>
                </div>
            </div>

            <div class="shop-controls">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search everyday wear..." autocomplete="off">
                    <div class="search-suggestions"></div>
                </div>
            </div>

            <div class="section-header">
                <div>
                <div class="section-label">Essentials</div>
                <h2 class="section-title">Daily <span>Collection</span></h2>
                </div>
            </div>

            <div class="product-grid">
                <div class="product-card" data-category="fresh" data-name="Office Signature">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Office Signature" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Office Signature</h3>
                        <div class="card-tags"><span class="tag">Professional</span><span class="tag">Fresh</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,500</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Sunday Morning">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Sunday Morning" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Sunday Morning</h3>
                        <div class="card-tags"><span class="tag">Casual</span><span class="tag">Light</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,300</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="citrus" data-name="Active Sport">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Active Sport" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Active Sport</h3>
                        <div class="card-tags"><span class="tag">Sport</span><span class="tag">Citrus</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,450</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Coffee Break">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Coffee Break" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Coffee Break</h3>
                        <div class="card-tags"><span class="tag">Gourmand</span><span class="tag">Warm</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,600</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Crisp Linen">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Crisp Linen" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Crisp Linen</h3>
                        <div class="card-tags"><span class="tag">Clean</span><span class="tag">Airy</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,400</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Aqua Motion">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Aqua Motion" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Aqua Motion</h3>
                        <div class="card-tags"><span class="tag">Aquatic</span><span class="tag">Sport</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,350</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Urban Cedar">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Urban Cedar" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Urban Cedar</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Office</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,550</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Spring Bloom">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Spring Bloom" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Spring Bloom</h3>
                        <div class="card-tags"><span class="tag">Floral</span><span class="tag">Casual</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,450</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Evening Chill">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Evening Chill" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Evening Chill</h3>
                        <div class="card-tags"><span class="tag">Musk</span><span class="tag">Relax</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,500</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Silver Fern">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Silver Fern" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or</div><h3 class="card-name">Silver Fern</h3>
                        <div class="card-tags"><span class="tag">Green</span><span class="tag">Fresh</span></div>
                        <div class="card-price-row"><span class="card-price">₱1,380</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="page" id="page5">
            <div class="hero premium-hero">
                <div class="hero-bg-effects"></div>
                <div class="hero-content">
                <div class="hero-label">Exclusive</div>
                <h1 class="hero-title">Premium <span>Collection</span></h1>
                <div class="hero-line"></div>
                <p class="hero-text">Masterpieces for the discerning. Crafted with the world's rarest ingredients.</p>
                </div>
            </div>

            <div class="shop-controls">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search premium..." autocomplete="off">
                    <div class="search-suggestions"></div>
                </div>
            </div>

            <div class="feature-ribbon">
                <div class="feature-item"><div class="feature-icon"><i class="fas fa-gem"></i></div><div><div class="feature-title">Premium Ingredients</div><div class="feature-sub">World's finest</div></div></div>
                <div class="feature-item"><div class="feature-icon"><i class="fas fa-clock"></i></div><div><div class="feature-title">Long-Lasting</div><div class="feature-sub">12-24 hours</div></div></div>
                <div class="feature-item"><div class="feature-icon"><i class="fas fa-trophy"></i></div><div><div class="feature-title">Award Winning</div><div class="feature-sub">2024 Awards</div></div></div>
            </div>

            <div class="section-header">
                <div>
                <div class="section-label">Haute</div>
                <h2 class="section-title">Prestige <span>Masterpieces</span></h2>
                </div>
            </div>

            <div class="product-grid premium-grid">
                <div class="product-card" data-category="oud" data-name="Royal Oud Absolu">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Royal Oud Absolu" class="image-placeholder"><div class="badge" style="background: #e5e4e2;">Platinum</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Royal Oud Absolu</h3>
                        <div class="card-tags"><span class="tag">Pure Extrait</span><span class="tag">Intense</span></div>
                        <div class="card-price-row"><span class="card-price large">₱5,500</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="spicy" data-name="Imperial Saffron">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Imperial Saffron" class="image-placeholder"><div class="badge">Limited</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Imperial Saffron</h3>
                        <div class="card-tags"><span class="tag">Exclusive</span><span class="tag">Spicy</span></div>
                        <div class="card-price-row"><span class="card-price large">₱4,800</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Gold Extrait">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Gold Extrait" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Gold Extrait</h3>
                        <div class="card-tags"><span class="tag">Pure Perfume</span><span class="tag">Signature</span></div>
                        <div class="card-price-row"><span class="card-price large">₱6,200</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Platinum Musk">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Platinum Musk" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Platinum Musk</h3>
                        <div class="card-tags"><span class="tag">Musk</span><span class="tag">Ethereal</span></div>
                        <div class="card-price-row"><span class="card-price large">₱5,100</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="spicy" data-name="Velvet Ambergris">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Velvet Ambergris" class="image-placeholder"><div class="badge" style="background: var(--red);">Rare</div></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Velvet Ambergris</h3>
                        <div class="card-tags"><span class="tag">Amber</span><span class="tag">Marine</span></div>
                        <div class="card-price-row"><span class="card-price large">₱6,800</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Black Rose Edition">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Black Rose Edition" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Black Rose Edition</h3>
                        <div class="card-tags"><span class="tag">Floral</span><span class="tag">Dark</span></div>
                        <div class="card-price-row"><span class="card-price large">₱4,500</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="floral" data-name="Diamond Iris">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Diamond Iris" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Diamond Iris</h3>
                        <div class="card-tags"><span class="tag">Iris</span><span class="tag">Powdery</span></div>
                        <div class="card-price-row"><span class="card-price large">₱5,300</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="woody" data-name="Majestic Sandalwood">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Majestic Sandalwood" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Majestic Sandalwood</h3>
                        <div class="card-tags"><span class="tag">Woody</span><span class="tag">Creamy</span></div>
                        <div class="card-price-row"><span class="card-price large">₱4,900</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="fresh" data-name="Emerald Vetiver">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Emerald Vetiver" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Emerald Vetiver</h3>
                        <div class="card-tags"><span class="tag">Earthy</span><span class="tag">Green</span></div>
                        <div class="card-price-row"><span class="card-price large">₱4,600</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card" data-category="oud" data-name="Ruby Oud">
                    <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    <div class="card-image"><img src="../assets/images/brand_images/nocturne.png" alt="Ruby Oud" class="image-placeholder"></div>
                    <div class="card-body">
                        <div class="card-brand">Homme D'or Privé</div><h3 class="card-name large">Ruby Oud</h3>
                        <div class="card-tags"><span class="tag">Oud</span><span class="tag">Fruity</span></div>
                        <div class="card-price-row"><span class="card-price large">₱5,800</span></div>
                        <button class="card-btn" onclick="showGeneralToast('Added to cart!', 'info')">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
        
    <script src="../assets/js/shopPages.js"></script>
    <div id="generalToast" class="generalToast"></div>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/HomepageAnimations.js"></script>

    <script>
    // ── User identity passed from PHP ─────────────────────────────
    const IS_LOGGED_IN = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    const IS_GUEST = <?php echo $isGuest ? 'true' : 'false'; ?>;

    document.addEventListener('DOMContentLoaded', function () {

        // ── Wire up ALL Add to Cart buttons ───────────────────────
        document.querySelectorAll('.card-btn').forEach(function (btn) {
            // Remove the old inline onclick so we fully control it
            btn.removeAttribute('onclick');

            btn.addEventListener('click', function () {
                const card = btn.closest('.product-card');
                const productId = card ? card.dataset.productId : null;

                if (!productId || productId === '0') {
                    // Product ID not set on this card yet — show toast only
                    showGeneralToast('Added to cart!', 'info');
                    return;
                }

                const original = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Adding...';

                fetch('../backend/add_to_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'product_id=' + productId + '&quantity=1'
                })
                .then(r => r.json())
                .then(data => {
                    showGeneralToast(data.message, data.status === 'success' ? 'info' : 'error');
                    btn.textContent = data.status === 'success' ? 'Added!' : 'Error';
                    setTimeout(() => { btn.textContent = original; btn.disabled = false; }, 1500);
                })
                .catch(() => { btn.textContent = original; btn.disabled = false; });
            });
        });

        // ── Wire up ALL Wishlist heart buttons ────────────────────
        document.querySelectorAll('.wishlist-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                // Guest: prompt to register
                if (IS_GUEST) {
                    showGeneralToast('Create a free account to save your wishlist!', 'info');
                    return;
                }
                // Stranger: redirect to login
                if (!IS_LOGGED_IN) {
                    window.location.href = 'index.php?login_required=true';
                    return;
                }

                // Registered user: toggle wishlist
                const card = btn.closest('.product-card');
                const productId = card ? card.dataset.productId : null;

                if (!productId || productId === '0') {
                    showGeneralToast('Wishlist saved! (Connect product ID to persist)', 'info');
                    toggleHeartVisual(btn);
                    return;
                }

                fetch('../backend/add_to_wishlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'product_id=' + productId
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

    // Toggle heart icon filled/empty
    function toggleHeartVisual(btn, forceState) {
        const icon = btn.querySelector('i');
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