<!DOCTYPE html>
<html lang = "en">
    <head> 
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homme d'Or - Product Details</title>
        
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">

    </head>
    <body style="color: red;"> 
        <?php include '../components/header.php'; ?>
        <script src="script.js"></script>

        <main>
<section id="product-details-section">
            <div class="product-details-container">
                <img src="../assets/images/products_images/nocturne.png" alt="Perfume Image" class="product-image">

                <div class="product-info">
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
                <div class="details-box">
                    <h4>Top Notes</h4>
                    <p>Blah blah blah</p>
                </div>

                <div class="details-box">
                    <h4>Middle Notes</h4>
                    <p>bla bla bla</p>
                </div>

                <div class="details-box">
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
        </main>

        <?php include '../components/footer.php'; ?>

    </body>
        
</html>

