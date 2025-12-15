<?php
include '../backend/functions.php';
$products = getAllProducts();
?>

//Sample layout. Dito mag-eedit ang mga frontends

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homme d'Or - Products</title>
    <style>
        body {
            background-color:gainsboro;
        }
        h1{
            color:goldenrod;
        }
        
    </style>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<h1> All Products</h1>

<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width = "100px" height = "100px">
            <h3><?php echo $product['name']; ?></h3>
            <p>₱<?php echo $product['price']; ?></p>
            <p><?php echo $product['description']; ?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
