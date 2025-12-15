<?php
include 'db_connect.php';

// Example: get all products
function getAllProducts()
{
    global $conn;
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    $products = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    return $products;
}

// Example: add new product
function addProduct($name, $price, $description, $stock, $image)
{
    global $conn;
    $sql = "INSERT INTO products (name, price, description, stock, image) 
            VALUES ('$name', $price, '$description', $stock, '$image')";
    return mysqli_query($conn, $sql);
}
?>
