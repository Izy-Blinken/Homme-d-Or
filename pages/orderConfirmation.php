<?php
// WAKE UP THE SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';
require_once '../backend/gcash.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendOrderEmail($to_email, $to_name, $order_id, $items, $total_amount, $paymentMethod) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hommedor2026@gmail.com';
        $mail->Password   = 'esoczvhrdrmilpbn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('hommedor2026@gmail.com', "Homme d'Or");
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation - #$order_id";

        $itemList = "";
        foreach ($items as $item) {
            $itemList .= "<li>{$item['product_name']} (Qty: {$item['quantity']})</li>";
        }

        $paymentText = ($paymentMethod === 'cod')
            ? "Cash on Delivery (Pay upon arrival)"
            : "Paid Online via GCash";

        $mail->Body = "
            <div style='font-family: Arial; max-width: 500px; margin:auto;'>
                <h2>Order Confirmation</h2>
                <p>Hi <strong>$to_name</strong>,</p>
                <p>Your order has been successfully placed.</p>
                <p><strong>Order #:</strong> $order_id</p>
                <h3>Items:</h3>
                <ul>$itemList</ul>
                <p><strong>Total:</strong> ₱" . number_format($total_amount, 2) . "</p>
                <p><strong>Payment Method:</strong> $paymentText</p>
                <p>We will process your order shortly.</p>
                <p style='font-size:12px; color:#aaa;'>Homme d'Or © 2026</p>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail Error: ' . $mail->ErrorInfo);
        return false;
    }
}

$identity  = getCurrentUserId();
$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value  = $identity['id'];

// GCASH RETURN: Coming back from PayMongo after payment
if (isset($_GET['gcash']) && $_GET['gcash'] === 'success' && isset($_GET['token'])) {

    $token    = preg_replace('/[^a-f0-9]/', '', $_GET['token']); // sanitize
    $filePath = sys_get_temp_dir() . '/pending_' . $token . '.json';

    if (!file_exists($filePath)) {
        header("Location: cart.php");
        exit;
    }

    $pending = json_decode(file_get_contents($filePath), true);
    unlink($filePath); // clean up

    $_SESSION['selected_items'] = $pending['selected_items'];

    $fullName      = $pending['fullName'];
    $nameParts     = explode(' ', $fullName, 2);
    $fname         = $nameParts[0];
    $lname         = $nameParts[1] ?? '';
    $email         = $pending['email'];
    $phone         = $pending['phone'];
    $street        = $pending['address'];
    $city          = $pending['city'];
    $province      = $pending['province'];
    $zipCode       = $pending['zipCode'];
    $country       = $pending['country'];
    $paymentMethod = 'gcash';
    $paymentTitle  = 'GCash';
    $paymentDetail = 'Paid via GCash';
    $order_status  = 'Processing';

    // Re-fetch cart items
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
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Bouncer
    if ($identity['type'] === 'stranger' || empty($_SESSION['selected_items'])) {
        header("Location: cart.php");
        exit;
    }

    $fullName  = trim($_POST['fullName'] ?? '');
    $nameParts = explode(' ', $fullName, 2);
    $fname     = $nameParts[0];
    $lname     = $nameParts[1] ?? '';
    $email     = trim($_POST['email']    ?? '');
    $phone     = trim($_POST['phone']    ?? '');
    $street    = trim($_POST['address']  ?? '');
    $city      = trim($_POST['city']     ?? '');
    $province  = trim($_POST['province'] ?? '');
    $zipCode   = trim($_POST['zipCode']  ?? '');
    $country   = trim($_POST['country']  ?? '');

    if (empty($fullName) || empty($email) || empty($phone) || empty($street) || empty($city)) {
        header("Location: checkout.php?error=missing_fields");
        exit;
    }

    $paymentMethod = $_POST['paymentMethod'] ?? 'cod';
    $paymentTitle  = 'Cash on Delivery';
    $paymentDetail = 'Pay when you receive';
    $order_status  = 'Pending Payment (COD)';

    // Fetch cart items
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

    // ---- GCASH: Save to session and redirect to PayMongo ----
    if ($paymentMethod === 'gcash') {
        $token = bin2hex(random_bytes(16));

        $pendingData = [
            'fullName'       => $fullName,
            'email'          => $email,
            'phone'          => $phone,
            'address'        => $street,
            'city'           => $city,
            'province'       => $province,
            'zipCode'        => $zipCode,
            'country'        => $country,
            'paymentMethod'  => 'gcash',
            'selected_items' => $_SESSION['selected_items'],
        ];

        file_put_contents(sys_get_temp_dir() . '/pending_' . $token . '.json', json_encode($pendingData));

        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') 
                    . '://' . $_SERVER['HTTP_HOST'] 
                    . '/Homme-d-Or';

        $success_url = $base_url . '/pages/orderConfirmation.php?gcash=success&token=' . $token;
        $cancel_url  = $base_url . '/pages/checkout.php?gcash=cancelled';

        $checkoutUrl = createPayMongoCheckout(
            $total_amount,
            "Homme d'Or Order",
            $success_url,
            $cancel_url
        );

        if ($checkoutUrl) {
            header("Location: $checkoutUrl");
            exit;
        } else {
            header("Location: checkout.php?error=paymongo_failed");
            exit;
        }
    }

    // ---- COD: Set payment details ----
    $paymentTitle  = 'Cash on Delivery';
    $paymentDetail = 'Pay when you receive';

} else {
    // Direct access — no POST and no gcash return
    header("Location: cart.php");
    exit;
}

