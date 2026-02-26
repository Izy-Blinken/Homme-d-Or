<?php
session_start();
include '../db_connect.php';

$product_id = $_GET['id'] ?? null;

if ($product_id) {
    mysqli_query($conn, "DELETE FROM products WHERE product_id = '$product_id'");
    header('Location: ../../pages/Admin Pages/productManagement.php?success=Product deleted successfully.');
} else {
    header('Location: ../../pages/Admin Pages/productManagement.php?error=Product not found.');
}
exit;
?>