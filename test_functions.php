<?php
include 'backend/functions.php';

//Mano-manong functions pa lang 'to
//for testing lang if working ang mga logic na gagawin

// fetching products
$products = getAllProducts();
echo "<pre>";
print_r($products);
echo "</pre>";

// adding a product

if(addProduct("Nocturne", 15000, "lorem ipsum", 10, "nocturne.png")){
    echo "Product added successfully!";
} else {
    echo "Failed to add new product.";
}

?>
