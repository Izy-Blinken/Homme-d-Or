<?php
session_start();
include_once 'db_connect.php';

$identity = getCurrentUserId();

// Block strangers
if ($identity['type'] === 'stranger') {
    header("Location: ../pages/index.php?login_required=true");
    exit;
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity   = isset($_POST['quantity'])   ? intval($_POST['quantity'])   : 1;

if ($product_id <= 0) {
    header("Location: ../pages/shop.php");
    exit;
}

$id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
$id_value  = $identity['id'];
$bind_type = 's';

// Guests: resolve session string → real integer guest_id
if ($id_column === 'guest_id') {
    $g = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
    $g->bind_param("s", $id_value);
    $g->execute();
    $g_result = $g->get_result();
    $g->close();

    if ($g_result->num_rows === 0) {
        // Insert new guest row
        $ins = $conn->prepare("INSERT INTO guests (session_id) VALUES (?)");
        $ins->bind_param("s", $id_value);
        $ins->execute();
        $id_value = $conn->insert_id;
        $ins->close();
    } else {
        $id_value = $g_result->fetch_assoc()['guest_id'];
    }
    $bind_type = 'i';
}

// Check if product already in cart
$check = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE product_id = ? AND $id_column = ?");
$check->bind_param("i{$bind_type}", $product_id, $id_value);
$check->execute();
$existing = $check->get_result();
$check->close();

if ($existing->num_rows > 0) {
    // Already in cart — update quantity
    $row         = $existing->fetch_assoc();
    $cart_id     = $row['cart_id'];
    $new_qty     = $row['quantity'] + $quantity;
    $upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $upd->bind_param("ii", $new_qty, $cart_id);
    $upd->execute();
    $upd->close();
} else {
    // Insert new cart row
    $ins = $conn->prepare("INSERT INTO cart (product_id, quantity, $id_column) VALUES (?, ?, ?)");
    $ins->bind_param("ii{$bind_type}", $product_id, $quantity, $id_value);
    $ins->execute();
    $cart_id = $conn->insert_id;
    $ins->close();
}

// Set selected_items to ONLY this item so checkout shows just this product
$_SESSION['selected_items'] = [$cart_id];
unset($_SESSION['initialized_cart_selections']);

header("Location: ../pages/checkout.php");
exit;
?>