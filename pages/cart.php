<?php
// Safely start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- TEST MODE: FORCING A CART RESET ---
// (Delete this 'unset' line later when you hook up your real add-to-cart buttons!)
unset($_SESSION['cart']); 
// ---------------------------------------

// Expanded sample cart items (Now includes 'selected' => true)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        1 => ['name' => 'Midnight Oud', 'price' => 3499, 'qty' => 1, 'img' => '../assets/images/products_images/nocturne.png', 'desc' => '50ml • Premium Collection', 'selected' => true],
        2 => ['name' => 'Wally B.', 'price' => 2999, 'qty' => 2, 'img' => '../assets/images/products_images/customerPic.png', 'desc' => '30ml • AlbubNation', 'selected' => true],
        3 => ['name' => 'Ocean Breeze', 'price' => 1850, 'qty' => 1, 'img' => '../assets/images/products_images/nocturne.png', 'desc' => '100ml • Summer Collection', 'selected' => true],
        4 => ['name' => 'Vanilla Dreams', 'price' => 2100, 'qty' => 3, 'img' => '../assets/images/products_images/customerPic.png', 'desc' => '50ml • Signature Scent', 'selected' => true]
    ];
}

// Handle form submissions (quantities, removing, and toggling checkboxes)
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

    // NEW: Handle Checkbox Toggle
    if (isset($_POST['toggle_select'])) {
        // Flip the boolean value (true becomes false, false becomes true)
        $_SESSION['cart'][$id]['selected'] = !$_SESSION['cart'][$id]['selected'];
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
        <title>Cart | Homme d'Or</title>
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
    </head>

    <body>

        <?php include '../components/header.php'; ?>

        <main class="mainBG">
            <a href="javascript:history.back()" class="back-button">
                <i class="fa-solid fa-chevron-left"></i> 
            </a>

            <section class="cart-page">
            <h1 class="cart-title">Shopping Cart</h1>
            <div class="cart-wrapper">

                <section class="cart-items">
                    <?php 
                    $subtotal = 0;
                    $selectedCount = 0;
                    
                    if (empty($_SESSION['cart'])) {
                        echo "<div class='empty-cart-msg'>Your cart is completely empty. Time to find a new signature scent!</div>";
                    }

                    foreach($_SESSION['cart'] as $id => $item): 
                        $totalPrice = $item['price'] * $item['qty'];
                        
                        // Only add to the total if the item is selected!
                        if ($item['selected']) {
                            $subtotal += $totalPrice;
                            $selectedCount++;
                        }
                    ?>
                    
                    <div class="cart-item" style="<?php echo !$item['selected'] ? 'opacity: 0.4; border-color: rgba(255,255,255,0.02); background: rgba(10,10,10,0.5);' : ''; ?>">
                        
                        <form method="POST" style="margin-right: 35px; margin-top: 5px;">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="toggle_select" value="1">
                            <label class="custom-checkbox">
                                <input type="checkbox" onChange="this.form.submit()" <?php echo $item['selected'] ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                            </label>
                        </form>

                        <img src="<?php echo $item['img']; ?>" alt="Perfume" style="<?php echo !$item['selected'] ? 'filter: grayscale(100%);' : ''; ?>">

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
                        <span>Subtotal (<?php echo $selectedCount; ?> items)</span>
                        <span>₱<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>₱<?php echo $selectedCount > 0 ? '150.00' : '0.00'; ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₱<?php echo $selectedCount > 0 ? number_format($subtotal + 150, 2) : '0.00'; ?></span>
                    </div>
                    
                    <?php if ($selectedCount > 0): ?>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="#" class="checkout-btn" style="opacity: 0.5; cursor: not-allowed; border-color: rgba(255,255,255,0.2); background: transparent; color: rgba(255,255,255,0.5);" onclick="return false;">Select Items to Checkout</a>
                    <?php endif; ?>
                </aside>

            </div>
            </section>
        </main>

        <?php include '../components/footer.php'; ?>
        <script src="../assets/js/cart.js"></script>
    </body>
</html>