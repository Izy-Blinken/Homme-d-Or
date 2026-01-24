<?php
$title = $title ?? 'Trade-in-offer';
$subtitle = $subtitle ?? 'Super value deals';
$heading = $heading ?? 'On all products';
$description = $description ?? 'Save more with coupons & up to 70% off!';
$buttonText = $buttonText ?? 'Shop Now';
$buttonLink = $buttonLink ?? 'productdetails.php';
?>

<section id="hero">
    <h4><?php echo $title; ?></h4>
    <h2><?php echo $subtitle; ?></h2>
    <h1><?php echo $heading; ?></h1>
    <p><?php echo $description; ?></p>
    
    <a href="<?php echo $buttonLink; ?>">
    <button class="active"><?php echo $buttonText; ?></button>
    </a>
</section>