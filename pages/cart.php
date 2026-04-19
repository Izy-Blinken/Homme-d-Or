<?php
// WAKE UP THE SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';
$identity = getCurrentUserId();

// THE BOUNCER: Kick complete strangers back to the homepage
if ($identity['type'] === 'stranger') {
    header("Location: index.php?login_required=true");
    exit;
}

$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value  = $identity['id'];
// FIX Issue 2: bind_type must reflect the resolved type for BOTH users and
// guests.  Previously it stayed 's' for logged-in users, causing every
// UPDATE/DELETE to silently fail because user_id is an integer column.
$bind_type = 'i';   // default — users always have an integer id

// Guests: resolve session string → real integer guest_id
if ($id_column === 'guest_id') {
    $g = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
    $g->bind_param("s", $id_value);
    $g->execute();
    $g_result = $g->get_result();
    $g->close();
    if ($g_result->num_rows === 0) {
        $id_value  = 0;
        // bind_type stays 'i'
    } else {
        $id_value  = $g_result->fetch_assoc()['guest_id'];
        // bind_type stays 'i'
    }
}

// Initialize selection session if it doesn't exist
if (!isset($_SESSION['selected_items'])) {
    $_SESSION['selected_items'] = [];
}

// HANDLE FORM SUBMISSIONS (Update Qty, Remove, Toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);

    if (isset($_POST['increase'])) {
        // FIX Issue 2: cap quantity at available stock before incrementing
        $stockStmt = $conn->prepare(
            "SELECT p.stock_qty, c.quantity
             FROM cart c
             JOIN products p ON c.product_id = p.product_id
             WHERE c.cart_id = ? AND c.$id_column = ?"
        );
        $stockStmt->bind_param("i{$bind_type}", $cart_id, $id_value);
        $stockStmt->execute();
        $stockRow = $stockStmt->get_result()->fetch_assoc();
        $stockStmt->close();

        if ($stockRow && (int)$stockRow['quantity'] < (int)$stockRow['stock_qty']) {
            $stmt = $conn->prepare(
                "UPDATE cart SET quantity = quantity + 1
                 WHERE cart_id = ? AND $id_column = ?"
            );
            $stmt->bind_param("i{$bind_type}", $cart_id, $id_value);
            $stmt->execute();
            $stmt->close();
        }
        // If already at stock limit, silently do nothing (quantity unchanged)
    }

    if (isset($_POST['decrease'])) {
        // Quantity floor is 1 — GREATEST(quantity - 1, 1) ensures it
        $stmt = $conn->prepare(
            "UPDATE cart SET quantity = GREATEST(quantity - 1, 1)
             WHERE cart_id = ? AND $id_column = ?"
        );
        $stmt->bind_param("i{$bind_type}", $cart_id, $id_value);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['remove'])) {
        $stmt = $conn->prepare(
            "DELETE FROM cart WHERE cart_id = ? AND $id_column = ?"
        );
        $stmt->bind_param("i{$bind_type}", $cart_id, $id_value);
        $stmt->execute();
        $stmt->close();
        if (($key = array_search($cart_id, $_SESSION['selected_items'])) !== false) {
            unset($_SESSION['selected_items'][$key]);
        }
    }

    if (isset($_POST['toggle_select'])) {
        if (in_array($cart_id, $_SESSION['selected_items'])) {
            $key = array_search($cart_id, $_SESSION['selected_items']);
            unset($_SESSION['selected_items'][$key]);
        } else {
            $_SESSION['selected_items'][] = $cart_id;
        }
    }

    header("Location: cart.php");
    exit;
}

// FETCH CART ITEMS FROM DATABASE
$sql = "SELECT c.cart_id, c.quantity, p.product_name, p.price, p.stock_qty, pi.image_url
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        WHERE c.$id_column = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param($bind_type, $id_value);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    if (!isset($_SESSION['initialized_cart_selections'])) {
        $_SESSION['selected_items'][] = $row['cart_id'];
    }
}
$_SESSION['initialized_cart_selections'] = true;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart | Homme d'Or</title>
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
            <a href="javascript:history.back()" class="back-button"><i class="fa-solid fa-chevron-left"></i></a>
            <section class="cart-page">
            <h1 class="cart-title">Shopping Cart</h1>
            <div class="cart-wrapper">
                <section class="cart-items">
                    <?php
                    
                    $subtotal      = 0.0;
                    $selectedCount = 0;
                    $totalQty      = 0;

                    if (empty($cartItems)) {
                        echo "<div class='empty-cart-msg'>Your cart is completely empty. Time to find a new signature scent!</div>";
                    }

                    foreach ($cartItems as $item):
                        $isSelected = in_array($item['cart_id'], $_SESSION['selected_items']);
                        $itemTotal  = (float)$item['price'] * (int)$item['quantity'];
                        if ($isSelected) {
                            $subtotal += $itemTotal;
                            $selectedCount++;
                            $totalQty += (int)$item['quantity'];
                        }
                        $imgSrc = $item['image_url']
                            ? '../assets/images/products/' . htmlspecialchars($item['image_url'])
                            : '../assets/images/brand_images/nocturne.png';

                            $atStockLimit = ((int)$item['quantity'] >= (int)$item['stock_qty']);
                    ?>
                    <div class="cart-item" style="<?= !$isSelected ? 'opacity: 0.4; filter: grayscale(50%);' : '' ?>">
                        <form method="POST" style="margin-right: 35px; margin-top: 5px;">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <input type="hidden" name="toggle_select" value="1">
                            <label class="custom-checkbox">
                                <input type="checkbox" onChange="this.form.submit()" <?= $isSelected ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                            </label>
                        </form>
                        <img src="<?= $imgSrc ?>" alt="Perfume">
                        <div class="cart-info">
                            <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                            <div class="cart-actions">
                                <form method="POST" class="qty-form">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                    <!-- Disable + when already at stock limit -->
                                    <button type="submit" name="decrease">-</button>
                                    <span><?= (int)$item['quantity'] ?></span>
                                    <button type="submit" name="increase"
                                        <?= $atStockLimit ? 'disabled title="Maximum stock reached"' : '' ?>>+</button>
                                </form>
                                <form method="POST" class="remove-form">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                    <button type="submit" name="remove" class="remove-item">Remove</button>
                                </form>
                            </div>
                        </div>
                        <div class="cart-price">₱<?= number_format($itemTotal, 2) ?></div>
                    </div>
                    <?php endforeach; ?>
                </section>

                <?php
                // FIX Issue 1: shipping and total use the same formula as
                // checkout.php and orderConfirmation.php — no hardcoded literals
                // in the HTML template below.
                $shipping_fee   = ($selectedCount > 0) ? 150.00 : 0.00;
                $cart_total     = $subtotal + $shipping_fee;
                ?>
                <aside class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal (<?= $totalQty ?> items)</span>
                        <span>₱<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>₱<?= number_format($shipping_fee, 2) ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₱<?= number_format($cart_total, 2) ?></span>
                    </div>
                    <?php if ($selectedCount > 0): ?>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="#" class="checkout-btn" style="opacity: 0.5; cursor: not-allowed;" onclick="return false;">Select Items to Checkout</a>
                    <?php endif; ?>
                </aside>
            </div>
            </section>
        </main>
        <?php include '../components/footer.php'; ?>
        <script src="../assets/js/cart.js"></script>
    </body>
</html>