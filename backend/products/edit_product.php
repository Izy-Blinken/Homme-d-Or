<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id      = $_POST['product_id'];
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
    if (!empty($_FILES['product_image']['name'])) {
        $upload_dir = '../../assets/images/products/';
        $filename   = time() . '_' . basename($_FILES['product_image']['name']);
        $target     = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
            $existing = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT image_id FROM product_images WHERE product_id = '$product_id' AND is_primary = 1"));

            if ($existing) {
                mysqli_query($conn, "UPDATE product_images SET image_url = '$filename' 
                                     WHERE product_id = '$product_id' AND is_primary = 1");
            } else {
                mysqli_query($conn, "INSERT INTO product_images (product_id, image_url, is_primary) 
                                     VALUES ('$product_id', '$filename', 1)");
            }
        }
    }

    // Update product
    $sql = "UPDATE products SET
                product_name     = '$product_name',
                category_id      = '$category_id',
                price            = '$price',
                discounted_price = '$discounted_price',
                stock_qty        = '$stock_qty',
                sku              = '$sku',
                product_desc     = '$product_desc',
                product_status   = '$product_status'
            WHERE product_id = '$product_id'";

    if (mysqli_query($conn, $sql)) {
        header('Location: ../../pages/Admin Pages/productManagement.php?success=Product updated successfully.');
        exit;
    } else {
        header('Location: ../../pages/Admin Pages/productManagement.php?error=Failed to update product.');
        exit;
    }
}
?>