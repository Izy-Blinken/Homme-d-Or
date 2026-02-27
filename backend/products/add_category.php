<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $_SESSION['error'] = 'Category name cannot be empty.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    // Check kung meron na
    $check = mysqli_query($conn, "SELECT category_id FROM categories WHERE category_name = '$category_name'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = 'Category already exists.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Category $category_name added successfully.";
    } else {
        $_SESSION['error'] = 'Failed to add category.';
    }

    header('Location: ../../pages/Admin Pages/productManagement.php');
    exit;
}
?>