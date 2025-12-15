<?php

include("../db_connect.php");

//Example ng pag-add ng product (mano-mano muna to)
//This is not needed sa actual na logic. Dedelete din to later. It's just here for testing.
//ito lang rin yung addProduct sa functions.php

$name = "Break Pads";
$price = 350;
$description = "This is a sample product.";
$stock = 10;
$image = "sample.jpg";

$sql = "INSERT INTO products (name, price, description, stock, image) 
        VALUES ('$name', $price, '$description', $stock, '$image')";

if (mysqli_query($conn, $sql)) {
    echo "Product added successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

?>