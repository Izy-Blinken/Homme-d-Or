<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_name    = trim($_POST['product_name']);
    $category_id     = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $price           = $_POST['price'];
    $discounted_price = !empty($_POST['discounted_price']) ? $_POST['discounted_price'] : null;
    $stock_qty       = $_POST['stock_qty'];
    $sku             = trim($_POST['sku']);
    $product_desc    = trim($_POST['product_desc']);
    
    if ($stock_qty == 0) {
        $product_status = 'out-of-stock';
    } elseif ($stock_qty <= 10) {
        $product_status = 'low-stock';
    } else {
        $product_status = 'in-stock';
    }

    // Handle image upload
    $image_url = null;
    if (!empty($_FILES['product_image']['name'])) {
        $upload_dir = '../../assets/images/products/';
        $filename   = time() . '_' . basename($_FILES['product_image']['name']);
        $target     = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
            $image_url = $filename;
        }
    }

    // Insert product
    $sql = "INSERT INTO products 
            (category_id, product_name, product_desc, price, discounted_price, sku, stock_qty, product_status) 
            VALUES ('$category_id', '$product_name', '$product_desc', '$price', '$discounted_price', '$sku', '$stock_qty', '$product_status')";

    if (mysqli_query($conn, $sql)) {
        $product_id = mysqli_insert_id($conn);

        // Insert image if uploaded
        if ($image_url) {
            mysqli_query($conn, "INSERT INTO product_images (product_id, image_url, is_primary) 
                                 VALUES ('$product_id', '$image_url', 1)");
        }

        header('Location: ../../pages/Admin Pages/productManagement.php?success=Product added successfully.');
        exit;
    } else {
        header('Location: ../../pages/Admin Pages/productManagement.php?error=Failed to add product.');
        exit;
    }
}
?>