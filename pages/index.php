<?php
session_start(); // ← ADD THIS
include '../backend/db_connect.php';
include '../backend/get_products_by_category.php';
$productsByCategory = getProductsByCategory($conn);

$newArrivals = $productsByCategory['New Arrivals'] ?? [];

$sql = "SELECT p.product_id, p.product_name, p.price, p.discounted_price,
               p.product_status, pi.image_url
        FROM products p
        LEFT JOIN product_images pi
            ON pi.product_id = p.product_id AND pi.is_primary = 1
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
                
                <div class="hero-title">
                    <h1>Homme<br>d'Or</h1>
                </div>
                
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
                                    p.product_status, pi.image_url
                                FROM products p
                                LEFT JOIN product_images pi
                                    ON pi.product_id = p.product_id AND pi.is_primary = 1
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
                                        : '../assets/images/product_images/image_unavailable.png';
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
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In commodo porta mi, ut vestibulum urna eros. Nam in lacinia est, vestibulum urna eros, sagittis et mollis gravida, tincidunt a lorem.</p>
                        <a href="shop.php" class="promo-link">DISCOVER</a>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="promo-card">
                    <div class="promo-image">
                        <img src="../assets/images/brand_images/elegperf.jpg" alt="Exclusive Offers">
                    </div>
                    <div class="promo-content">
                        <h3>Xclusive offers</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In commodo porta mi, ut vestibulum urna eros. Nam in lacinia est, vestibulum urna eros, sagittis et mollis gravida, tincidunt a lorem.</p>
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
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In commodo porta mi, ut vestibulum urna eros. Nam in lacinia est, vestibulum urna eros, sagittis et mollis gravida, tincidunt a lorem.</p>
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
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In commodo porta mi, ut vestibulum urna eros. Nam in lacinia est, vestibulum urna eros, sagittis et mollis gravida, tincidunt a lorem.</p>
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
                    <!-- Testimonial Card 1 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Amazing fragrance! Lasts all day and I get compliments everywhere I go. Highly recommend this perfume to anyone looking for a signature scent."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="author-name">Sarah Johnson</span>
                        </div>
                    </div>

                    <!-- Testimonial Card 2 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Best purchase ever! The quality is outstanding and the scent is absolutely divine. Will definitely be ordering more products from this brand."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="author-name">Michael Chen</span>
                        </div>
                    </div>

                    <!-- Testimonial Card 3 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Luxurious and elegant! The packaging is beautiful and the fragrance is even better. This has become my go-to perfume for special occasions."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="author-name">Emma Davis</span>
                        </div>
                    </div>

                    <!-- Testimonial Card 4 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Incredible value for money! The scent is long-lasting and sophisticated. I've already recommended it to all my friends and family."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="author-name">David Martinez</span>
                        </div>
                    </div>

                    <!-- Testimonial Card 5 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Perfect gift! Bought this for my partner and they absolutely love it. The fragrance is unique and memorable. Five stars!"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="author-name">Jessica Brown</span>
                        </div>
                    </div>

                    <!-- Testimonial Card 6 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Outstanding quality and service! Fast shipping and the product exceeded my expectations. This is now my favorite perfume brand."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="author-name">Robert Wilson</span>
                        </div>
                    </div>
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