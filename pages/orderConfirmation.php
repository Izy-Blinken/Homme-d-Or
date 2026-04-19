<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';
require_once '../backend/config.php';
require_once '../backend/gcash.php';
require_once '../backend/notifications/notify.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$vendorPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendorPath)) {
    error_log('PHPMailer vendor directory missing. Run: composer require phpmailer/phpmailer');
} else {
    require $vendorPath;
}

function sendOrderEmail($to_email, $to_name, $order_id, $items, $total_amount, $paymentMethod) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation - #$order_id";

        $itemList = '';
        foreach ($items as $item) {
            $itemList .= "<li>{$item['product_name']} (Qty: {$item['quantity']})</li>";
        }
        $paymentText = ($paymentMethod === 'cod')
            ? 'Cash on Delivery (Pay upon arrival)'
            : 'Paid Online via GCash';

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

//  Ensure discount_amount column exists (safe one-time migration) 
$colCheck = $conn->query(
    "SELECT 1 FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME   = 'orders'
       AND COLUMN_NAME  = 'discount_amount'
     LIMIT 1"
);
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query(
        "ALTER TABLE orders
         ADD COLUMN discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00
         AFTER shipping_fee"
    );
    error_log('orderConfirmation: added missing discount_amount column to orders table.');
}

//  Shared variables 
$fname = $lname = $email = $phone = '';
$street = $city = $province = $zipCode = $country = '';
$paymentMethod = $paymentTitle = $paymentDetail = $order_status = '';
$purchasedItems   = [];
$subtotal         = 0.0;
$shipping_fee     = 0.0;
$discount_amount  = 0.0;
$total_amount     = 0.0;
$voucher_code     = '';
$order_id         = 0;
$orderFormatted   = '';
$skipDbInserts    = false;

$identity  = getCurrentUserId();
$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value  = $identity['id'];   // raw value — integer for users, session string for guests

