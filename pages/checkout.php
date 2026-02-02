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
        <main>

            <div class="checkoutWrapper">
                <div class="checkoutHeader">
                    <h1>Checkout</h1>
                    <div class="checkoutSteps">
                        <div class="step active">
                            <div class="stepNumber">1</div>
                            <span>Information</span>
                        </div>
                        <div class="stepLine"></div>
                        <div class="step">
                            <div class="stepNumber">2</div>
                            <span>Payment</span>
                        </div>
                        <div class="stepLine"></div>
                        <div class="step">
                            <div class="stepNumber">3</div>
                            <span>Confirmation</span>
                        </div>
                    </div>
                </div>

                <div id="checkoutContainer">

                    <!--billing information--> 
                    <div class="billingInfo">
                        <div class="sectionHeader">
                            
                            <h2>Billing Information</h2>
                        </div>
                        
                        <form id="checkoutForm" action="orderAgain.php" method="POST">
                            <div class="formSection">
                                <h3>Contact Details</h3>
                                
                                <div class="formGroup">
                                    <label for="fullName">Full Name <span class="required">*</span></label>
                                    <input type="text" id="fullName" name="fullName" placeholder="ex. John Doe" >
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="email">Email Address <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" placeholder="ex. johndoe@example.com" >
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="phone">Phone Number <span class="required">*</span></label>
                                        <input type="tel" id="phone" name="phone" placeholder="ex. +63 912 345 6789" >
                                    </div>
                                </div>
                            </div>

                            <div class="formSection">
                                <h3>Shipping Address</h3>
                                
                                <div class="formGroup">
                                    <label for="address">Street Address <span class="required">*</span></label>
                                    <input type="text" id="address" name="address" placeholder="ex. 123 Main Street">
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="city">City <span class="required">*</span></label>
                                        <input type="text" id="city" name="city" placeholder="ex. Manila" >
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="province">Province <span class="required">*</span></label>
                                        <input type="text" id="province" name="province" placeholder="ex. Metro Manila" >
                                    </div>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="zipCode">Zip Code <span class="required">*</span></label>
                                        <input type="text" id="zipCode" name="zipCode" placeholder="ex. 1000" >
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="country">Country <span class="required">*</span></label>
                                        <select>
                                            <option>Select Country</option>
                                            <option>Philippines</option>
                                            <option>Japan</option>
                                            <option>Switzerland</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="formSection">
                                <h3>Payment Method</h3>
                                
                                <div class="paymentOptions">
                                    <label class="paymentOption">
                                        <input type="radio" name="paymentMethod" value="cod" required>
                                        <div class="paymentCard">
                                            <div class="paymentInfo">
                                                <span class="paymentName">Cash on Delivery</span>
                                                <span class="paymentDesc">Pay when you receive</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="paymentOption">
                                        <input type="radio" name="paymentMethod" value="gcash">
                                        <div class="paymentCard">
                                            <div class="paymentInfo">
                                                <span class="paymentName">GCash</span>
                                                <span class="paymentDesc">Digital wallet payment</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="paymentOption">
                                        <input type="radio" name="paymentMethod" value="card">
                                        <div class="paymentCard">
                                            <div class="paymentInfo">
                                                <span class="paymentName">Credit/Debit Card</span>
                                                <span class="paymentDesc">Visa, Mastercard, etc.</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="formActions">
                                <button type="button" class="backToCartBtn" onclick="window.location.href='cart.php'">
                                    <i class="fas fa-arrow-left"></i> Back to Cart
                                </button>
                                <button type="submit" class="placeOrderBtn" onclick="openSignupModal()">
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
                        
                        <div class="orderItems">
                            <div class="orderItem">
                                <img src="../assets/images/products_images/nocturne.png" alt="Product">
                                <div class="itemDetails">
                                    <h4>Nocturne Eau de Parfum</h4>
                                    <p class="itemVariant">50ml | Premium Collection</p>
                                    <div class="itemQuantity">
                                        <span>Qty: 1</span>
                                    </div>
                                </div>
                                <div class="itemPrice">
                                    <p>₱1,299.00</p>
                                </div>
                            </div>

                            <div class="orderItem">
                                <img src="../assets/images/products_images/nocturne.png" alt="Product">
                                <div class="itemDetails">
                                    <h4>Classic Cologne</h4>
                                    <p class="itemVariant">100ml | Signature Line</p>
                                    <div class="itemQuantity">
                                        <span>Qty: 2</span>
                                    </div>
                                </div>
                                <div class="itemPrice">
                                    <p>₱2,400.00</p>
                                </div>
                            </div>
                        </div>

                        <div class="promoSection">
                            <input type="text" placeholder="Enter promo code" class="promoInput">
                            <button class="applyPromoBtn">Apply</button>
                        </div>
                        
                        <div class="orderTotal">
                            <div class="totalRow">
                                <span>Subtotal:</span>
                                <span>₱3,699.00</span>
                            </div>
                            <div class="totalRow">
                                <span>Shipping:</span>
                                <span>₱150.00</span>
                            </div>
                            <div class="totalRow discount">
                                <span>Discount:</span>
                                <span>- ₱0.00</span>
                            </div>
                            <div class="totalDivider"></div>
                            <div class="totalRow totalFinal">
                                <span>Total:</span>
                                <span>₱3,849.00</span>
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

        <?php include '../components/footer.php'; ?>
    </body>
</html>