<?php
// WAKE UP THE SESSION FIRST!
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

// ─── PREFILL DATA (logged-in users only) ─────────────────────────────────────
$prefill = [
    'fullName' => '',
    'email'    => '',
    'phone'    => '',
    'address'  => '',
    'city'     => '',
    'province' => '',
    'zipCode'  => '',
    'country'  => '',
];

if ($identity['type'] === 'user_id') {
    $uid = (int) $identity['id'];

    $uStmt = $conn->prepare("SELECT fname, lname, email, phone FROM users WHERE user_id = ?");
    $uStmt->bind_param('i', $uid);
    $uStmt->execute();
    $uRow = $uStmt->get_result()->fetch_assoc();
    $uStmt->close();

    if ($uRow) {
        $prefill['fullName'] = trim($uRow['fname'] . ' ' . $uRow['lname']);
        $prefill['email']    = $uRow['email']  ?? '';
        $prefill['phone']    = $uRow['phone']  ?? '';
    }

    $aStmt = $conn->prepare(
        "SELECT street, city, province, zip_code, country
         FROM orders
         WHERE user_id = ? AND street IS NOT NULL AND street != ''
         ORDER BY created_at DESC LIMIT 1"
    );
    $aStmt->bind_param('i', $uid);
    $aStmt->execute();
    $aRow = $aStmt->get_result()->fetch_assoc();
    $aStmt->close();

    if ($aRow) {
        $prefill['address']  = $aRow['street']   ?? '';
        $prefill['city']     = $aRow['city']      ?? '';
        $prefill['province'] = $aRow['province']  ?? '';
        $prefill['zipCode']  = $aRow['zip_code']  ?? '';
        $prefill['country']  = $aRow['country']   ?? '';
    }
}

