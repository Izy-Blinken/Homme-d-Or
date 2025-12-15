<?php
include '../db_connect.php';

//This is not needed sa actual na logic. Dedelete din to later. It's just here for testing.
//ito lang rin yung getAllProducts sa functions.php

// Query products table
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

// Check if products exist
if (mysqli_num_rows($result) > 0) {

    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $products[] = $row;

    }

    // Output as JSON
    echo json_encode($products);

} else {

    echo json_encode([]);

}
?>
