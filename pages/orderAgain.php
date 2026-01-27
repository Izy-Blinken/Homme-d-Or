<!DOCTYPE html>
<html>
    <head>
        <title>Checkout</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    </head>

    <body>
        <?php include '../components/checkoutHeader.php'; ?>

        <main>

            <div class="confirmationContainer">
                <div class="confirmationBox">
                    <div class="successIcon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    
                    <h1>Order Placed Successfully!</h1>
                    <p class="orderNumber">Order #1</p>
                    
                    <div class="statusMessage">
                        <i class="fa-solid fa-clock"></i>
                        <p>Waiting to ship your order</p>
                    </div>
                    
                    <p class="thankYouMessage">
                        Thank you for your purchase! We'll send you a confirmation email with your order details.
                    </p>
                    
                    <div class="actionButtons">
                        <a href="profileViewOrders.php" class="btn btnPrimary">
                             View My Order
                        </a>
                        <a href="index.php" class="btn btnSecondary">
                             Order Again
                        </a>
                    </div>
                </div>
            </div>

        </main>

        <?php include '../components/footer.php'; ?>
    </body>
</html>