$db_user_id  = ($identity['type'] === 'user_id')  ? $id_value : null;
$db_guest_id = ($identity['type'] === 'guest_id') ? $id_value : null;

$insertOrder = $conn->prepare("
    INSERT INTO orders (
        user_id, guest_id, fname, lname, email, phone,
        street, city, province, country, zip_code,
        subtotal, shipping_fee, total_amount, order_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$insertOrder->bind_param("ssssssssssdddss",
    $db_user_id, $db_guest_id, $fname, $lname, $email, $phone,
    $street, $city, $province, $country, $zipCode,
    $subtotal, $shipping_fee, $total_amount, $order_status
);
if (!$insertOrder->execute()) {
    die("Order failed.");
}
$order_id       = $conn->insert_id;
$orderFormatted = str_pad($order_id, 6, '0', STR_PAD_LEFT);

$db_method         = ($paymentMethod === 'gcash') ? 'gcash' : 'cod';
$db_payment_status = ($paymentMethod === 'cod') ? 'pending' : 'paid';
$db_paid_at        = ($paymentMethod !== 'cod') ? date('Y-m-d H:i:s') : null;

$insertPayment = $conn->prepare("
    INSERT INTO payments (order_id, method, payment_status, paid_at)
    VALUES (?, ?, ?, ?)
");
$insertPayment->bind_param("isss", $order_id, $db_method, $db_payment_status, $db_paid_at);
$insertPayment->execute();

foreach ($purchasedItems as $item) {
    $stmtItem = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase)
        VALUES (?, ?, ?, ?)
    ");
    $stmtItem->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmtItem->execute();
}

unset($_SESSION['selected_items']);

if (!empty($purchasedItems)) {
    $deletePlaceholders = implode(',', array_fill(0, count($purchasedItems), '?'));
    $deleteCart = $conn->prepare("DELETE FROM cart WHERE cart_id IN ($deletePlaceholders)");
    $types   = str_repeat('i', count($purchasedItems));
    $cartIds = array_column($purchasedItems, 'cart_id');
    $deleteCart->bind_param($types, ...$cartIds);
    $deleteCart->execute();
}

$updateStock = $conn->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE product_id = ?");
foreach ($purchasedItems as $item) {
    $updateStock->bind_param("ii", $item['quantity'], $item['product_id']);
    $updateStock->execute();
}

$emailSent = sendOrderEmail($email, $fname, $orderFormatted, $purchasedItems, $total_amount, $paymentMethod);
if (!$emailSent) {
    error_log("Order email failed for order ID: $order_id");
}
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
                <p class="order-number">Order #<?= $orderFormatted ?></p>
                <p class="email-notice">We've sent a confirmation email to <?= htmlspecialchars($email) ?>.</p>
            </div>
            <div class="confirm-container">

                <!-- LEFT: Delivery & Payment Details -->
                <div class="confirm-details">

                    <div class="status-tracker">
                        <h3>Order Status</h3>
                        <div class="tracker-bar">
                            <div class="tracker-step active">
                                <div class="dot"></div>
                                <span>Order Placed</span>
                            </div>
                            <div class="tracker-line"></div>
                            <div class="tracker-step">
                                <div class="dot"></div>
                                <span>Processing</span>
                            </div>
                            <div class="tracker-line"></div>
                            <div class="tracker-step">
                                <div class="dot"></div>
                                <span>Shipped</span>
                            </div>
                            <div class="tracker-line"></div>
                            <div class="tracker-step">
                                <div class="dot"></div>
                                <span>Delivered</span>
                            </div>
                        </div>
                    </div>

                    <h3>Delivery Information</h3>
                    <div class="info-grid">
                        <div class="info-block">
                            <h3>Full Name</h3>
                            <p><?= htmlspecialchars($fname . ' ' . $lname) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Email</h3>
                            <p><?= htmlspecialchars($email) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Phone</h3>
                            <p><?= htmlspecialchars($phone) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Street</h3>
                            <p><?= htmlspecialchars($street) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>City</h3>
                            <p><?= htmlspecialchars($city) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Province</h3>
                            <p><?= htmlspecialchars($province) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Zip Code</h3>
                            <p><?= htmlspecialchars($zipCode) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Country</h3>
                            <p><?= htmlspecialchars($country) ?></p>
                        </div>
                    </div>

                    <br>
                    <h3>Payment Method</h3>
                    <div class="info-grid">
                        <div class="info-block">
                            <h3>Method</h3>
                            <p><?= htmlspecialchars($paymentTitle) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Detail</h3>
                            <p><?= htmlspecialchars($paymentDetail) ?></p>
                        </div>
                        <div class="info-block">
                            <h3>Status</h3>
                            <p style="color: #c9a961;"><?= htmlspecialchars($order_status) ?></p>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: Order Receipt -->
                <div class="confirm-receipt">
                    <h3>Order Summary</h3>
                    <div class="receipt-items">
                        <?php foreach ($purchasedItems as $item):
                            $imgSrc = $item['image_url']
                                ? '../assets/images/products/' . htmlspecialchars($item['image_url'])
                                : '../assets/images/brand_images/nocturne.png';
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
                        <div class="r-row r-final">
                            <span><?= ($paymentMethod === 'cod') ? 'Total to Pay' : 'Total Paid' ?></span>
                            <span>₱<?= number_format($total_amount, 2) ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <?php include '../components/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.modal').forEach(function(modal) {
                modal.style.display = 'none';
                modal.classList.remove('show', 'closing');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.overflow = 'auto';
            document.body.style.overflowX = 'hidden';
        });
    </script>
</body>
</html>