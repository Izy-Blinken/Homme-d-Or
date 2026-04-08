<?php
// add_to_cart.php
// Place this file in: Homme_dOr/backend/cart/add_to_cart.php

session_start();
include '../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity   = isset($_POST['quantity'])   ? (int)$_POST['quantity']   : 1;
$variant_id = isset($_POST['variant_id']) ? (int)$_POST['variant_id'] : null;

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

// --- Check product exists and is in stock ---
$prod = mysqli_query($conn, "SELECT product_id, product_status, stock_qty FROM products WHERE product_id = $product_id");
$product = mysqli_fetch_assoc($prod);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

if ($product['product_status'] === 'out-of-stock' || $product['stock_qty'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Product is out of stock.']);
    exit;
}

// --- Determine if logged in user or guest ---
$user_id  = isset($_SESSION['user_id'])  ? (int)$_SESSION['user_id']  : null;
$guest_id = isset($_SESSION['guest_id']) ? (int)$_SESSION['guest_id'] : null;

// If neither, treat as guest (you can change this to require login)
if (!$user_id && !$guest_id) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to your cart.']);
    exit;
}

$variant_sql  = $variant_id ? $variant_id : 'NULL';
$user_sql     = $user_id    ? $user_id    : 'NULL';
$guest_sql    = $guest_id   ? $guest_id   : 'NULL';

// --- Check if already in cart ---
$check_sql = "SELECT cart_id, quantity FROM cart WHERE product_id = $product_id";
$check_sql .= $user_id  ? " AND user_id = $user_id"   : " AND user_id IS NULL";
$check_sql .= $guest_id ? " AND guest_id = $guest_id"  : " AND guest_id IS NULL";
$check_sql .= $variant_id ? " AND variant_id = $variant_id" : " AND variant_id IS NULL";

$check = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check) > 0) {
    // Already in cart — update quantity
    $existing = mysqli_fetch_assoc($check);
    $new_qty  = $existing['quantity'] + $quantity;
    $cart_id  = $existing['cart_id'];

    mysqli_query($conn, "UPDATE cart SET quantity = $new_qty WHERE cart_id = $cart_id");
    echo json_encode(['success' => true, 'message' => 'Cart updated.']);
} else {
    // Insert new cart row
    $ins = "INSERT INTO cart (user_id, guest_id, product_id, variant_id, quantity)
            VALUES ($user_sql, $guest_sql, $product_id, $variant_sql, $quantity)";
    if (mysqli_query($conn, $ins)) {
        echo json_encode(['success' => true, 'message' => 'Added to cart.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not add to cart: ' . mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>