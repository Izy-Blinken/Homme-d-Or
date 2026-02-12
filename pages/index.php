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
        
        
        done: review and cancel order modal

        Things to polish pa:
        1. History and wishlist scrollable
        2. Verification code for sign up
        3. Toast messages after successful/failed operations
        4. Product links
        5. Consistent design
        6. Animations
        7. Responsive pages
        8. Review page (for product na mismo)
        9. Terms and conditions
        10. Order placed successfully gawin modal nlang (yung orderAgain.php)
        11. *Pinag-iisipan q pa* Yung mga discover buttons sa shop page lagyan ng individual page
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
                
                <!--
                <div class="hero-video-ad">
                    <div class="video-wrapper" onclick="playHeroVideo()">
                        <span class="video-label">WATCH AD</span>
                        <img src="../assets/images/brand_images/heroBG1.png" alt="Perfume Video Ad">
                        <div class="video-play-button">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    </div>
                </div> 
                 Info Card - Bottom Left 
                -->
                
               
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
           <section class="carousel-section">
               <div class="carousel-container">
                   <div class="carousel-wrapper">
                       <div class="carousel-track" id="carouselTrack">
                           <div class="carousel-slide">
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
                   <div class="products-grid">
                       
                       <!-- Product Card 1 - SOLD OUT -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/sampleperfume.png" alt="Perfume 1">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <div class="sold-out-label">SOLD OUT</div>
                           </div>
                           <button class="add-to-cart-btn" disabled>ADD TO CART</button>
                       </div>

                       <!-- Product Card 2 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 2">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn" onclick="window.location.href='productDetails.php'">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn" onclick="window.location.href='cart.php'">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Golden Night</h3>
                               <p class="product-price">₱1,800</p>
                           </div>
                       </div>

                       <!-- Product Card 3 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 3">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Ocean Breeze</h3>
                               <p class="product-price">₱2,200</p>
                           </div>
                       </div>

                       <!-- Product Card 4 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 4">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Vanilla Dream</h3>
                               <p class="product-price">₱2,800</p>
                           </div>
                       </div>

                       <!-- Product Card 5 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 5">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Midnight Rose</h3>
                               <p class="product-price">₱3,200</p>
                           </div>
                       </div>

                       <!-- Product Card 6 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Amber Woods</h3>
                               <p class="product-price">₱2,600</p>
                           </div>
                       </div>

                       <!-- Product Card 7 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Amber Woods</h3>
                               <p class="product-price">₱2,600</p>
                           </div>
                       </div>

                       <!-- Product Card 8 -->
                       <div class="product-card">
                           <div class="product-card-image">
                               <img src="../assets/images/brand_images/nocturne.png" alt="Perfume 6">
                               <button class="wishlist-btn">
                                   <i class="fa-solid fa-heart"></i>
                               </button>
                               <button class="quick-view-btn">Quick View</button>
                           </div>
                           <button class="add-to-cart-btn">
                               ADD TO CART
                           </button>
                           <div class="product-info">
                               <h3>Amber Woods</h3>
                               <p class="product-price">₱2,600</p>
                           </div>
                       </div>

                   </div>
               </div>
           </section>
       </div>

    <!-- BRAND LOGOS -->
    <section class="brands-section">
    <div class="brands-scroll-container">
        <div class="brands-track">
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
    <section class="promo-cards-section">
        <div class="promo-cards-container">
            
            <!-- Card 1 -->
            <div class="promo-card">
                <div class="promo-image">
                    <img src="../assets/images/brand_images/nocturne.png" alt="Holiday Gifts">
                </div>
                <div class="promo-content">
                    <h3>Holiday gifts selection</h3>
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
                    <h3>Xclusive offers</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In commodo porta mi, ut vestibulum urna eros. Nam in lacinia est, vestibulum urna eros, sagittis et mollis gravida, tincidunt a lorem.</p>
                    <a href="#" class="promo-link">DISCOVER</a>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW ARRIVALS SECTION -->
    <section class="new-arrivals-section">
        <div class="new-arrivals-container">
            <div class="section-header">
                <div class="header-line"></div>
                <h2>New Arrivals</h2>
                <div class="header-line"></div>
            </div>
            
            <div class="new-arrivals-wrapper">
                <div class="new-arrivals-overflow">
                    <div class="new-arrivals-grid" id="arrivalsGrid">
                        Product 1
                        <div class="new-arrival-card">
                            <div class="new-arrival-image">
                                <img src="../assets/images/brand_images/evrland.jpg" alt="New Arrival 1">
                            </div>
                            <button class="arrival-add-cart">ADD TO CART</button>
                        </div>
                    
                         Product 2 
                        <div class="new-arrival-card">
                            <div class="new-arrival-image">
                                <img src="../assets/images/brand_images/evrland.jpg" alt="New Arrival 2">
                            </div>
                            <button class="arrival-add-cart">ADD TO CART</button>
                        </div>
                    
                         Product 3 
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
</div>
 
<div class="promo-arrivals-wrapper-2">
    <section class="promo-cards-section-2">
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
    <section class="new-arrivals-section-2">
        <div class="new-arrivals-container-2">
            <div class="section-header">
                <div class="header-line"></div>
                <h2>Our Selection</h2>
                <div class="header-line"></div>
            </div>
            
            <div class="new-arrivals-wrapper-2">
                <div class="new-arrivals-overflow-2">
                    <div class="new-arrivals-grid-2" id="arrivalsGrid2">
                    </div>
                </div>

                <button class="scroll-next-btn" id="nextBtn2"><i class="fas fa-chevron-right"></i></button>

                <div class="line-indicators" id="lineIndicators2">
                    <span class="indicator active"></span>
                    <span class="indicator"></span>
                    <span class="indicator"></span>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="special-promo-section">
    <div class="special-promo-container">
        <h2 class="special-promo-title">SPECIAL PROMO 20% OFF THIS HOLIDAY SEASON</h2>
        <button class="special-promo-btn">Buy Now</button>
    </div>
</section>

<section class="testimonials-section">
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

        

        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/HomepageAnimations.js"></script>


    </body>
        
</html>

<!--
CHANGELOG:
01/25/2026
1. Added wrapper div (carousel-products-wrapper) around carousel and products sections
2. Wrapper has background image/gradient with glassmorphism effect on cards

01/24/2026
1. Added perfume hero section with:
   - Large center product image
   - Video ad with play button (top right)
   - Info card with product details (bottom left)
   - Product features list (bottom right)
   - CTA buttons (Buy Now & Learn More)
2. Design features: glassmorphism effects, hover animations, responsive layout

For all changes: Design not final. Placeholders pa lahat.

Previous updates:
01/20/2026
1. About Us page done.

12/31/25
1. Blog Page done.

12/29/25
1. Frontend: Checkout and order again page done.
2. Dropdown menu for shop and country/currency options done.

Initial:
1. Done reusable footer and header. Design not final.
2. Changed pages files from .html to .php for reusability. 
3. Kapag magrrun, direct sa browser: localhost/Homme_dor/pages/index.php
-->