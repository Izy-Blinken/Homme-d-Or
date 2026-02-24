
<!DOCTYPE html>
<html lang = "en">
    <head> 
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        
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
        <script src="script.js"></script>

        <main class="mainBG">

            <a href="javascript:history.back()" class="back-button">
                <i class="fa-solid fa-chevron-left"></i> 
            </a>
            <section id="product-details-section">
                        <div class="product-details-container">
                            <img src="../assets/images/products_images/nocturne.png" alt="Perfume Image" class="product-image">

                            <div class="product-dtls">
                                <h1>Name ng perfume</h1>
                                <h4>₱3,499</h4>
                                <p>
                                    Description ng perfume: lorem ipsum blah blah blah, ingredients nya ganun, ano smell something etc...
                                </p>

                                <div class="product-actions">
                                    <a href="checkout.php">
                                        <button class="buynow">
                                            <i class="fa-solid fa-bolt"></i> Buy Now
                                        </button>
                                    </a>
                                    
                                    <a href="cart.php">
                                        <button class="addtocart">
                                            <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                        </button>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </section>

                    <section id="details-section">
                        <h2>Perfume Details</h2>
                        <div class="details-grid">
                            <div class="detailsSection-box">
                                <h4>Top Notes</h4>
                                <p>Blah blah blah</p>
                            </div>

                            <div class="detailsSection-box">
                                <h4>Middle Notes</h4>
                                <p>bla bla bla</p>
                            </div>

                            <div class="detailsSection-box">
                                <h4>Base Notes</h4>
                                <p>ahuhuhuhuh</p>
                            </div>
                        </div>
                    </section>

                    <section id="reviews-section">
                        <h2>Customer Reviews</h2>
                        <div class="reviews-container">
                            <div class="review-box">
                                <img src="../assets/images/products_images/customerPic.png" alt="User">
                                <div class="review-text">
                                    <h4>Wally B.</h4>
                                    <p>Long lasting smell. Worth the price.</p>
                                </div>
                            </div>

                            <div class="review-box">
                                <img src="../assets/images/products_images/customerPic.png" alt="User">
                                <div class="review-text">
                                    <h4>Bayola W.</h4>
                                    <p>Yiz galing. Highly recommended.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="new-arrivals-section">
                    <div class="new-arrivals-container">
                        <div class="section-header" >
                            <div class="header-line" style="background-color: lightgrey"></div>
                            <h2 style="color: white; font-weight: 300; letter-spacing: 2px;">You May Also Like</h2>
                            <div class="header-line" style="background-color: lightgrey"></div>
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
        </main>

    <?php include '../components/footer.php'; ?>
    <script src="../assets/js/HomepageAnimations.js"></script>

</body>
        
</html>
