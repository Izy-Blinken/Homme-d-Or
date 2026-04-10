<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';

$identity = getCurrentUserId();

// Redirect strangers
if ($identity['type'] === 'stranger') {
    header("Location: index.php?login_required=true");
    exit;
}

$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value = $identity['id'];

// Fetch all orders for this user with payment info
$stmt = $conn->prepare("
    SELECT o.*, p.method, p.payment_status, p.paid_at
    FROM orders o
    LEFT JOIN payments p ON p.order_id = o.order_id
    WHERE o.$id_column = ?
    ORDER BY o.created_at DESC
");
$stmt->bind_param("s", $id_value);
$stmt->execute();
$ordersResult = $stmt->get_result();

$orders = [];
while ($row = $ordersResult->fetch_assoc()) {
    $orders[] = $row;
}

// Fetch order items for each order
function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT oi.*, p.product_name, pi.image_url
        FROM order_items oi
        JOIN products p ON p.product_id = oi.product_id
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Categorize orders by status
$processing = [];
$toReview   = [];
$completed  = [];
$cancelled  = [];

foreach ($orders as $order) {
    $status = strtolower($order['order_status'] ?? 'pending');
    
    if ($status === 'cancelled') {
        $cancelled[] = $order;
    } elseif ($status === 'completed') {
        $completed[] = $order;
    } elseif ($status === 'received') {
        $toReview[] = $order;
    } elseif (in_array($status, ['pending', 'paid', 'shipped', 'delivered'])) {
        $processing[] = $order;
    }
}

// Helper to render order rows
function renderOrders($conn, $orders, $tabType) {
    if (empty($orders)) {
        echo '<p style="color:#aaa; text-align:center; padding: 40px 0;">No orders found.</p>';
        return;
    }

    foreach ($orders as $order) {
        $items     = getOrderItems($conn, $order['order_id']);
        $orderNum  = str_pad($order['order_id'], 6, '0', STR_PAD_LEFT);
        $date      = date('F j, Y', strtotime($order['created_at']));
        $method    = ucfirst($order['method'] ?? 'N/A');
        $status    = $order['order_status'];
        $total     = '₱' . number_format($order['total_amount'], 2);

        foreach ($items as $item) {
            $imgSrc = $item['image_url']
                ? '../assets/images/products/' . htmlspecialchars($item['image_url'])
                : '../assets/images/brand_images/nocturne.png';
            $productName = htmlspecialchars($item['product_name']);
            $qty         = $item['quantity'];
            $price       = '₱' . number_format($item['price_at_purchase'], 2);
            $subtotal    = '₱' . number_format($item['price_at_purchase'] * $item['quantity'], 2);

            // Escape for JS onclick
            $jsImg     = addslashes($imgSrc);
            $jsName    = addslashes($productName);
            $jsQty     = $qty;
            $jsTotal   = addslashes($total);
            $jsMethod  = addslashes($method);
            $jsDate    = addslashes($date);
            $jsStatus  = addslashes($status);
            $jsOrderId = addslashes($orderNum);
            ?>
            <div class="v-orders">
                <div class="v-left">
                    <img src="<?= $imgSrc ?>" alt="" width="60">
                    <div class="v-ordersinfo">
                        <p class="v-name"><?= $productName ?></p>
                        <small class="v-desc">Order #<?= $orderNum ?> &bull; Qty: <?= $qty ?></small>
                        <small style="display: block; margin-top: 5px; color: #c9a961;">
                            <span class="badge badge-<?= strtolower($status) ?>" style="padding: 4px 8px; border-radius: 3px; font-size: 0.75rem;">
                                <?= ucfirst($status) ?>
                            </span>
                        </small>
                    </div>
                </div>
                <div class="v-right">
                    <p class="v-price"><?= $total ?></p>
                    <div class="v-actions">
                        <button class="v-view btn-open"
                            onclick="openViewModal(
                                '<?= $jsImg ?>',
                                '<?= $jsName ?>',
                                'Order #<?= $jsOrderId ?>',
                                <?= $jsQty ?>,
                                '<?= $jsTotal ?>',
                                '<?= $jsMethod ?>',
                                '<?= $jsDate ?>',
                                '<?= $jsStatus ?>'
                            )">View</button>

                        <?php if ($tabType === 'processing' && strtolower($status) === 'pending'): ?>
                            <button class="v-cancel btn-open"
                                onclick="openCancelModal(<?= $order['order_id'] ?>)">Cancel Order</button>

                        <?php elseif ($tabType === 'review'): ?>
                            <button class="v-rate btn-open"
                                onclick="openReviewModal(<?= $order['order_id'] ?>, <?= $item['product_id'] ?>)">Rate Order</button>

                        <?php elseif ($tabType === 'completed' || $tabType === 'cancelled'): ?>
                            <button class="v-again"
                                onclick="window.location.href='shop.php'">Order Again</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme d'Or - My Orders</title>
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
    <link rel="stylesheet" href="../assets/css/viewTabs.css">
    <link rel="stylesheet" href="../assets/css/ReviewCancelOrderStyle.css">
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="mainBG">
        <button class="back-btn" onclick="history.back()" title="Go back"><i class="fas fa-arrow-left"></i> Back</button>
        <div class="v-tabs">
            <h1 class="v-header">My Orders</h1>

            <div class="order-tabs">
                <button class="tab-btn active" data-tab="processing">Processing</button>
                <button class="tab-btn" data-tab="review">To Review</button>
                <button class="tab-btn" data-tab="completed">Completed</button>
                <button class="tab-btn" data-tab="cancelled">Cancelled</button>
            </div>

            <div class="tab-content active" id="processing">
                <?php renderOrders($conn, $processing, 'processing'); ?>
            </div>

            <div class="tab-content" id="review">
                <?php renderOrders($conn, $toReview, 'review'); ?>
            </div>

            <div class="tab-content" id="completed">
                <?php renderOrders($conn, $completed, 'completed'); ?>
            </div>

            <div class="tab-content" id="cancelled">
                <?php renderOrders($conn, $cancelled, 'cancelled'); ?>
            </div>

        </div>
    </main>

    <!-- Cancel Order Modal -->
    <div id="cancelOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <h2>Cancel Order</h2>
            </div>
            <div class="romcomDivider"></div>
            <form class="romcomBody" onsubmit="submitCancellation(event)">
                <input type="hidden" id="cancelOrderId" value="">
                <p class="modal-description">Please select a reason for cancellation</p>
                <div class="romcomFormGroup">
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Found a better price elsewhere" required>
                            <span class="radio-label">Found a better price elsewhere</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Changed my mind" required>
                            <span class="radio-label">Changed my mind</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Ordered by mistake" required>
                            <span class="radio-label">Ordered by mistake</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Delivery taking too long" required>
                            <span class="radio-label">Delivery taking too long</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Product no longer needed" required>
                            <span class="radio-label">Product no longer needed</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Other" required>
                            <span class="radio-label">Other</span>
                        </label>
                    </div>
                </div>
                <div class="romcomFormGroup" id="otherReasonGroup" style="display: none;">
                    <label for="otherReason">ADDITIONAL DETAILS</label>
                    <textarea id="otherReason" placeholder="Please specify your reason..."></textarea>
                </div>
                <div class="romcomButtonGroup">
                    <button type="button" class="romcomBtnClose" onclick="closeCancelModal()">Keep Order</button>
                    <button type="submit" class="romcomBtnSubmit">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Order Modal -->
    <div id="reviewOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <h2>Submit a Review</h2>
            </div>
            <div class="romcomDivider"></div>
            <form class="romcomBody" onsubmit="submitReview(event)">
                <input type="hidden" id="reviewOrderId" value="">
                <input type="hidden" id="reviewProductId" value="">
                <div class="romcomFormGroup">
                    <label>RATING</label>
                    <div class="starRating">
                        <span class="star" onclick="setRating(1)" onmouseover="hoverRating(1)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                        <span class="star" onclick="setRating(2)" onmouseover="hoverRating(2)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span> <span class="star" onclick="setRating(3)" onmouseover="hoverRating(3)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                        <span class="star" onclick="setRating(4)" onmouseover="hoverRating(4)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                        <span class="star" onclick="setRating(5)" onmouseover="hoverRating(5)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                    </div>
                    <p class="rating-text" id="ratingText"></p>
                </div>
                <div class="romcomFormGroup">
                    <label for="reviewText">YOUR REVIEW</label>
                    <textarea id="reviewText" placeholder="Share your thoughts about this product..."></textarea>
                </div>
                <div class="romcomButtonGroup">
                    <button type="button" class="romcomBtnClose" onclick="closeReviewModal()">Cancel</button>
                    <button type="submit" class="romcomBtnSubmit" id="submitReviewBtn" disabled>Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Order Modal -->
    <div id="viewOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <span class="view-close-btn" onclick="closeViewModal()">&times;</span>
                <h2>Order Details</h2>
            </div>
            <div class="romcomDivider"></div>
            <div class="romcomBody">
                <div class="view-section">
                    <h4>PRODUCT</h4>
                    <div class="view-product">
                        <img id="viewImage" src="" width="70" alt="Product Image">
                        <div>
                            <p id="viewName"></p>
                            <small id="viewVariant" style="color: gray; font-weight:bold;"></small>
                        </div>
                    </div>
                </div>
                <div class="view-section">
                    <h4>ORDER INFORMATION</h4>
                    <p><strong style="color: gray;">Quantity:</strong> <span id="viewQty"></span></p>
                    <p><strong style="color: gray;">Total:</strong> <span id="viewTotal"></span></p>
                    <p><strong style="color: gray;">Payment Method:</strong> <span id="viewPayment"></span></p>
                    <p><strong style="color: gray;">Order Date:</strong> <span id="viewDate"></span></p>
                    <p><strong style="color: gray;">Status:</strong> <span id="viewStatus"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div id="generalToast" class="generalToast"></div>
    <script src="../assets/js/script.js"></script>

    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/viewAllTabs.js"></script>
    <script src="../assets/js/ReviewCancelOrder.js"></script>

    <script>
        // Pass order_id into cancel modal
        function openCancelModal(orderId) {
            document.getElementById('cancelOrderId').value = orderId;
            const modal = document.getElementById('cancelOrderModal');
            modal.style.display = 'flex';
        }

        // Pass order_id and product_id into review modal
        function openReviewModal(orderId, productId) {
            document.getElementById('reviewOrderId').value = orderId;
            document.getElementById('reviewProductId').value = productId;
            const modal = document.getElementById('reviewOrderModal');
            modal.style.display = 'flex';
        }
    </script>
</body>
</html>