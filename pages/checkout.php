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

        <div id="checkoutContainer">
            <!--order summary--> 
            <div class="orderSummary">
                <h2>Order Summary</h2>
                
                <div class="orderItems">
                    <!-- Placeholders (below is for single item pa lang) -->
                    <div class="orderItem">
                        <img src="product-image.jpg" alt="Product">
                        <div class="itemDetails">
                            <h4>Product Name</h4>
                            <p>Size: M | Color: Black</p>
                            <p>Qty: 1</p>
                        </div>
                        <div class="itemPrice">
                            <p>₱1,299.00</p>
                        </div>
                    </div>
                    
                </div>
                
                <div class="orderTotal">
                    <div class="totalRow">
                        <span>Subtotal:</span>
                        <span>₱1,299.00</span>
                    </div>
                    <div class="totalRow">
                        <span>Shipping:</span>
                        <span>₱150.00</span>
                    </div>
                    <div class="totalRow totalFinal">
                        <span>Total:</span>
                        <span>₱1,449.00</span>
                    </div>
                </div>
            </div>   
                
            <!--billing information--> 
            <div class="billingInfo">
                <h2>Billing Information</h2>
                
                <form id="checkoutForm"  action="orderAgain.php" method="POST">
                    <div class="formGroup">
                        <label for="fullName">Full Name *</label>
                        <input type="text" id="fullName" name="fullName" >
                    </div>
                    
                    <div class="formGroup">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" >
                    </div>
                    
                    <div class="formGroup">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" >
                    </div>
                    
                    <div class="formGroup">
                        <label for="address">Street Address *</label>
                        <input type="text" id="address" name="address" >
                    </div>
                    
                    <div class="formRow">
                        <div class="formGroup">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" >
                        </div>
                        
                        <div class="formGroup">
                            <label for="province">Province *</label>
                            <input type="text" id="province" name="province" >
                        </div>
                    </div>
                    
                    <div class="formRow">
                        <div class="formGroup">
                            <label for="zipCode">Zip Code *</label>
                            <input type="text" id="zipCode" name="zipCode" >
                        </div>
                        
                        <div class="formGroup">
                            <label for="country">Country *</label>
                            <input type="text" id="country" name="country" >
                        </div>
                    </div>
                    
                    <div class="formGroup">
                        <label for="paymentMethod">Payment Method *</label>
                        <select id="paymentMethod" name="paymentMethod">
                            <option value="">Select Payment Method</option>
                            <option value="cod">Cash on Delivery</option>
                            <option value="gcash">GCash</option>
                            <option value="card">Credit/Debit Card</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="placeOrderBtn">Place Order</button>
                    
                </form>
            </div>
        </div>

        </main>

        <?php include '../components/footer.php'; ?>
    </body>
</html>