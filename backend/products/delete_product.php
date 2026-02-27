<?php
session_start();
include '../db_connect.php';

$product_id = $_GET['id'] ?? null;

if ($product_id) {
    mysqli_query($conn, "DELETE FROM products WHERE product_id = '$product_id'");
    $_SESSION['success'] = 'Product deleted successfully.';
    header('Location: ../../pages/Admin Pages/productManagement.php');
} else {
    $_SESSION['error'] = 'Failed to delete product.';
    header('Location: ../../pages/Admin Pages/productManagement.php');
}
exit;
?>