function pv(string $key, array $prefill): string {
    return htmlspecialchars($prefill[$key] ?? '', ENT_QUOTES, 'UTF-8');
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
        <link rel="stylesheet" href="../assets/css/CartPageStyle.css">
        <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
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
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">

                            <!-- ── Voucher fields: always present, JS fills them on apply ── -->
                            <input type="hidden" name="voucher_code"    id="hiddenVoucherCode"    value="">
                            <input type="hidden" name="discount_amount" id="hiddenDiscountAmount" value="0">

                            <div class="formSection">
                                <h3>Contact Details</h3>
                                
                                <div class="formGroup">
                                    <label for="fullName">Full Name <span class="required">*</span></label>
                                    <input type="text" id="fullName" name="fullName"
                                           placeholder="ex. John Doe"
                                           value="<?= pv('fullName', $prefill) ?>"
                                           required>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="email">Email Address <span class="required">*</span></label>
                                        <input type="email" id="email" name="email"
                                               placeholder="ex. johndoe@example.com"
                                               value="<?= pv('email', $prefill) ?>"
                                               required>
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="phone">Phone Number <span class="required">*</span></label>
                                        <input type="tel" id="phone" name="phone"
                                               placeholder="ex. +63 912 345 6789"
                                               value="<?= pv('phone', $prefill) ?>"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="formSection">
                                <h3>Shipping Address</h3>
                                
                                <div class="formGroup">
                                    <label for="address">Street Address <span class="required">*</span></label>
                                    <input type="text" id="address" name="address"
                                           placeholder="ex. 123 Main Street"
                                           value="<?= pv('address', $prefill) ?>"
                                           required>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="city">City <span class="required">*</span></label>
                                        <input type="text" id="city" name="city"
                                               placeholder="ex. Manila"
                                               value="<?= pv('city', $prefill) ?>"
                                               required>
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="province">Province <span class="required">*</span></label>
                                        <input type="text" id="province" name="province"
                                               placeholder="ex. Metro Manila"
                                               value="<?= pv('province', $prefill) ?>"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="formRow">
                                    <div class="formGroup">
                                        <label for="zipCode">Zip Code <span class="required">*</span></label>
                                        <input type="text" id="zipCode" name="zipCode"
                                               placeholder="ex. 1000"
                                               value="<?= pv('zipCode', $prefill) ?>"
                                               required>
                                    </div>
                                    
                                    <div class="formGroup">
                                        <label for="country">Country <span class="required">*</span></label>
                                        <select id="country" name="country" required>
                                            <option value="" disabled <?= empty($prefill['country']) ? 'selected' : '' ?>>Select Country</option>
                                            <?php
                                            $countries = ['Philippines', 'Japan', 'Switzerland'];
                                            foreach ($countries as $c):
                                                $sel = ($prefill['country'] === $c) ? 'selected' : '';
                                            ?>
                                            <option value="<?= htmlspecialchars($c) ?>" <?= $sel ?>><?= htmlspecialchars($c) ?></option>
                                            <?php endforeach; ?>
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

                                </div>
                            </div>

                            <div class="formActions">
                                <button type="button" class="backToCartBtn" onclick="window.location.href='cart.php'">
                                    <i class="fas fa-left"></i> Back to Cart
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
                        $co_id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
                        $co_id_value  = $identity['id'];
                        // FIX Issue 1: bind_type must be 'i' for users (integer),
                        // and resolved to 'i' for guests after lookup.
                        // Original code left bind_type as 's' for users — fixed here.
                        $co_bind_type = 'i';   // users always have integer ids

                        if ($co_id_column === 'guest_id') {
                            $g = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
                            $g->bind_param("s", $co_id_value);
                            $g->execute();
                            $g_result = $g->get_result();
                            $g->close();
                            if ($g_result->num_rows > 0) {
                                $co_id_value  = $g_result->fetch_assoc()['guest_id'];
                                // co_bind_type stays 'i'
                            } else {
                                header("Location: index.php?error=session_expired");
                                exit;
                            }
                        }

                        $cartStmt = $conn->prepare("
                            SELECT c.cart_id, c.product_id, c.quantity,
                                   p.product_name, p.price, pi.image_url 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.product_id
                            LEFT JOIN product_images pi
                                   ON pi.product_id = p.product_id AND pi.is_primary = 1
                            WHERE c.$co_id_column = ?
                        ");
                        $cartStmt->bind_param($co_bind_type, $co_id_value);
                        $cartStmt->execute();
                        $cartResult = $cartStmt->get_result();
                        $cartStmt->close();


                        $checkoutItems = [];
                        $subtotal = 0.0;
                        $totalQty = 0;

                        while ($row = $cartResult->fetch_assoc()) {
                            if (!empty($_SESSION['selected_items'])
                                && in_array($row['cart_id'], $_SESSION['selected_items'])
                            ) {
                                $checkoutItems[] = $row;
                                $subtotal += (float)$row['price'] * (int)$row['quantity'];
                                $totalQty += (int)$row['quantity'];
                            }
                        }

                      
                        $shipping_fee = (count($checkoutItems) > 0) ? 150.00 : 0.00;
                        $total        = $subtotal + $shipping_fee;
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
                                        <span>Qty: <?= (int)$item['quantity'] ?></span>
                                    </div>
                                </div>
                                <div class="itemPrice">
                                    <p>₱<?= number_format((float)$item['price'] * (int)$item['quantity'], 2) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <?php if (empty($checkoutItems)): ?>
                                <p style="color:#aaa; text-align:center;">No items selected.</p>
                            <?php endif; ?>
                        </div>

                        <div class="promoSection">
                            <input type="text" placeholder="Enter promo code" class="promoInput" id="promoInput">
                            <button type="button" class="applyPromoBtn" id="applyPromoBtn">Apply</button>
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
                                <span id="discountDisplay">- ₱0.00</span>
                            </div>
                            <div class="totalDivider"></div>
                            <div class="totalRow totalFinal">
                                <span>Total:</span>
                                <span id="totalDisplay">₱<?= number_format($total, 2) ?></span>
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

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const promoInput     = document.getElementById('promoInput');
            const applyBtn       = document.getElementById('applyPromoBtn');
            const discountEl     = document.getElementById('discountDisplay');
            const totalEl        = document.getElementById('totalDisplay');
            const hiddenCode     = document.getElementById('hiddenVoucherCode');
            const hiddenDiscount = document.getElementById('hiddenDiscountAmount');

            const baseSubtotal  = <?= json_encode((float)$subtotal) ?>;
            const shippingFee   = <?= json_encode((float)$shipping_fee) ?>;

            if (!applyBtn) return;

            applyBtn.addEventListener('click', function () {
                const code = promoInput ? promoInput.value.trim() : '';
                if (!code) {
                    alert('Please enter a promo code.');
                    return;
                }

                applyBtn.disabled = true;
                applyBtn.textContent = 'Checking...';

                fetch('../backend/apply_voucher.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'code=' + encodeURIComponent(code)
                         + '&subtotal=' + baseSubtotal.toFixed(2)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const discountAmt = parseFloat(data.discount);
                        // apply_voucher returns new_total as discounted subtotal;
                        // add shipping to get the true order total — same formula
                        // as orderConfirmation.php uses server-side.
                        const newTotal = (baseSubtotal + shippingFee) - discountAmt;

                        if (discountEl) discountEl.textContent = '- ₱' + discountAmt.toFixed(2);
                        if (totalEl)    totalEl.textContent    = '₱'   + newTotal.toFixed(2);

                        hiddenCode.value     = code;
                        hiddenDiscount.value = discountAmt.toFixed(2);

                        promoInput.disabled  = true;
                        applyBtn.textContent = '✓ Applied';
                    } else {
                        alert(data.message || 'Invalid promo code.');
                        applyBtn.disabled    = false;
                        applyBtn.textContent = 'Apply';
                    }
                })
                .catch(() => {
                    alert('Could not validate the promo code. Please try again.');
                    applyBtn.disabled    = false;
                    applyBtn.textContent = 'Apply';
                });
            });
        });
        </script>
        <?php include '../components/footer.php'; ?>
    </body>
</html>