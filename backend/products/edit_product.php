<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id       = $_POST['product_id'];
    $product_name     = trim($_POST['product_name']);
    $category_id      = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $price            = $_POST['price'];
    $discounted_price = !empty($_POST['discounted_price']) ? $_POST['discounted_price'] : null;
    $stock_qty        = $_POST['stock_qty'];
    $sku              = trim($_POST['sku']);
    $product_desc     = trim($_POST['product_desc']);

    if ($price < 0 || $stock_qty < 0 || ($discounted_price !== null && $discounted_price < 0)) {
        $_SESSION['error'] = 'Numeric input cannot be negative.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    if ($stock_qty == 0) {
        $product_status = 'out-of-stock';
    } elseif ($stock_qty <= 10) {
        $product_status = 'low-stock';
    } else {
        $product_status = 'in-stock';
    }

    // Handle new image uploads (appends to existing, max 5 total)
    if (!empty($_FILES['product_images']['name'][0])) {
        $upload_dir = '../../assets/images/products/';

        $existing_count = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS cnt FROM product_images WHERE product_id = '$product_id'"
        ))['cnt'];

        $slots_left = max(0, 5 - $existing_count);
        $file_count = min(count($_FILES['product_images']['name']), $slots_left);

        $has_primary = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS cnt FROM product_images WHERE product_id = '$product_id' AND is_primary = 1"
        ))['cnt'];

        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['product_images']['error'][$i] !== UPLOAD_ERR_OK) continue;

            $filename = time() . '_' . $i . '_' . basename($_FILES['product_images']['name'][$i]);
            $target   = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['product_images']['tmp_name'][$i], $target)) {
                // Only set as primary if no primary exists yet
                $is_primary = (!$has_primary && $i === 0) ? 1 : 0;
                mysqli_query($conn, "INSERT INTO product_images (product_id, image_url, is_primary) 
                    VALUES ('$product_id', '$filename', '$is_primary')");
                if ($is_primary) $has_primary = 1;
            }
        }
    }

    // Check SKU duplicate
    $check = mysqli_query($conn, "SELECT product_id FROM products WHERE sku = '$sku' AND product_id != '$product_id'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = 'SKU already exists.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    $cat_val  = $category_id      ? "'$category_id'"      : "NULL";
    $disc_val = $discounted_price  ? "'$discounted_price'" : "NULL";

    $sql = "UPDATE products SET
                product_name     = '$product_name',
                category_id      = $cat_val,
                price            = '$price',
                discounted_price = $disc_val,
                stock_qty        = '$stock_qty',
                sku              = '$sku',
                product_desc     = '$product_desc',
                product_status   = '$product_status'
            WHERE product_id = '$product_id'";

    if (!mysqli_query($conn, $sql)) {
        $_SESSION['error'] = 'Failed to update product.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    // Update existing variants
    if (!empty($_POST['existing_variant_id'])) {
        $ex_ids    = $_POST['existing_variant_id'];
        $ex_sizes  = $_POST['existing_variant_size'];
        $ex_prices = $_POST['existing_variant_price'];
        $ex_stocks = $_POST['existing_variant_stock'];
        $ex_skus   = $_POST['existing_variant_sku'];

        foreach ($ex_ids as $i => $variant_id) {
            $size_label = trim($ex_sizes[$i]);
            $var_price  = trim($ex_prices[$i]);
            $var_stock  = trim($ex_stocks[$i]);
            $var_sku    = trim($ex_skus[$i]);

            if (empty($size_label) || empty($var_price) || empty($var_sku)) continue;

            $vcheck = mysqli_query($conn, "SELECT variant_id FROM product_variants 
                WHERE sku = '$var_sku' AND variant_id != '$variant_id'");
            if (mysqli_num_rows($vcheck) > 0) continue;

            mysqli_query($conn, "UPDATE product_variants SET
                size_label = '$size_label',
                price      = '$var_price',
                stock_qty  = '$var_stock',
                sku        = '$var_sku'
                WHERE variant_id = '$variant_id'");
        }
    }

    // Add new variants
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

    $_SESSION['success'] = 'Product updated successfully.';
    header('Location: ../../pages/Admin Pages/productManagement.php');
    exit;
}
?>