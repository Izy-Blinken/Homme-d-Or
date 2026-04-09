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
$id_value = $identity['id'];

// Initialize selection session if it doesn't exist
if (!isset($_SESSION['selected_items'])) {
    $_SESSION['selected_items'] = [];
}

// HANDLE FORM SUBMISSIONS (Update Qty, Remove, Toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);

    if (isset($_POST['increase'])) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE cart_id = ? AND $id_column = ?");
        $stmt->bind_param("is", $cart_id, $id_value);
        $stmt->execute();
    }
    if (isset($_POST['decrease'])) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE cart_id = ? AND $id_column = ?");
        $stmt->bind_param("is", $cart_id, $id_value);
        $stmt->execute();
    }
    if (isset($_POST['remove'])) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND $id_column = ?");
        $stmt->bind_param("is", $cart_id, $id_value);
        $stmt->execute();
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
$sql = "SELECT c.cart_id, c.quantity, p.product_name, p.price, pi.image_url
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        WHERE c.$id_column = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_value);
$stmt->execute();
$result = $stmt->get_result();

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
                    $subtotal = 0;
                    $selectedCount = 0;
                    if (empty($cartItems)) {
                        echo "<div class='empty-cart-msg'>Your cart is completely empty. Time to find a new signature scent!</div>";
                    }
                    foreach($cartItems as $item):
                        $isSelected = in_array($item['cart_id'], $_SESSION['selected_items']);
                        $totalPrice = $item['price'] * $item['quantity'];
                        if ($isSelected) {
                            $subtotal += $totalPrice;
                            $selectedCount++;
                        }
                        $imgSrc = $item['image_url'] ? '../assets/images/products/' . htmlspecialchars($item['image_url']) : '../assets/images/brand_images/nocturne.png';
                    ?>
                    <div class="cart-item" style="<?php echo !$isSelected ? 'opacity: 0.4; filter: grayscale(50%);' : ''; ?>">
                        <form method="POST" style="margin-right: 35px; margin-top: 5px;">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <input type="hidden" name="toggle_select" value="1">
                            <label class="custom-checkbox">
                                <input type="checkbox" onChange="this.form.submit()" <?php echo $isSelected ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                            </label>
                        </form>
                        <img src="<?php echo $imgSrc; ?>" alt="Perfume">
                        <div class="cart-info">
                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                            <div class="cart-actions">
                                <form method="POST" class="qty-form">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="decrease">-</button>
                                    <span><?php echo $item['quantity']; ?></span>
                                    <button type="submit" name="increase">+</button>
                                </form>
                                <form method="POST" class="remove-form">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="remove" class="remove-item">Remove</button>
                                </form>
                            </div>
                        </div>
                        <div class="cart-price">₱<?php echo number_format($totalPrice, 2); ?></div>
                    </div>
                    <?php endforeach; ?>
                </section>
                <aside class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row"><span>Subtotal (<?php echo $selectedCount; ?> items)</span><span>₱<?php echo number_format($subtotal, 2); ?></span></div>
                    <div class="summary-row"><span>Shipping</span><span>₱<?php echo $selectedCount > 0 ? '150.00' : '0.00'; ?></span></div>
                    <div class="summary-row total"><span>Total</span><span>₱<?php echo $selectedCount > 0 ? number_format($subtotal + 150, 2) : '0.00'; ?></span></div>
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