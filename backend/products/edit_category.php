<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_id   = $_POST['category_id'];
    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $_SESSION['error'] = 'Category name cannot be empty.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    // Check if name already used by another category
    $check = mysqli_query($conn, "SELECT category_id FROM categories 
            WHERE category_name = '$category_name' 
            AND category_id != '$category_id'");
            
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = 'Another category with that name already exists.';
        header('Location: ../../pages/Admin Pages/productManagement.php');
        exit;
    }

    $sql = "UPDATE categories SET category_name = '$category_name' WHERE category_id = '$category_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Category updated to $category_name successfully.";
    } else {
        $_SESSION['error'] = 'Failed to update category.';
    }

    header('Location: ../../pages/Admin Pages/productManagement.php');
    exit;
}
?>