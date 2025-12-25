<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<section id="header">
    <a href="index.php">
        <img src="../assets/images/brand_images/prodLogo.png" class="logo" alt="Brand Logo">
    </a>

    <div class="nav-wrapper">
        <ul id="navbar">
            <li><a class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a></li>
            <li><a class="<?php echo ($currentPage == 'shop.php') ? 'active' : ''; ?>" href="shop.php">Shop</a></li>
            <li><a class="<?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Blog</a></li>
            <li><a class="<?php echo ($currentPage == 'AboutUs.php') ? 'active' : ''; ?>" href="AboutUs.php">About Us</a></li>
            <li><a class="<?php echo ($currentPage == 'ContactUs.php') ? 'active' : ''; ?>" href="ContactUs.php">Contact Us</a></li>
            <li><a href="search.php"><i class="fa-solid fa-search"></i></a></li>
        </ul>

        <ul id="navbar-right">
            <li><a href="countrycurrency.php"><i class="fa-solid fa-globe"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'wishlist.php') ? 'active' : ''; ?>"href="wishlist.php"><i class="fa-solid fa-star"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'cart.php') ? 'active' : ''; ?>" href="cart.php"><i class="fa-solid fa-shopping-cart"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?>" href="profile.php"><i class="fa-solid fa-user"></i></a></li>
        </ul>
    </div>
</section>