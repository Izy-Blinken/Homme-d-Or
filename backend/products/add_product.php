<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_name     = trim($_POST['product_name']);
    $category_id      = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $price            = $_POST['price'];
    $discounted_price = !empty($_POST['discounted_price']) ? $_POST['discounted_price'] : null;
    $stock_qty        = $_POST['stock_qty'];
    $sku              = trim($_POST['sku']);
    $product_desc     = trim($_POST['product_desc']);

    if ($price < 0 || $stock_qty < 0 || ($discounted_price !== null && $discounted_price < 0)) {
        $_SESSION['error'] = 'Price and stock cannot be negative.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    if ($stock_qty == 0) {
        $product_status = 'out-of-stock';
    } elseif ($stock_qty >= 1 && $stock_qty <= 10) {
        $product_status = 'low-stock';
    } else {
        $product_status = 'in-stock';
    }

    // Check SKU
    $check = mysqli_query($conn, "SELECT product_id FROM products WHERE sku = '$sku'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = 'SKU already exists.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    $cat_val  = $category_id      ? "'$category_id'"      : "NULL";
    $disc_val = $discounted_price  ? "'$discounted_price'" : "NULL";

    $sql = "INSERT INTO products 
            (category_id, product_name, product_desc, price, discounted_price, sku, stock_qty, product_status) 
            VALUES ($cat_val, '$product_name', '$product_desc', '$price', $disc_val, '$sku', '$stock_qty', '$product_status')";

    if (mysqli_query($conn, $sql)) {
        $product_id = mysqli_insert_id($conn);

        // Handle multiple image uploads (max 5)
        if (!empty($_FILES['product_images']['name'][0])) {
            $upload_dir    = '../../assets/images/products/';
            $primary_index = isset($_POST['primary_image_index']) ? (int)$_POST['primary_image_index'] : 0;
            $file_count    = min(count($_FILES['product_images']['name']), 5);

            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['product_images']['error'][$i] !== UPLOAD_ERR_OK) continue;

                $filename = time() . '_' . $i . '_' . basename($_FILES['product_images']['name'][$i]);
                $target   = $upload_dir . $filename;

                if (move_uploaded_file($_FILES['product_images']['tmp_name'][$i], $target)) {
                    $is_primary = ($i === $primary_index) ? 1 : 0;
                    mysqli_query($conn, "INSERT INTO product_images (product_id, image_url, is_primary) 
                        VALUES ('$product_id', '$filename', '$is_primary')");
                }
            }

            // Ensure at least one primary exists
            $has_primary = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT COUNT(*) AS cnt FROM product_images WHERE product_id = '$product_id' AND is_primary = 1"
            ));
            if ($has_primary['cnt'] == 0) {
                $first = mysqli_fetch_assoc(mysqli_query($conn,
                    "SELECT image_id FROM product_images WHERE product_id = '$product_id' LIMIT 1"
                ));
                if ($first) {
                    mysqli_query($conn, "UPDATE product_images SET is_primary = 1 WHERE image_id = '{$first['image_id']}'");
                }
            }
        }

        // Insert variants
        if (!empty($_POST['variant_size'])) {
            $sizes  = $_POST['variant_size'];
            $prices = $_POST['variant_price'];
            $stocks = $_POST['variant_stock'];
            $skus   = $_POST['variant_sku'];

            foreach ($sizes as $i => $size_label) {
                $size_label = trim($size_label);
                $var_price  = trim($prices[$i]);
                $var_stock  = trim($stocks[$i]);
                $var_sku    = trim($skus[$i]);

                if (empty($size_label) || empty($var_price) || empty($var_sku)) continue;

                $vcheck = mysqli_query($conn, "SELECT variant_id FROM product_variants WHERE sku = '$var_sku'");
                if (mysqli_num_rows($vcheck) > 0) continue;

                mysqli_query($conn, "INSERT INTO product_variants (product_id, size_label, price, stock_qty, sku)
                    VALUES ('$product_id', '$size_label', '$var_price', '$var_stock', '$var_sku')");
            }
        }

        $_SESSION['success'] = 'Product added successfully.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;

    } else {
        $_SESSION['error'] = 'Failed to add product.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }
}
?>