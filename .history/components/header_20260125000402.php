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
            <li class="dropdown">
                <a >
                    Shop <i class="fa-solid fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="shop.php?category=sale">Sale</a></li>
                    <li><a href="shop.php?category=new-arrival">New Arrival</a></li>
                    <li><a href="shop.php?category=top-picks">Top Picks</a></li>
                    <li><a href="shop.php?category=daily-wear">Daily Wear</a></li>
                    <li><a href="shop.php?category=premium">Premium</a></li>
                </ul>
            </li>
            <li><a class="<?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Blog</a></li>
            <li><a class="<?php echo ($currentPage == 'AboutUs.php') ? 'active' : ''; ?>" href="AboutUs.php">About Us</a></li>
            <li><a class="<?php echo ($currentPage == 'ContactUs.php') ? 'active' : ''; ?>" href="ContactUs.php">Contact Us</a></li>
            <li><a href="search.php"><i class="fa-solid fa-search"></i></a></li>
        </ul>

        <ul id="navbar-right">
        <li class="dropdown country-dropdown">
            <a href="#">
                <i class="fa-solid fa-globe"></i>
            </a>
            <ul class="dropdown-menu country-menu">
                <li><a href="?country=ph"><span class="flag">🇵🇭</span> Philippines</a></li>
                <li><a href="?country=jp"><span class="flag">🇯🇵</span> Japan</a></li>
                <li><a href="?country=cn"><span class="flag">🇨🇳</span> China</a></li>
                <li><a href="?country=fr"><span class="flag">🇫🇷</span> France</a></li>
            </ul>
        </li>
        <li><a class="<?php echo ($currentPage == 'wishlist.php') ? 'active' : ''; ?>"href="wishlist.php"><i class="fa-solid fa-star"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'cart.php') ? 'active' : ''; ?>" href="cart.php"><i class="fa-solid fa-shopping-cart"></i></a></li>

            <li class="dropdown profile-dropdown">
                <a href="#">
                    <i class="fa-solid fa-user"></i>
                </a>
                <div class="dropdown-menu profile-menu">
                    <div class="profile-header">
                        Join
                    </div>
                    <p class="profile-subtext">Log in to discover our loyalty program and our membership privileges</p>
                    <a href="login.php" class="profile-login-btn">Log In</a>
                    <a href="register.php" class="profile-register-btn">Create an Account</a>
                </div>
            </li>
        </ul>
    </div>
</section>