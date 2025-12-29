<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<section id="checkoutHeader">
    <a href="index.php">
        <img src="../assets/images/brand_images/prodLogo.png" class="COlogo" alt="Brand Logo">
    </a>

    <div class="COnav-wrapper">

        <ul id="COnavbar">
            <li><a href="index.php"><i class="fa-solid fa-chevron-left"></i></a></li>
        </ul>

        <ul id="COnavbar-right">
        
           <li><a class="<?php echo ($currentPage == 'wishlist.php') ? 'active' : ''; ?>"href="wishlist.php"><i class="fa-solid fa-star"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'cart.php') ? 'active' : ''; ?>" href="cart.php"><i class="fa-solid fa-shopping-cart"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?>" href="profile.php"><i class="fa-solid fa-user"></i></a></li>
        </ul>

    </div>



    
</section>