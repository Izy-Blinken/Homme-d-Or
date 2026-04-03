<?php
    // Catch the submitted form data, with fallbacks just in case
    $orderNumber = $_POST['orderNumber'] ?? 'ORD' . rand(100000, 999999);
    $fullName = $_POST['fullName'] ?? 'Guest User';
    $address = $_POST['address'] ?? 'No Address Provided';
    $city = $_POST['city'] ?? '';
    $province = $_POST['province'] ?? '';
    $zipCode = $_POST['zipCode'] ?? '';
    $country = $_POST['country'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Logic to display the correct payment information
    $paymentMethod = $_POST['paymentMethod'] ?? 'cod';
    $paymentTitle = 'Cash on Delivery';
    $paymentDetail = 'Pay when you receive';

    if ($paymentMethod === 'gcash') {
        $paymentTitle = 'GCash';
        $gcashNumber = $_POST['gcashNumber'] ?? '';
        $paymentDetail = 'Number: ' . htmlspecialchars($gcashNumber);
    } elseif ($paymentMethod === 'card') {
        $paymentTitle = 'Credit/Debit Card';
        $cardNum = $_POST['cardNumber'] ?? '';
        // Extract just the last 4 digits for security
        $last4 = substr(str_replace(' ', '', $cardNum), -4);
        $paymentDetail = 'Ending in **** ' . ($last4 ? $last4 : 'XXXX');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme d'Or - Order Confirmed</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css"> 
    <link rel="stylesheet" href="../assets/css/ConfirmationStyle.css">
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="mainBG confirm-bg">
        <div class="confirm-wrapper">
            
            <div class="confirm-header">
                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                <h1>Thank You For Your Order</h1>
                <p class="order-number">Order #<?= htmlspecialchars($orderNumber) ?></p>
                <p class="email-notice">We've sent a confirmation email with your order details and receipt.</p>
            </div>

            <div class="confirm-container">
                <div class="confirm-details">
                    <div class="status-tracker">
                        <h3>Order Status</h3>
                        <div class="tracker-bar">
                            <div class="tracker-step active"><div class="dot"></div><span>Order Placed</span></div>
                            <div class="tracker-line"></div>
                            <div class="tracker-step"><div class="dot"></div><span>Processing</span></div>
                            <div class="tracker-line"></div>
                            <div class="tracker-step"><div class="dot"></div><span>Shipped</span></div>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-block">
                            <h3>Shipping Address</h3>
                            <p><strong><?= htmlspecialchars($fullName) ?></strong></p>
                            <p><?= htmlspecialchars($address) ?></p>
                            <p><?= htmlspecialchars($city) ?>, <?= htmlspecialchars($province) ?></p>
                            <p><?= htmlspecialchars($country) ?>, <?= htmlspecialchars($zipCode) ?></p>
                            <p><?= htmlspecialchars($phone) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Payment Method</h3>
                            <p><strong><?= htmlspecialchars($paymentTitle) ?></strong></p>
                            <p><?= $paymentDetail ?></p>
                        </div>
                    </div>
                </div>

                <div class="confirm-receipt">
                    <h3>Order Summary</h3>
                    <div class="receipt-items">
                        <div class="receipt-item">
                            <img src="../assets/images/products_images/nocturne.png" alt="Product">
                            <div class="r-item-details">
                                <h4>Nocturne Eau de Parfum</h4>
                                <p>50ml | Qty: 1</p>
                            </div>
                            <div class="r-item-price">₱1,299.00</div>
                        </div>
                        <div class="receipt-item">
                            <img src="../assets/images/products_images/nocturne.png" alt="Product">
                            <div class="r-item-details">
                                <h4>Classic Cologne</h4>
                                <p>100ml | Qty: 2</p>
                            </div>
                            <div class="r-item-price">₱2,400.00</div>
                        </div>
                    </div>

                    <div class="receipt-totals">
                        <div class="r-row"><span>Subtotal</span><span>₱3,699.00</span></div>
                        <div class="r-row"><span>Shipping</span><span>₱150.00</span></div>
                        <div class="r-divider"></div>
                        <div class="r-row r-final"><span>Total Paid</span><span>₱3,849.00</span></div>
                    </div>
                </div>
            </div>

            <div class="confirm-actions">
                <a href="shop.php" class="btn-ghost">Continue Shopping</a>
                <a href="viewAllTabs.php" class="btn-solid">View Order History</a>
            </div>

        </div>
    </main>

    <?php include '../components/footer.php'; ?>
</body>
</html>