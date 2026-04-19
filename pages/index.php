<?php
session_start(); 
include '../backend/db_connect.php';
include '../backend/get_products_by_category.php';
$productsByCategory = getProductsByCategory($conn);

$newArrivals = $productsByCategory['New Arrivals'] ?? [];

$sql = "SELECT p.product_id, p.product_name, p.price, p.discounted_price,
               p.product_status, pi.image_url,
               COALESCE(ROUND(AVG(pr.rating), 1), 0) AS avg_rating,
               COUNT(pr.review_id) AS review_count
        FROM products p
        LEFT JOIN product_images pi 
            ON pi.product_id = p.product_id AND pi.is_primary = 1
        LEFT JOIN product_reviews pr ON pr.product_id = p.product_id
        GROUP BY p.product_id, p.product_name, p.price, p.discounted_price, p.product_status, pi.image_url
        ORDER BY p.created_at DESC";

$allProducts = [];
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $allProducts[] = $row;
}
$wishlistedIds = [];
if (!empty($_SESSION['user_id'])) {
    $wl_result = $conn->query("SELECT product_id FROM wishlist WHERE user_id = " . intval($_SESSION['user_id']));
    while ($wl_row = $wl_result->fetch_assoc()) {
        $wishlistedIds[] = $wl_row['product_id'];
    }
}

// Fetch reviews for testimonials
$reviews_query = "
    SELECT pr.rating, pr.comment, u.fname, u.lname, pr.created_at
    FROM product_reviews pr
    JOIN users u ON pr.user_id = u.user_id
    ORDER BY pr.created_at DESC
    LIMIT 6
