<?php
// 1. WAKE UP THE SESSION FIRST!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Now connect to the database and check identity
include_once '../backend/db_connect.php'; 
$identity = getCurrentUserId();

// THE BOUNCER: Only kick out complete strangers!
if ($identity['type'] === 'stranger') {
    header("Location: index.php?login_required=true");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Checkout</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
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
            <button class="back-btn" onclick="history.back()" title="Go back"><i class="fas fa-arrow-left"></i> Back</button>

            <div class="checkoutWrapper">
                <div class="checkoutHeader">
                    <h1>Checkout</h1>
                
                </div>

                <div id="checkoutContainer">

                    <!--billing information--> 
                    <div class="billingInfo">
                        <div class="sectionHeader">
                            
                            <h2>Billing Information</h2>
                        </div>
                        
                        <form id="checkoutForm" action="orderConfirmation.php" method="POST">
                            <div class="formSection">
                                <h3>Contact Details</h3>
                                
                                <div class="formGroup">
                                    <label for="fullName">Full Name <span class="required">*</span></label>
                                    <input type="text" id="fullName" name="fullName" placeholder="ex. John Doe" required>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="email">Email Address <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" placeholder="ex. johndoe@example.com" required>
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="phone">Phone Number <span class="required">*</span></label>
                                        <input type="tel" id="phone" name="phone" placeholder="ex. +63 912 345 6789" required>
                                    </div>
                                </div>
                            </div>

                            <div class="formSection">
                                <h3>Shipping Address</h3>
                                
                                <div class="formGroup">
                                    <label for="address">Street Address <span class="required">*</span></label>
                                    <input type="text" id="address" name="address" placeholder="ex. 123 Main Street" required>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="city">City <span class="required">*</span></label>
                                        <input type="text" id="city" name="city" placeholder="ex. Manila" required>
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="province">Province <span class="required">*</span></label>
                                        <input type="text" id="province" name="province" placeholder="ex. Metro Manila" required>
                                    </div>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="zipCode">Zip Code <span class="required">*</span></label>
                                        <input type="text" id="zipCode" name="zipCode" placeholder="ex. 1000" required>
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="country">Country <span class="required">*</span></label>
                                        <select name="country" required>
                                            <option value="" disabled selected>Select Country</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Switzerland">Switzerland</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="formSection">
                                <h3>Payment Method</h3>
                                
                                <div class="paymentOptions">
                                    
                                    <div class="paymentOption">
                                        <input type="radio" name="paymentMethod" id="pay-cod" value="cod" required checked>
                                        <label for="pay-cod" class="paymentCard">
                                            <div class="paymentInfo">
                                                <span class="paymentName">Cash on Delivery</span>
                                                <span class="paymentDesc">Pay when you receive</span>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="paymentOption">
                                        <input type="radio" name="paymentMethod" id="pay-gcash" value="gcash">
                                        <label for="pay-gcash" class="paymentCard">
                                            <div class="paymentInfo">
                                                <span class="paymentName">GCash</span>
                                                <span class="paymentDesc">Digital wallet payment</span>
                                            </div>
                                        </label>
                                        
                                        <div class="paymentDetails">
                                            <p style="font-size: 13px; color: #aaa; margin: 0;">
                                                <i class="fas fa-info-circle"></i>
                                                You'll be redirected to GCash to complete your payment securely.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- <div class="paymentOption">
                                        <input type="radio" name="paymentMethod" disabled id="pay-card" value="card">
                                        <label for="pay-card" class="paymentCard">
                                            <div class="paymentInfo">
                                                <span class="paymentName">Credit/Debit Card</span>
                                                <span class="paymentDesc">Visa, Mastercard, etc.</span>
                                            </div>
                                        </label>
                                        
                                        <div class="paymentDetails">
                                            <div class="formGroup">
                                                <label>Cardholder Name <span class="required">*</span></label>
                                                <input type="text" name="cardName" placeholder="Name on card">
                                            </div>
                                            <div class="formGroup">
                                                <label>Card Number <span class="required">*</span></label>
                                                <input type="text" name="cardNumber" placeholder="0000 0000 0000 0000">
                                            </div>
                                            <div class="formRow">
                                                <div class="formGroup" style="margin-bottom: 0;">
                                                    <label>Expiry Date <span class="required">*</span></label>
                                                    <input type="text" name="cardExpiry" placeholder="MM/YY">
                                                </div>
                                                <div class="formGroup" style="margin-bottom: 0;">
                                                    <label>CVV <span class="required">*</span></label>
                                                    <input type="password" name="cardCvv" placeholder="123" maxlength="4">
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    
                                </div>
                            </div>

                            <div class="formActions">
                                <button type="button" class="backToCartBtn" onclick="window.location.href='cart.php'">
                                    <i class="fas fa-arrow-left"></i> Back to Cart
                                </button>
                                <button type="submit" class="placeOrderBtn">
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </div>

                    <!--order summary--> 
                    <div class="orderSummary">
                        <div class="sectionHeader">

                        <h2>Order Summary</h2>
                        </div>

                        <?php
                        $id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
                        $id_value = $identity['id'];

                        $cartStmt = $conn->prepare("
                            SELECT c.cart_id, c.product_id, c.quantity, p.product_name, p.price, pi.image_url 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.product_id
                            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
                            WHERE c.$id_column = ?
                        ");
                        $cartStmt->bind_param("s", $id_value);
                        $cartStmt->execute();
                        $cartResult = $cartStmt->get_result();

                        $checkoutItems = [];
                        $subtotal = 0;

                        while ($row = $cartResult->fetch_assoc()) {
                            if (!empty($_SESSION['selected_items']) && in_array($row['cart_id'], $_SESSION['selected_items'])) {
                                $checkoutItems[] = $row;
                                $subtotal += $row['price'] * $row['quantity'];
                            }
                        }

                        $shipping_fee = ($subtotal > 0) ? 150.00 : 0.00;
                        $total = $subtotal + $shipping_fee;
                        ?>

                        <div class="orderItems">
                            <?php foreach ($checkoutItems as $item): 
                                $imgSrc = $item['image_url'] 
                                    ? '../assets/images/products/' . htmlspecialchars($item['image_url']) 
                                    : '../assets/images/brand_images/nocturne.png';
                            ?>
                            <div class="orderItem">
                                <img src="<?= $imgSrc ?>" alt="Product">
                                <div class="itemDetails">
                                    <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                                    <div class="itemQuantity">
                                        <span>Qty: <?= $item['quantity'] ?></span>
                                    </div>
                                </div>
                                <div class="itemPrice">
                                    <p>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <?php if (empty($checkoutItems)): ?>
                                <p style="color:#aaa; text-align:center;">No items selected.</p>
                            <?php endif; ?>
                        </div>

                        <div class="promoSection">
                            <input type="text" placeholder="Enter promo code" class="promoInput">
                            <button class="applyPromoBtn">Apply</button>
                        </div>
                        
                         <div class="orderTotal">
                            <div class="totalRow">
                                <span>Subtotal:</span>
                                <span>₱<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="totalRow">
                                <span>Shipping:</span>
                                <span>₱<?= number_format($shipping_fee, 2) ?></span>
                            </div>
                            <div class="totalRow discount">
                                <span>Discount:</span>
                                <span>- ₱0.00</span>
                            </div>
                            <div class="totalDivider"></div>
                            <div class="totalRow totalFinal">
                                <span>Total:</span>
                                <span>₱<?= number_format($total, 2) ?></span>
                            </div>
                        </div>

                        <div class="securityBadge">
                            <i class="fas fa-lock"></i>
                            <span>Secure checkout powered by SSL encryption</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <script src="../assets/js/orderAgainModal.js"></script>
        <?php include '../components/footer.php'; ?>
    </body>
</html>