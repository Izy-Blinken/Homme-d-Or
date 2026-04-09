<?php
// WAKE UP THE SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';
$identity = getCurrentUserId();

// THE BOUNCER: Prevent direct access
if ($identity['type'] === 'stranger' || empty($_SESSION['selected_items']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}

$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value = $identity['id'];

$fullName = trim($_POST['fullName'] ?? 'Guest User');
$nameParts = explode(' ', $fullName, 2);
$fname = $nameParts[0];
$lname = $nameParts[1] ?? '';

$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$street = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$province = trim($_POST['province'] ?? '');
$zipCode = trim($_POST['zipCode'] ?? '');
$country = trim($_POST['country'] ?? '');

$paymentMethod = $_POST['paymentMethod'] ?? 'cod';
$paymentTitle = 'Cash on Delivery';
$paymentDetail = 'Pay when you receive';

if ($paymentMethod === 'gcash') {
    $paymentTitle = 'GCash';
    $paymentDetail = 'Number: ' . htmlspecialchars($_POST['gcashNumber'] ?? '');
} elseif ($paymentMethod === 'card') {
    $paymentTitle = 'Credit/Debit Card';
    $cardNum = str_replace(' ', '', $_POST['cardNumber'] ?? '');
    $last4 = substr($cardNum, -4);
    $paymentDetail = 'Ending in **** ' . ($last4 ? $last4 : 'XXXX');
}

$stmt = $conn->prepare("
    SELECT c.cart_id, c.product_id, c.quantity, p.product_name, p.price, pi.image_url
    FROM cart c JOIN products p ON c.product_id = p.product_id
    LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
    WHERE c.$id_column = ?
");
$stmt->bind_param("s", $id_value);
$stmt->execute();
$result = $stmt->get_result();

$purchasedItems = [];
$subtotal = 0;
while ($row = $result->fetch_assoc()) {
    if (in_array($row['cart_id'], $_SESSION['selected_items'])) {
        $purchasedItems[] = $row;
        $subtotal += ($row['price'] * $row['quantity']);
    }
}

$shipping_fee = ($subtotal > 0) ? 150.00 : 0.00;
$total_amount = $subtotal + $shipping_fee;
$order_status = 'Pending';

$db_user_id = ($identity['type'] === 'user_id') ? $id_value : null;
$db_guest_id = ($identity['type'] === 'guest_id') ? $id_value : null;

$insertOrder = $conn->prepare("
    INSERT INTO orders (user_id, guest_id, fname, lname, email, phone, street, city, province, country, zip_code, subtotal, shipping_fee, total_amount, order_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$insertOrder->bind_param("ssssssssssdddds",
    $db_user_id, $db_guest_id, $fname, $lname, $email, $phone,
    $street, $city, $province, $country, $zipCode,
    $subtotal, $shipping_fee, $total_amount, $order_status
);
$insertOrder->execute();
$order_id = $conn->insert_id;

foreach ($purchasedItems as $item) {
    $delStmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
    $delStmt->bind_param("i", $item['cart_id']);
    $delStmt->execute();
}
unset($_SESSION['selected_items']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/ConfirmationStyle.css">
</head>
<body>
    <?php include '../components/header.php'; ?>
    <main class="mainBG confirm-bg">
        <div class="confirm-wrapper">
            <div class="confirm-header">
                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                <h1>Thank You For Your Order</h1>
                <p class="order-number">Order #<?= str_pad($order_id, 6, '0', STR_PAD_LEFT) ?></p>
                <p class="email-notice">We've sent a confirmation email to <?= htmlspecialchars($email) ?>.</p>
            </div>
            <div class="confirm-container">
                <div class="confirm-receipt">
                    <h3>Order Summary</h3>
                    <div class="receipt-items">
                        <?php foreach($purchasedItems as $item):
                            $imgSrc = $item['image_url'] ? '../assets/images/products/' . htmlspecialchars($item['image_url']) : '../assets/images/brand_images/nocturne.png';
                        ?>
                        <div class="receipt-item">
                            <img src="<?= $imgSrc ?>" alt="Product">
                            <div class="r-item-details">
                                <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                                <p>Qty: <?= $item['quantity'] ?></p>
                            </div>
                            <div class="r-item-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="receipt-totals">
                        <div class="r-row"><span>Subtotal</span><span>₱<?= number_format($subtotal, 2) ?></span></div>
                        <div class="r-row"><span>Shipping</span><span>₱<?= number_format($shipping_fee, 2) ?></span></div>
                        <div class="r-divider"></div>
                        <div class="r-row r-final"><span>Total Paid</span><span>₱<?= number_format($total_amount, 2) ?></span></div>
                    </div>
                </div>
            </div>
            <div class="confirm-actions">
                <a href="shop.php" class="btn-ghost">Continue Shopping</a>
                <?php if ($identity['type'] === 'user_id'): ?>
                    <a href="viewAllTabs.php" class="btn-solid">View Order History</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include '../components/footer.php'; ?>
</body>
</html>