//  BRANCH 1: GCash return from PayMongo 
if (isset($_GET['gcash']) && $_GET['gcash'] === 'success' && isset($_GET['token'])) {

    $token = preg_replace('/[^a-f0-9]/', '', $_GET['token']);

    // REFRESH GUARD: order already processed — serve from session cache
    if (isset($_SESSION['completed_order_' . $token])) {
        $c = $_SESSION['completed_order_' . $token];

        $nameParts      = explode(' ', $c['fullName'], 2);
        $fname          = $nameParts[0];
        $lname          = $nameParts[1] ?? '';
        $email          = $c['email'];
        $phone          = $c['phone'];
        $street         = $c['address'];
        $city           = $c['city'];
        $province       = $c['province'];
        $zipCode        = $c['zipCode'];
        $country        = $c['country'];
        $paymentMethod  = 'gcash';
        $paymentTitle   = 'GCash';
        $paymentDetail  = 'Paid via GCash';
        $order_status   = 'pending';
        $purchasedItems = $c['purchasedItems'];
        $subtotal       = (float)$c['subtotal'];
        $shipping_fee   = (float)$c['shipping_fee'];
        $discount_amount= (float)$c['discount_amount'];
        $voucher_code   = $c['voucher_code'];
        $total_amount   = (float)$c['total_amount'];
        $order_id       = $c['order_id'];
        $orderFormatted = $c['orderFormatted'];
        $skipDbInserts  = true;

    } else {
        // FIRST VISIT after PayMongo redirect — read and delete the pending file
        $filePath = sys_get_temp_dir() . '/pending_' . $token . '.json';

        if (!file_exists($filePath)) {
            error_log("orderConfirmation GCash: pending file not found for token $token");
            header('Location: cart.php?error=session_expired');
            exit;
        }

        $pending = json_decode(file_get_contents($filePath), true);
        unlink($filePath);

        // Restore selected items into session so the cart filter works below
        $_SESSION['selected_items'] = $pending['selected_items'];

        $nameParts     = explode(' ', $pending['fullName'], 2);
        $fname         = $nameParts[0];
        $lname         = $nameParts[1] ?? '';
        $email         = $pending['email'];
        $phone         = $pending['phone'];
        $street        = $pending['address'];
        $city          = $pending['city'];
        $province      = $pending['province'];
        $zipCode       = $pending['zipCode'];
        $country       = $pending['country'];
        $voucher_code  = $pending['voucher_code']    ?? '';
        $discount_amount = (float)($pending['discount_amount'] ?? 0);
        $paymentMethod = 'gcash';
        $paymentTitle  = 'GCash';
        $paymentDetail = 'Paid via GCash';
        $order_status  = 'pending';

        // Resolve guest session string → integer guest_id for cart query
        $gc_id_column = $id_column;
        $gc_id_value  = $id_value;

        if ($gc_id_column === 'guest_id') {
            $g = $conn->prepare('SELECT guest_id FROM guests WHERE session_id = ?');
            $g->bind_param('s', $gc_id_value);
            $g->execute();
            $g_row = $g->get_result()->fetch_assoc();
            $g->close();
            if (!$g_row) {
                error_log("orderConfirmation GCash: guest not found for session $gc_id_value");
                header('Location: cart.php?error=session_expired');
                exit;
            }
            $gc_id_value = (int)$g_row['guest_id'];
        } else {
            $gc_id_value = (int)$gc_id_value;
        }
       
        $stmt = $conn->prepare("
            SELECT c.cart_id, c.product_id, c.quantity,
                p.product_name, p.price, pi.image_url
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            LEFT JOIN product_images pi
                ON pi.product_id = p.product_id AND pi.is_primary = 1
            WHERE c.$id_column = ?
        ");
        $stmt->bind_param('i', $gc_id_value);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $purchasedItems = [];
        $subtotal       = 0.0;
        // Cast selected_items to int for reliable in_array comparison
        $selectedIds = array_map('intval', $_SESSION['selected_items']);
        while ($row = $result->fetch_assoc()) {
            if (in_array((int)$row['cart_id'], $selectedIds, true)) {
                $purchasedItems[] = $row;
                $subtotal += (float)$row['price'] * (int)$row['quantity'];
            }
        }

        if (empty($purchasedItems)) {
            error_log("orderConfirmation GCash: no matching cart items found for token $token");
            header('Location: cart.php?error=items_not_found');
            exit;
        }


        $shipping_fee = ($subtotal > 0) ? 150.00 : 0.00;
        $total_amount = max(0.0, $subtotal + $shipping_fee - $discount_amount);
    }

//  BRANCH 2: COD (and initial GCash redirect trigger) via POST 
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    requireCsrf();

    if ($identity['type'] === 'stranger' || empty($_SESSION['selected_items'])) {
        header('Location: cart.php');
        exit;
    }

    $fullName = trim($_POST['fullName'] ?? '');
    $nameParts = explode(' ', $fullName, 2);
    $fname    = $nameParts[0];
    $lname    = $nameParts[1] ?? '';
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $street   = trim($_POST['address']  ?? '');
    $city     = trim($_POST['city']     ?? '');
    $province = trim($_POST['province'] ?? '');
    $zipCode  = trim($_POST['zipCode']  ?? '');
    $country  = trim($_POST['country']  ?? '');

    // Voucher data from hidden form fields (populated by JS when promo is applied)
    $voucher_code    = trim($_POST['voucher_code']    ?? '');
    $discount_amount = max(0.0, (float)($_POST['discount_amount'] ?? 0));

    $validationErrors = [];
    if (empty($fullName)) $validationErrors[] = 'Full name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $validationErrors[] = 'A valid email is required.';
    if (empty($phone) || !preg_match('/^\+?[\d\s\-]{7,15}$/', $phone))
        $validationErrors[] = 'A valid phone number is required (7–15 digits).';
    if (empty($street)) $validationErrors[] = 'Street address is required.';
    if (empty($city))   $validationErrors[] = 'City is required.';
    if (!empty($zipCode) && !preg_match('/^\d{4,10}$/', $zipCode))
        $validationErrors[] = 'Zip code must be numeric (4–10 digits).';

    if (!empty($validationErrors)) {
        $_SESSION['checkout_errors'] = $validationErrors;
        header('Location: checkout.php?error=validation_failed');
        exit;
    }

    $paymentMethod = $_POST['paymentMethod'] ?? 'cod';
    $order_status  = 'pending';

    // Fetch cart items (COD path and GCash pre-redirect path both need this)
    $stmt = $conn->prepare("
        SELECT c.cart_id, c.product_id, c.quantity,
               p.product_name, p.price, pi.image_url
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN product_images pi
               ON pi.product_id = p.product_id AND pi.is_primary = 1
        WHERE c.$id_column = ?
    ");

    $post_id_value = $id_value;
    if ($id_column === 'guest_id') {
        $g = $conn->prepare('SELECT guest_id FROM guests WHERE session_id = ?');
        $g->bind_param('s', $id_value);
        $g->execute();
        $g_row = $g->get_result()->fetch_assoc();
        $g->close();
        $post_id_value = $g_row ? (int)$g_row['guest_id'] : 0;
    } else {
        $post_id_value = (int)$id_value;
    }

    $stmt->bind_param('i', $post_id_value);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $purchasedItems = [];
    $subtotal       = 0.0;
    $selectedIds    = array_map('intval', $_SESSION['selected_items']);
    while ($row = $result->fetch_assoc()) {
        if (in_array((int)$row['cart_id'], $selectedIds, true)) {
            $purchasedItems[] = $row;
            $subtotal += (float)$row['price'] * (int)$row['quantity'];
        }
    }

    $shipping_fee = ($subtotal > 0) ? 150.00 : 0.00;
    $total_amount = max(0.0, $subtotal + $shipping_fee - $discount_amount);

    //  GCash: save everything to a temp file and redirect to PayMongo 
    if ($paymentMethod === 'gcash') {
        $token = bin2hex(random_bytes(16));

        $pendingData = [
            'fullName'        => $fullName,
            'email'           => $email,
            'phone'           => $phone,
            'address'         => $street,
            'city'            => $city,
            'province'        => $province,
            'zipCode'         => $zipCode,
            'country'         => $country,
            'paymentMethod'   => 'gcash',
            'selected_items'  => $_SESSION['selected_items'],
            'voucher_code'    => $voucher_code,
            'discount_amount' => $discount_amount,
        ];

        file_put_contents(
            sys_get_temp_dir() . '/pending_' . $token . '.json',
            json_encode($pendingData)
        );

        $script_dir = dirname($_SERVER['SCRIPT_NAME']);
        $base_url   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST']
            . rtrim(dirname($script_dir), '/');

        $success_url = $base_url . '/pages/orderConfirmation.php?gcash=success&token=' . $token;
        $cancel_url  = $base_url . '/pages/checkout.php?gcash=cancelled';

        // Charge the discounted total to PayMongo
        $checkoutUrl = createPayMongoCheckout(
            $total_amount,
            'Homme d\'Or Order',
            $success_url,
            $cancel_url,
            [
                'name'  => $fullName,
                'email' => $email,
                'phone' => $phone,
            ]
        );

        if ($checkoutUrl) {
            header('Location: ' . $checkoutUrl);
            exit;
        } else {
            header('Location: checkout.php?error=paymongo_failed');
            exit;
        }
    }

    // COD labels (GCash labels set further below after the shared insert block)
    $paymentTitle  = 'Cash on Delivery';
    $paymentDetail = 'Pay when you receive';

} else {
    // Direct GET access with no token — bounce back
    header('Location: cart.php');
    exit;
}

//  DB INSERTS (skipped on page refresh via session guard) 
if (!$skipDbInserts) {

    // Resolve IDs for the INSERT
    // For registered users: db_user_id = integer, db_guest_id = NULL
    // For guests: db_user_id = NULL, db_guest_id = resolved integer
    $db_user_id  = null;
    $db_guest_id = null;

    if ($identity['type'] === 'user_id') {
        $db_user_id = (int)$id_value;
    } else {
        // Resolve guest session string → integer guest_id
        $g = $conn->prepare('SELECT guest_id FROM guests WHERE session_id = ?');
        $g->bind_param('s', $id_value);
        $g->execute();
        $g_row = $g->get_result()->fetch_assoc();
        $g->close();
        $db_guest_id = $g_row ? (int)$g_row['guest_id'] : null;
    }

    //  INSERT order 
    // MySQLi bind_param does not handle PHP null for integer types natively.
    // Use a helper: bind as string 's' when null, integer 'i' when set,
    // or simply pass null directly — PHP 8.1+ handles this, but for older
    // PHP compatibility we use the explicit send_long_data workaround by
    // preparing the statement without those columns when they are null,
    // OR use the simpler approach of always providing both as nullable strings
    // bound with 'i' which PHP's MySQLi accepts as NULL for integer columns.
    //
    // The safest cross-version approach: include both columns, bind as 'i',
    // pass null — PHP MySQLi translates PHP null → SQL NULL for numeric types
    // in bind_param as of PHP 5.3+. This is reliable in practice.

    $insertOrder = $conn->prepare("
        INSERT INTO orders (
            user_id, guest_id,
            fname, lname, email, phone,
            street, city, province, country, zip_code,
            subtotal, shipping_fee, discount_amount, total_amount,
            order_status
        ) VALUES (
            ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?
        )
    ");

    if (!$insertOrder) {
        error_log('orderConfirmation: prepare failed — ' . $conn->error);
        header('Location: checkout.php?error=order_failed');
        exit;
    }

    // Type string breakdown (16 params):
    //   i  i   — user_id (nullable int), guest_id (nullable int)
    //   s  s  s  s   — fname, lname, email, phone
    //   s  s  s  s  s   — street, city, province, country, zip_code
    //   d  d  d  d   — subtotal, shipping_fee, discount_amount, total_amount
    //   s   — order_status
    $insertOrder->bind_param(
        'iisssssssssdddds',
        $db_user_id,    $db_guest_id,
        $fname,         $lname,         $email,    $phone,
        $street,        $city,          $province, $country, $zipCode,
        $subtotal,      $shipping_fee,  $discount_amount, $total_amount,
        $order_status
    );

    if (!$insertOrder->execute()) {
        error_log('orderConfirmation: order insert failed — ' . $insertOrder->error
            . ' | user=' . ($db_user_id ?? 'null')
            . ' guest=' . ($db_guest_id ?? 'null')
            . ' method=' . $paymentMethod);
        $insertOrder->close();
        // For GCash: redirect rather than die() so the user gets a useful page
        header('Location: checkout.php?error=order_failed');
        exit;
    }
    $insertOrder->close();

    $order_id       = (int)$conn->insert_id;
    $orderFormatted = str_pad($order_id, 6, '0', STR_PAD_LEFT);

    // Insert order placed notification
    if ($db_user_id) {
        insertNotif($conn, $db_user_id, 'order_status',
            "Your order #{$orderFormatted} has been placed successfully!", $order_id);
    } elseif ($db_guest_id) {
        $gs = $conn->prepare("SELECT session_id FROM guests WHERE guest_id = ?");
        $gs->bind_param("i", $db_guest_id);
        $gs->execute();
        $gs_row = $gs->get_result()->fetch_assoc();
        $gs->close();
        if ($gs_row) {
            $notif_msg = mysqli_real_escape_string($conn,
                "Your order #{$orderFormatted} has been placed successfully!");
            $session_id = mysqli_real_escape_string($conn, $gs_row['session_id']);
            mysqli_query($conn, "
                INSERT INTO guest_notifications (session_id, notif_type, notif_message, reference_id, is_read, created_at)
                VALUES ('$session_id', 'order_status', '$notif_msg', $order_id, 0, NOW())
            ");
        }
    }

    //  INSERT payment record 
    $db_method         = ($paymentMethod === 'gcash') ? 'gcash' : 'cod';
    $db_payment_status = ($paymentMethod === 'cod')   ? 'pending' : 'paid';
    $db_paid_at        = ($paymentMethod !== 'cod')   ? date('Y-m-d H:i:s') : null;

    $insertPayment = $conn->prepare(
        'INSERT INTO payments (order_id, method, payment_status, paid_at)
         VALUES (?, ?, ?, ?)'
    );
    if ($insertPayment) {
        $insertPayment->bind_param('isss', $order_id, $db_method, $db_payment_status, $db_paid_at);
        $insertPayment->execute();
        $insertPayment->close();
    } else {
        error_log('orderConfirmation: payments prepare failed — ' . $conn->error);
    }

    //  INSERT order items 
    $stmtItem = $conn->prepare(
        'INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase)
         VALUES (?, ?, ?, ?)'
    );
    if ($stmtItem) {
        foreach ($purchasedItems as $item) {
            $stmtItem->bind_param('iiid',
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            );
            $stmtItem->execute();
        }
        $stmtItem->close();
    } else {
        error_log('orderConfirmation: order_items prepare failed — ' . $conn->error);
    }

    //  Clear selected_items session flag 
    unset($_SESSION['selected_items']);

    //  Delete purchased items from cart 
    if (!empty($purchasedItems)) {
        $cartIds            = array_column($purchasedItems, 'cart_id');
        $deletePlaceholders = implode(',', array_fill(0, count($cartIds), '?'));
        $deleteCart         = $conn->prepare(
            "DELETE FROM cart WHERE cart_id IN ($deletePlaceholders)"
        );
        if ($deleteCart) {
            $deleteCart->bind_param(str_repeat('i', count($cartIds)), ...$cartIds);
            $deleteCart->execute();
            $deleteCart->close();
        } else {
            error_log('orderConfirmation: cart delete prepare failed — ' . $conn->error);
        }
    }

    //  Deduct stock (cancel order if any item is out of stock) 
    $updateStock = $conn->prepare(
        'UPDATE products SET stock_qty = stock_qty - ?
         WHERE product_id = ? AND stock_qty >= ?'
    );
    if ($updateStock) {
        foreach ($purchasedItems as $item) {
            $updateStock->bind_param('iii',
                $item['quantity'],
                $item['product_id'],
                $item['quantity']
            );
            $updateStock->execute();
            if ($updateStock->affected_rows === 0) {
                $conn->query(
                    "UPDATE orders
                     SET order_status = 'cancelled',
                         cancellation_reason = 'Out of stock at time of order'
                     WHERE order_id = $order_id"
                );
                // Re-add items to cart so user doesn't lose their selection
                // Use the resolved integer IDs stored in purchasedItems' own cart
                // structure — re-insert using the appropriate id column.
                $reAddCol = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
                $reAddId  = ($identity['type'] === 'user_id')
                    ? $db_user_id
                    : $db_guest_id;
                if ($reAddId) {
                    $reAdd = $conn->prepare(
                        "INSERT INTO cart ($reAddCol, product_id, quantity) VALUES (?, ?, ?)
                         ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)"
                    );
                    if ($reAdd) {
                        foreach ($purchasedItems as $ri) {
                            $reAdd->bind_param('iii', $reAddId, $ri['product_id'], $ri['quantity']);
                            $reAdd->execute();
                        }
                        $reAdd->close();
                    }
                }
                header('Location: cart.php?error=out_of_stock');
                exit;
            }
        }
        $updateStock->close();
    } else {
        error_log('orderConfirmation: stock update prepare failed — ' . $conn->error);
    }

    //  Send confirmation email 
    $emailSent = false;
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        $emailSent = sendOrderEmail(
            $email, $fname, $orderFormatted,
            $purchasedItems, $total_amount, $paymentMethod
        );
    }
    if (!$emailSent) {
        error_log("orderConfirmation: email failed for order #$order_id");
    }

    //  Cache in session so page refresh doesn't re-insert (GCash only) 
    if ($paymentMethod === 'gcash' && !empty($token)) {
        $_SESSION['completed_order_' . $token] = [
            'fullName'        => trim($fname . ' ' . $lname),
            'email'           => $email,
            'phone'           => $phone,
            'address'         => $street,
            'city'            => $city,
            'province'        => $province,
            'zipCode'         => $zipCode,
            'country'         => $country,
            'purchasedItems'  => $purchasedItems,
            'subtotal'        => $subtotal,
            'shipping_fee'    => $shipping_fee,
            'discount_amount' => $discount_amount,
            'voucher_code'    => $voucher_code,
            'total_amount'    => $total_amount,
            'order_id'        => $order_id,
            'orderFormatted'  => $orderFormatted,
        ];
    }
}

//  Resolve display labels (GCash labels apply to both branches) 
if ($paymentMethod === 'gcash') {
    $paymentTitle  = 'GCash';
    $paymentDetail = 'Paid via GCash';
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

            <div class="confirmation-actions" style="text-align: center; padding: 2rem; margin-top: 1rem;">
                <a href="index.php" class="btn-shop-again" style="display: inline-block; padding: 0.8rem 2rem; margin: 0.5rem; background: #c9a961; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-shopping-bag"></i> Shop Again
                </a>
                <a href="viewAllTabs.php" class="btn-view-order" style="display: inline-block; padding: 0.8rem 2rem; margin: 0.5rem; background: transparent; border: 1px solid #c9a961; color: #c9a961; text-decoration: none; border-radius: 4px; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-eye"></i> View Order
                </a>
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
                            <p><?= htmlspecialchars(trim($fname . ' ' . $lname)) ?></p>
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
                                <p>Qty: <?= (int)$item['quantity'] ?></p>
                            </div>
                            <div class="r-item-price">
                                ₱<?= number_format((float)$item['price'] * (int)$item['quantity'], 2) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="receipt-totals">
                        <div class="r-row">
                            <span>Subtotal</span>
                            <span>₱<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="r-row">
                            <span>Shipping</span>
                            <span>₱<?= number_format($shipping_fee, 2) ?></span>
                        </div>
                        <?php if ($discount_amount > 0): ?>
                        <div class="r-row" style="color: #c9a961;">
                            <span>Discount<?= $voucher_code
                                ? ' (' . htmlspecialchars($voucher_code) . ')'
                                : '' ?></span>
                            <span>- ₱<?= number_format($discount_amount, 2) ?></span>
                        </div>
                        <?php endif; ?>
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
            document.querySelectorAll('.modal').forEach(function (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show', 'closing');
            });
            document.body.style.overflow   = 'auto';
            document.body.style.overflowX  = 'hidden';
        });
    </script>
</body>
</html>