";
$reviews_result = $conn->query($reviews_query);
$testimonials = [];
if ($reviews_result) {
    while ($row = $reviews_result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homme d'Or - Home</title>
        
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

        <!--Steps from starting to saving: 
        terminal -> git pull -> then work na -> (if save na:) git add . -> git commit -m "describe what you changed here pero mas better kung i-comment din like this" -> git pull -> then git push
        
        
        done: 
        1. review and cancel order modal
        2. profile "view all" content scrollable
        3. Product links
        4. Toast messages after successful/failed operations
        5. Verification code for sign up
        6. Order placed successfully gawin modal nlang (yung orderAgain.php)
        7. Fade in and out animation (except sa profile page)

        Things to polish pa:
        
        1. Consistent design
        2. Animations
        3. Responsive pages
        4. Review page (for product na mismo)
        5. Terms and conditions
        6. *Pinag-iisipan q pa* Yung mga discover buttons sa shop page lagyan ng individual page
        7. back buttons
        8. UI for order history for individual products
        9. cart - selecting specific products
        
        -->
        
        <?php include '../components/header.php'; ?>
        
        <!-- PERFUME HERO SECTION -->
        <section id="perfume-hero">

            <video id="hero-video-bg" autoplay muted loop playsinline>
            <source src="../assets/videos/sample1.mp4" type="video/mp4">
            Your browser does not support the video tag.
            </video>

            <div class="hero-content">
                
                
                <div class="hero-center-image">
                    <div class="center-image-wrapper">
                        <video autoplay loop muted playsinline>
                            <source src="../assets/videos/perfumeLoop.webm" type="video/webm">
                        </video>
                    </div>
                </div>
                
                <div class="hero-info-card">
                    <div class="info-card-wrapper">
                        
                        <img src="../assets/images/brand_images/prodLogo.png" alt="Product Detail">
                        <div class="info-card-content">
                            <h3>Signature Scent</h3>
                            <p>Experience the essence of luxury with our exclusive fragrance collection.</p>
                            <a href="shop.php" class="info-card-link">
                                Learn More <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="hero-details">
                    <div class="details-list">
                        <ul>
                            <li><i class="fa-solid fa-flask"></i> PREMIUM INGREDIENTS</li>
                            <li><i class="fa-solid fa-droplet"></i> LONG-LASTING SCENT</li>
                            <li><i class="fa-solid fa-star"></i> AWARD WINNING</li>
                            <li><i class="fa-solid fa-award"></i> LIMITED EDITION</li>
                        </ul>
                    </div>
                </div>
                
                <div class="hero-cta">
                    <a href="shop.php" class="hero-btn btn-primary">Buy Now</a>
                    <a href="shop.php" class="hero-btn btn-secondary">Learn More</a>
                </div>
                
            </div>
        </section>


       <div class="carousel-products-wrapper">

           <section class="carousel-section fade-in">

               <div class="carousel-container">

                   <div class="carousel-wrapper">

                       <div class="carousel-track" id="carouselTrack">
                           <div class="carousel-slide fade-">
                               <img src="../assets/images/brand_images/perfsamp.jpg" alt="Featured Perfume 1">
                           </div>

                           <div class="carousel-slide">
                               <img src="../assets/images/brand_images/mensperf.jpg" alt="Featured Perfume 2">
                           </div>

                           <div class="carousel-slide">
                               <img src="../assets/images/brand_images/elegperf.jpg" alt="Featured Perfume 3">
                           </div>
                       </div>
                       
                       <button class="carousel-btn next" onclick="moveCarousel(1)">
                           <i class="fa-solid fa-play"></i>
                       </button>
                       
                       <div class="carousel-dots" id="carouselDots"></div>
                   </div>

               </div>

           </section>
        

           <!-- PRODUCTS SECTION -->
           
            <section class="products-section">
                <div class="products-container">

                    <div class="products-carousel-wrapper">
                        <button class="carousel-nav-btn prev" id="featPrev"><i class="fas fa-chevron-left"></i></button>
                        
                        <div class="products-scroll-area" id="featScrollArea">
                            <?php
                            $sql = "
                                SELECT p.product_id, p.product_name, p.price, p.discounted_price,
                                    p.product_status, pi.image_url,
                                    COALESCE(ROUND(AVG(pr.rating), 1), 0) AS avg_rating,
                                    COUNT(pr.review_id) AS review_count
                                FROM products p
                                LEFT JOIN product_images pi 
                                    ON pi.product_id = p.product_id AND pi.is_primary = 1
                                LEFT JOIN product_reviews pr ON pr.product_id = p.product_id
                                GROUP BY p.product_id, p.product_name, p.price, p.discounted_price, p.product_status, pi.image_url
                                ORDER BY p.created_at DESC
                            ";
                            
                            $result = $conn->query($sql);
                            while ($product = $result->fetch_assoc()):
                                $id = $product['product_id'];
                                $name = htmlspecialchars($product['product_name']);
                                $price = number_format($product['price'], 2);
                                $status = $product['product_status'];
                                $imgSrc = $product['image_url'] 
                                        ? '../assets/images/products/' . htmlspecialchars($product['image_url'])
                                        : '../assets/images/brand_images/nocturne.png';
                                $soldOut = ($status === 'out-of-stock');
                            ?>
                            
                            <div class="product-card">
                                <div class="product-card-image">
                                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                                    <?php if ($soldOut): ?>
                                        <div class="sold-out-label">SOLD OUT</div>
                                    <?php else: ?>
                                        <?php if (!empty($_SESSION['user_id'])): 
                                            $isWishlisted = in_array($id, $wishlistedIds);
                                        ?>
                                            <button class="wishlist-btn <?= $isWishlisted ? 'wishlisted' : '' ?>"
                                                    id="wishlist-card-<?= $id ?>"
                                                    onclick="toggleCardWishlist(<?= $id ?>, this)"
                                                    style="color: <?= $isWishlisted ? '#c9a961' : '#fff' ?>">
                                                <i class="<?= $isWishlisted ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="wishlist-btn" onclick="openLoginModal(); showGeneralToast('Please log in to save to your Wishlist!', 'info');"><i class="fa-regular fa-heart"></i></button>
                                        <?php endif; ?>

                                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php?id=<?= $id ?>'">Quick View</button>
                                    <?php endif; ?>
                                </div>

                                <?php if ($soldOut): ?>
                                    <button class="add-to-cart-btn" disabled>ADD TO CART</button>
                                <?php else: ?>
                                    <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['guest_id'])): ?>
                                        <button class="add-to-cart-btn" onclick="addToCart(<?= $id ?>)">ADD TO CART</button>
                                    <?php else: ?>
                                        <button class="add-to-cart-btn" onclick="openLoginModal(); showGeneralToast('Please login or continue as guest to shop.', 'error');">ADD TO CART</button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="shop-product-info">
                                    <h3 class="shop-product-title"><?= $name ?></h3>
                                    <p class="shop-product-price">₱<?= $price ?></p>
                                    <div class="product-card-rating" style="display:flex; align-items:center; gap:4px; margin-top:6px;">
                                        <?php
                                        $avgRating   = isset($product['avg_rating'])   ? (float)$product['avg_rating']  : 0;
                                        $reviewCount = isset($product['review_count']) ? (int)$product['review_count']   : 0;
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($avgRating >= $i): ?>
                                                <i class="fa-solid fa-star" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php elseif ($avgRating >= $i - 0.5): ?>
                                                <i class="fa-solid fa-star-half-stroke" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php endif;
                                        endfor; ?>
                                        <?php if ($reviewCount > 0): ?>
                                            <span style="color:#aaa; font-size:0.72rem; margin-left:4px;"><?= $avgRating ?> (<?= $reviewCount ?>)</span>
                                        <?php else: ?>
                                            <span style="color:#aaa; font-size:0.72rem; margin-left:4px;">No reviews yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>

                        <button class="carousel-nav-btn next" id="featNext"><i class="fas fa-chevron-right"></i></button>
                        </div> <div class="line-indicators" id="featIndicators"></div>
                    </div>

                </div>
            </section>
       </div>

    <!-- BRAND LOGOS -->
    <section class="brands-section">
    <div class="brands-scroll-container">
        <div class="brands-track fade-in">
            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 1">
                </div>
                
            </div>
            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 2">
                </div>
                
            </div>
            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 3">
                </div>
                
            </div>
            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 4">
                </div>
                
            </div>
            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 5">
                </div>
                
            </div>

            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 1">
                </div>
                
            </div>
            <div class="brand-logo-item">
                <div class="brand-logo">
                    <img src="../assets/images/brand_images/invprodLogo.png" alt="Brand 2">
                </div>
                
            </div>
        </div>
    </div>
    </section>

    <div class="promo-arrivals-wrapper">
        <section class="promo-cards-section fade-in">
            <div class="promo-cards-container">
                
                <!-- Card 1 -->
                <div class="promo-card">
                    <div class="promo-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Holiday Gifts">
                    </div>
                    <div class="promo-content">
                        <h3>Holiday gifts selection</h3>
                        <p>Discover the perfect gift for the man who deserves the finest. Our Holiday Gift Selection brings together an exclusive range of men's fragrances — bold, sophisticated, and unforgettable. From warm woody notes to fresh citrus bursts, each scent is crafted to leave a lasting impression. This holiday season, give more than a gift — give an experience he'll carry with him every day.</p>
                        <a href="shop.php" class="promo-link">DISCOVER</a>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="promo-card">
                    <div class="promo-image">
                        <img src="../assets/images/brand_images/elegperf.jpg" alt="Exclusive Offers">
                    </div>
                    <div class="promo-content">
                        <h3>Exclusive offers</h3>
                        <p>Unwrap the season's most irresistible deals with our Holiday Exclusive Offers. For a limited time, enjoy special bundles, complimentary gift wrapping, and unbeatable prices on our finest men's fragrances. Whether you're treating yourself or someone special, now is the perfect moment to indulge in luxury without compromise. Don't miss out — these offers are as rare as the scents themselves.</p>
                        <a href="shop.php" class="promo-link">DISCOVER</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- NEW ARRIVALS SECTION -->
        <section class="new-arrivals-section fade-in">
            <div class="new-arrivals-container">
                <div class="section-header">
                    <div class="header-line"></div>
                    <h2>New Arrivals</h2>
                    <div class="header-line"></div>
                </div>
                
                <div class="products-carousel-wrapper">
                    <button class="carousel-nav-btn prev" id="newArrPrev"><i class="fas fa-chevron-left"></i></button>
                    
                    <div class="products-scroll-area" id="newArrScrollArea">
                        <?php if (empty($newArrivals)): ?>
                            <p style="color:#ccc; padding: 1rem;">No new arrivals yet.</p>
                        <?php else: ?>
                            <?php 
                                foreach ($newArrivals as $product):
                                $id = $product['product_id'];
                                $name = htmlspecialchars($product['product_name']);
                                $price = number_format($product['price'], 2);
                                $status = $product['product_status'];
                                $imgSrc = $product['image_url']
                                        ? '../assets/images/products/' . htmlspecialchars($product['image_url'])
                                        : '../assets/images/brand_images/nocturne.png';
                                $soldOut = ($status === 'out-of-stock');
                            ?>
                            <div class="product-card">
                                <div class="product-card-image">
                                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                                    <?php if ($soldOut): ?>
                                        <div class="sold-out-label">SOLD OUT</div>
                                    <?php else: ?>
                                        <?php if (!empty($_SESSION['user_id'])): 
                                                $isWishlisted = in_array($id, $wishlistedIds); ?>
                                            <button class="wishlist-btn <?= $isWishlisted ? 'wishlisted' : '' ?>"
                                                    id="wishlist-card-<?= $id ?>"
                                                    onclick="toggleCardWishlist(<?= $id ?>, this)"
                                                    style="color: <?= $isWishlisted ? '#c9a961' : '#fff' ?>">
                                                <i class="<?= $isWishlisted ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="wishlist-btn" onclick="openLoginModal(); showGeneralToast('Please log in to save to your Wishlist!', 'info');"><i class="fa-regular fa-heart"></i></button>
                                        <?php endif; ?>

                                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php?id=<?= $id ?>'">Quick View</button>
                                    <?php endif; ?>
                                </div>

                                <?php if ($soldOut): ?>
                                    <button class="add-to-cart-btn" disabled>ADD TO CART</button>
                                <?php else: ?>
                                    <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['guest_id'])): ?>
                                        <button class="add-to-cart-btn" onclick="addToCart(<?= $id ?>)">ADD TO CART</button>
                                    <?php else: ?>
                                        <button class="add-to-cart-btn" onclick="openLoginModal(); showGeneralToast('Please login or continue as guest to shop.', 'error');">ADD TO CART</button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="shop-product-info">
                                    <h3 class="shop-product-title"><?= $name ?></h3>
                                    <p class="shop-product-price">₱<?= $price ?></p>
                                    <div class="product-card-rating" style="display:flex; align-items:center; gap:4px; margin-top:6px;">
                                        <?php
                                        $avgRating   = isset($product['avg_rating'])   ? (float)$product['avg_rating']  : 0;
                                        $reviewCount = isset($product['review_count']) ? (int)$product['review_count']   : 0;
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($avgRating >= $i): ?>
                                                <i class="fa-solid fa-star" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php elseif ($avgRating >= $i - 0.5): ?>
                                                <i class="fa-solid fa-star-half-stroke" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php endif;
                                        endfor; ?>
                                        <?php if ($reviewCount > 0): ?>
                                            <span style="color:#aaa; font-size:0.72rem; margin-left:4px;"><?= $avgRating ?> (<?= $reviewCount ?>)</span>
                                        <?php else: ?>
                                            <span style="color:#aaa; font-size:0.72rem; margin-left:4px;">No reviews yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button class="carousel-nav-btn next" id="newArrNext"><i class="fas fa-chevron-right"></i></button>
                    </div> <div class="line-indicators" id="newArrIndicators"></div>
                </div>
                
            </div>
        </section>
    </div>
    
    <div class="promo-arrivals-wrapper-2">
        <section class="promo-cards-section-2 fade-in">
            <div class="promo-cards-container-2">
                <!-- Card 1 -->
                <div class="promo-card">
                    <div class="promo-image">
                        <img src="../assets/images/brand_images/nocturne.png" alt="Holiday Gifts">
                    </div>
                    <div class="promo-content">
                        <h3>Engraving Compliment</h3>
                        <p>Leave your mark with something truly personal. Our Engraving Compliment service lets you add a custom message or name to his favorite fragrance bottle, turning a luxurious scent into a timeless keepsake. Perfect for anniversaries, birthdays, or the holiday season — because some gifts deserve to be remembered forever. Make it his, make it meaningful, make it unforgettable.</p>
                        <a href="#" class="promo-link">DISCOVER</a>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="promo-card">
                    <div class="promo-image">
                        <img src="../assets/images/brand_images/elegperf.jpg" alt="Exclusive Offers">
                    </div>
                    <div class="promo-content">
                        <h3>Art of Gifting</h3>
                        <p>Giving a gift is more than just a gesture — it is an art. The Art of Gifting celebrates the thoughtfulness behind every carefully chosen fragrance, beautifully presented in elegant packaging designed to impress from the very first glance. Each bottle tells a story, each scent evokes an emotion, and every detail is crafted with intention. Because the perfect gift is not just something you give — it is something they will never forget.</p>
                        <a href="#" class="promo-link">DISCOVER</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- NEW ARRIVALS SECTION -->
       <section class="new-arrivals-section-2 fade-in">
            <div class="new-arrivals-container-2">
                <div class="section-header">
                    <div class="header-line"></div>
                    <h2>Our Selection</h2>
                    <div class="header-line"></div>
                </div>
                
                <div class="products-carousel-wrapper">
                    <button class="carousel-nav-btn prev" id="selPrev"><i class="fas fa-chevron-left"></i></button>
                    
                    <div class="products-scroll-area" id="selScrollArea">
                        <?php if (empty($allProducts)): ?>
                            <p style="color:#ccc; padding: 1rem;">No products yet.</p>
                        <?php else: ?>
                            <?php 
                            $count2 = 0;
                            foreach ($allProducts as $product):
                                if ($count2 >= 8) break; // Limits to 8 items
                                $count2++;
                                $id = $product['product_id'];
                                $name = htmlspecialchars($product['product_name']);
                                $price = number_format($product['price'], 2);
                                $status = $product['product_status'];
                                $imgSrc = $product['image_url']
                                        ? '../assets/images/products/' . htmlspecialchars($product['image_url'])
                                        : '../assets/images/brand_images/nocturne.png';
                                $soldOut = ($status === 'out-of-stock');
                            ?>
                            <div class="product-card">
                                <div class="product-card-image">
                                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                                    <?php if ($soldOut): ?>
                                        <div class="sold-out-label">SOLD OUT</div>
                                    <?php else: ?>
                                        <?php if (!empty($_SESSION['user_id'])): 
                                                $isWishlisted = in_array($id, $wishlistedIds); ?>
                                            <button class="wishlist-btn <?= $isWishlisted ? 'wishlisted' : '' ?>"
                                                    id="wishlist-card-<?= $id ?>"
                                                    onclick="toggleCardWishlist(<?= $id ?>, this)"
                                                    style="color: <?= $isWishlisted ? '#c9a961' : '#fff' ?>">
                                                <i class="<?= $isWishlisted ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="wishlist-btn" onclick="openLoginModal(); showGeneralToast('Please log in to save to your Wishlist!', 'info');"><i class="fa-regular fa-heart"></i></button>
                                        <?php endif; ?>

                                        <button class="quick-view-btn" onclick="window.location.href='productDetails.php?id=<?= $id ?>'">Quick View</button>
                                    <?php endif; ?>
                                </div>

                                <?php if ($soldOut): ?>
                                    <button class="add-to-cart-btn" disabled>ADD TO CART</button>
                                <?php else: ?>
                                    <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['guest_id'])): ?>
                                        <button class="add-to-cart-btn" onclick="addToCart(<?= $id ?>)">ADD TO CART</button>
                                    <?php else: ?>
                                        <button class="add-to-cart-btn" onclick="openLoginModal(); showGeneralToast('Please login or continue as guest to shop.', 'error');">ADD TO CART</button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="shop-product-info">
                                    <h3 class="shop-product-title"><?= $name ?></h3>
                                    <p class="shop-product-price">₱<?= $price ?></p>
                                    <div class="product-card-rating" style="display:flex; align-items:center; gap:4px; margin-top:6px;">
                                        <?php
                                        $avgRating   = isset($product['avg_rating'])   ? (float)$product['avg_rating']  : 0;
                                        $reviewCount = isset($product['review_count']) ? (int)$product['review_count']   : 0;
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($avgRating >= $i): ?>
                                                <i class="fa-solid fa-star" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php elseif ($avgRating >= $i - 0.5): ?>
                                                <i class="fa-solid fa-star-half-stroke" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star" style="color:#c9a961; font-size:0.75rem;"></i>
                                            <?php endif;
                                        endfor; ?>
                                        <?php if ($reviewCount > 0): ?>
                                            <span style="color:#aaa; font-size:0.72rem; margin-left:4px;"><?= $avgRating ?> (<?= $reviewCount ?>)</span>
                                        <?php else: ?>
                                            <span style="color:#aaa; font-size:0.72rem; margin-left:4px;">No reviews yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button class="carousel-nav-btn next" id="selNext"><i class="fas fa-chevron-right"></i></button>
                    </div> <div class="line-indicators" id="selIndicators"></div>
                </div>
            </div>
        </section>
    </div>

    <section class="special-promo-section fade-in">
        <div class="special-promo-container">
            <h2 class="special-promo-title">SPECIAL PROMO 20% OFF THIS HOLIDAY SEASON</h2>
            <button class="special-promo-btn">Buy Now</button>
        </div>
    </section>

    <section class="testimonials-section fade-in">
        <div class="testimonials-container">
            <div class="testimonials-header">
                <h2>Hear From Our Customers</h2>
                <div class="testimonial-nav-buttons">
                    <button class="testimonial-nav-btn prev" id="testimonialPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="testimonial-nav-btn next" id="testimonialNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="testimonials-slider">
                <div class="testimonials-track" id="testimonialsTrack">
                    <?php if (empty($testimonials)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #aaa;">
                            <p style="font-size: 1.2rem; margin-bottom: 10px;">Nothing to review yet</p>
                            <p style="font-size: 0.9rem;">Be the first to share your experience with us!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($testimonials as $review): ?>
                        <div class="testimonial-card">
                            <div class="testimonial-stars">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <?php if ($i < $review['rating']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="fas fa-star" style="color: #ddd;"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <p class="testimonial-text"><?= htmlspecialchars($review['comment']); ?></p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <?= strtoupper(substr($review['fname'], 0, 1)); ?>
                                </div>
                                <span class="author-name"><?= htmlspecialchars($review['fname'] . ' ' . $review['lname']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    
    <div id="generalToast" class="generalToast"></div>

    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/HomepageAnimations.js"></script>

    <script>
    function toggleCardWishlist(productId, btn) {
    const icon = btn.querySelector('i');
    fetch('../backend/add_to_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'added') {
            icon.classList.replace('fa-regular', 'fa-solid');
            btn.style.color = '#c9a961';
            btn.title = 'Remove from Wishlist';
            showGeneralToast('Added to wishlist!', 'success'); // ← add this
        } else if (data.status === 'removed') {
            icon.classList.replace('fa-solid', 'fa-regular');
            btn.style.color = '#fff';
            btn.title = 'Add to Wishlist';
            showGeneralToast('Removed from wishlist.', 'info'); // ← add this
        } else {
            showGeneralToast(data.message, 'error'); // ← add this
        }
    })
    .catch(err => console.error('Wishlist error:', err));
}

    function addToCartFromCard(productId, btn) {
        const original = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Adding...';
        fetch('../backend/add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'product_id=' + productId + '&quantity=1'
        })
        .then(res => res.json())
        .then(data => {
            btn.textContent = data.status === 'success' ? 'Added!' : 'Error';
            setTimeout(() => {
                btn.textContent = original;
                btn.disabled = false;
            }, 1500);
        })
        .catch(() => {
            btn.textContent = original;
            btn.disabled = false;
        });
    }
    </script>


    <?php include '../components/footer.php'; ?>

    </body>
        
</html>