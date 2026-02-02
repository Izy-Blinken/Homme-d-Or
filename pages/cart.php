<?php
session_start();

// Sample cart items (session-based, no database)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        1 => ['name' => 'Product Name 1', 'price' => 3499, 'qty' => 1, 'img' => '../assets/images/products_images/nocturne.png', 'desc' => '50ml • variant ng perfume ex. cologne'],
        2 => ['name' => 'Wally B.', 'price' => 2999, 'qty' => 2, 'img' => '../assets/images/products_images/customerPic.png', 'desc' => '30ml • AlbubNation']
    ];
}

// Handle quantity changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product_id'];

    if (isset($_POST['increase'])) {
        $_SESSION['cart'][$id]['qty']++;
    }

    if (isset($_POST['decrease']) && $_SESSION['cart'][$id]['qty'] > 1) {
        $_SESSION['cart'][$id]['qty']--;
    }

    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart</title>
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
        <link rel="stylesheet" href="../assets/css/ProductDetailsStyle.css">

    </head>

    <body>

        <?php include '../components/header.php'; ?>

        <main >
            <a href="javascript:history.back()" class="back-button">
                <i class="fa-solid fa-chevron-left"></i> 
            </a>

            <section class="cart-page">
            <h1 class="cart-title">Shopping Cart</h1>
            <div class="cart-wrapper">

                <section class="cart-items">
                    <?php 
                    $subtotal = 0;
                    foreach($_SESSION['cart'] as $id => $item): 
                        $totalPrice = $item['price'] * $item['qty'];
                        $subtotal += $totalPrice;
                    ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['img']; ?>" alt="Perfume">

                        <div class="cart-info">
                            <h4><?php echo $item['name']; ?></h4>
                            <p><?php echo $item['desc']; ?></p>

                            <div class="cart-actions">
                                <form method="POST" class="qty-form">
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    <button type="submit" name="decrease">-</button>
                                    <span><?php echo $item['qty']; ?></span>
                                    <button type="submit" name="increase">+</button>
                                </form>

                                <form method="POST" class="remove-form">
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    <button type="submit" name="remove" class="remove-item">Remove</button>
                                </form>
                            </div>
                        </div>

                        <div class="cart-price">
                            ₱<?php echo number_format($totalPrice, 2); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </section>

                <aside class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₱<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>₱150.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₱<?php echo number_format($subtotal + 150, 2); ?></span>
                    </div>
                    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                </aside>

            </div>
            </section>
        </main>

        <?php include '../components/footer.php'; ?>

    </body>
</html>
