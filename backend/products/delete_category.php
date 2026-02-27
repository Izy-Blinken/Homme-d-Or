<?php
session_start();
include '../db_connect.php';

if (isset($_GET['id'])) {

    $category_id = $_GET['id'];

    // Safety check: wag mag delete if products yung category
    $check = mysqli_query($conn, "SELECT product_id FROM products WHERE category_id = '$category_id'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = 'Cannot delete — this category still has products assigned to it.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    $sql = "DELETE FROM categories WHERE category_id = '$category_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = 'Category deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete category.';
    }

    header('Location: ../../pages/Admin Pages/productManagement.php');
    exit;
}
?>