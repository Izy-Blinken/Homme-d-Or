<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../backend/db_connect.php';

$order    = null;
$items    = [];
$error    = '';
$searched = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searched   = true;
    $order_id   = intval($_POST['order_id'] ?? 0);
    $email_input = trim($_POST['email'] ?? '');

    if (!$order_id || empty($email_input)) {
        $error = 'Please enter both your Order ID and email address.';
    } else {
        $stmt = $conn->prepare("
            SELECT o.*, p.method AS payment_method, p.payment_status
            FROM orders o
            LEFT JOIN payments p ON p.order_id = o.order_id
            WHERE o.order_id = ? AND o.email = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $order_id, $email_input);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$order) {
            $error = 'No order found with that ID and email combination.';
        } else {
            $itemStmt = $conn->prepare("
                SELECT oi.*, pr.product_name, pi.image_url
                FROM order_items oi
                JOIN products pr ON pr.product_id = oi.product_id
                LEFT JOIN product_images pi ON pi.product_id = pr.product_id AND pi.is_primary = 1
                WHERE oi.order_id = ?
            ");
            $itemStmt->bind_param("i", $order['order_id']);
            $itemStmt->execute();
            $items = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $itemStmt->close();
        }
    }
}

$statusSteps = ['pending', 'paid', 'shipped', 'delivered', 'received', 'completed'];
$currentStep = $order ? array_search(strtolower($order['order_status']), $statusSteps) : -1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order — Homme d'Or</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/CheckoutPageStyle.css">
    <style>
        .track-wrapper { max-width: 700px; margin: 3rem auto; padding: 0 1rem; }
        .track-form-card { background: #1a1a1a; border: 1px solid #333; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; }
        .track-form-card h2 { color: #c9a961; margin-bottom: 1.5rem; }
        .track-row { display: flex; gap: 1rem; flex-wrap: wrap; }
        .track-field { flex: 1; min-width: 200px; }
        .track-field label { display: block; color: #aaa; margin-bottom: 0.4rem; font-size: 0.9rem; }
        .track-field input { width: 100%; padding: 0.7rem; background: #111; border: 1px solid #444; color: #fff; border-radius: 4px; }
        .track-btn { margin-top: 1.2rem; padding: 0.75rem 2rem; background: #c9a961; color: #000; border: none; font-weight: 700; border-radius: 4px; cursor: pointer; }
        .track-error { color: #e74c3c; margin-top: 1rem; }
        .result-card { background: #1a1a1a; border: 1px solid #444; padding: 2rem; border-radius: 8px; }
        .result-card h3 { color: #c9a961; margin-bottom: 1rem; }
        .status-row { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .step-dot { width: 28px; height: 28px; border-radius: 50%; background: #333; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #aaa; flex-shrink: 0; }
        .step-dot.done { background: #c9a961; color: #000; }
        .step-line { flex: 1; height: 2px; background: #333; min-width: 15px; }
        .step-line.done { background: #c9a961; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin: 1rem 0; }
        .info-block label { font-size: 0.8rem; color: #888; display: block; }
        .info-block p { color: #ddd; margin: 0.2rem 0 0; }
        .item-row { display: flex; gap: 1rem; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #2a2a2a; }
        .item-row img { width: 55px; height: 55px; object-fit: cover; border-radius: 4px; }
        .item-name { flex: 1; color: #ddd; }
        .item-price { color: #c9a961; font-weight: 600; }
        .badge-cancelled { color: #e74c3c; }
        .badge-completed { color: #2ecc71; }
    </style>
</head>
<body>
    <?php include '../components/header.php'; ?>
    <main class="mainBG">
        <div class="track-wrapper">
            <div class="track-form-card">
                <h2><i class="fas fa-search"></i> Track Your Order</h2>
                <p style="color:#aaa; margin-bottom:1.5rem;">Enter your order ID and the email address used at checkout.</p>
                <form method="POST" action="">
                    <div class="track-row">
                        <div class="track-field">
                            <label>Order ID</label>
                            <input type="number" name="order_id" placeholder="e.g. 1042"
                                value="<?= htmlspecialchars($_POST['order_id'] ?? '') ?>" required>
                        </div>
                        <div class="track-field">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="e.g. you@email.com"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="track-btn"><i class="fas fa-search"></i> Track Order</button>
                    <?php if ($searched && $error): ?>
                        <p class="track-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                </form>
            </div>

            <?php if ($order): ?>
            <div class="result-card">
                <h3>Order #<?= str_pad($order['order_id'], 6, '0', STR_PAD_LEFT) ?></h3>

                <?php if ($order['order_status'] !== 'cancelled'): ?>
                <div class="status-row">
                    <?php foreach ($statusSteps as $i => $step): ?>
                        <div class="step-dot <?= $i <= $currentStep ? 'done' : '' ?>" title="<?= ucfirst($step) ?>">
                            <?= $i < $currentStep ? '<i class="fas fa-check" style="font-size:10px"></i>' : ($i === $currentStep ? '<i class="fas fa-circle" style="font-size:8px"></i>' : '') ?>
                        </div>
                        <?php if ($i < count($statusSteps) - 1): ?>
                        <div class="step-line <?= $i < $currentStep ? 'done' : '' ?>"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <p style="color:#c9a961; font-weight:600; margin-bottom:1.5rem;">
                    Status: <?= ucfirst($order['order_status']) ?>
                </p>
                <?php else: ?>
                <p class="badge-cancelled" style="font-weight:600; margin-bottom:1.5rem;">
                    <i class="fas fa-times-circle"></i> Order Cancelled
                    <?= $order['cancellation_reason'] ? ' — Reason: ' . htmlspecialchars($order['cancellation_reason']) : '' ?>
                </p>
                <?php endif; ?>

                <div class="info-grid">
                    <div class="info-block">
                        <label>Name</label>
                        <p><?= htmlspecialchars($order['fname'] . ' ' . $order['lname']) ?></p>
                    </div>
                    <div class="info-block">
                        <label>Email</label>
                        <p><?= htmlspecialchars($order['email']) ?></p>
                    </div>
                    <div class="info-block">
                        <label>Order Date</label>
                        <p><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                    </div>
                    <div class="info-block">
                        <label>Payment</label>
                        <p><?= ucfirst($order['payment_method'] ?? 'N/A') ?></p>
                    </div>
                    <div class="info-block">
                        <label>Shipping Address</label>
                        <p><?= htmlspecialchars(($order['street'] ?? '') . ', ' . ($order['city'] ?? '') . ', ' . ($order['province'] ?? '')) ?></p>
                    </div>
                </div>

                <h3 style="margin-top:1.5rem;">Items Ordered</h3>
                <?php foreach ($items as $item):
                    $imgSrc = $item['image_url']
                        ? '../assets/images/products/' . htmlspecialchars($item['image_url'])
                        : '../assets/images/brand_images/nocturne.png';
                ?>
                <div class="item-row">
                    <img src="<?= $imgSrc ?>" alt="Product">
                    <div class="item-name">
                        <?= htmlspecialchars($item['product_name']) ?><br>
                        <small style="color:#888;">Qty: <?= $item['quantity'] ?></small>
                    </div>
                    <div class="item-price">₱<?= number_format($item['price_at_purchase'] * $item['quantity'], 2) ?></div>
                </div>
                <?php endforeach; ?>

                <div style="margin-top:1.5rem; text-align:right;">
                    <p style="color:#aaa;">Subtotal: ₱<?= number_format($order['subtotal'], 2) ?></p>
                    <p style="color:#aaa;">Shipping: ₱<?= number_format($order['shipping_fee'], 2) ?></p>
                    <p style="color:#c9a961; font-size:1.1rem; font-weight:700;">Total: ₱<?= number_format($order['total_amount'], 2) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../components/footer.php'; ?>
</body>
</html>