<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<section id="header">
    <div class="logo-wrapper">
        <a href="index.php" class="logo-link">
            <img src="../assets/images/brand_images/prodLogo.png" class="logo" alt="Brand Logo">
        </a>

        <!-- Hamburger: mobile only, sits on the LEFT replacing the logo -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle navigation" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Mobile dropdown — anchored LEFT under the hamburger -->
        <ul class="mobile-menu" id="mobileMenu">
            <li>
                <a class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
            </li>
            <li>
                <a class="<?php echo ($currentPage == 'shop.php') ? 'active' : ''; ?>" href="shop.php">Shop</a>
            </li>
            <li>
                <a class="<?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Blog</a>
            </li>
            <li>
                <a class="<?php echo ($currentPage == 'AboutUs.php') ? 'active' : ''; ?>" href="AboutUs.php">About Us</a>
            </li>
            <li>
                <a class="<?php echo ($currentPage == 'Profile.php') ? 'active' : ''; ?>" href="ContactUs.php">Profile</a>
            </li>
            <li>
                <a href="search.php"><i class="fa-solid fa-search"></i> Search</a>
            </li>
        </ul>

        <!-- Horizontal Slide Menu -->
        <ul class="logo-slide-menu" id="desktopMenu">
            <li>
                <a class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
            </li>

            <li>
                <a class="<?php echo ($currentPage == 'shop.php') ? 'active' : ''; ?>" href="shop.php">Shop</a>
            </li>
            
            <li>
                <a class="<?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Blog</a>
            </li>
            <li>
                <a class="<?php echo ($currentPage == 'AboutUs.php') ? 'active' : ''; ?>" href="AboutUs.php">About Us</a>
            </li>
            <li>
                <a class="<?php echo ($currentPage == 'Profile.php') ? 'active' : ''; ?>" href="ContactUs.php">Profile</a>
            </li>
            <li class="search-container">
                <a class="search-link" href="search.php"><i class="fa-solid fa-search"></i></a>
                <div class="search-input-wrapper">
                    <input type="text" placeholder="Search products..." id="navSearch">
                </div>
            </li>
        </ul>
    </div>

    <div class="nav-wrapper">
        <ul id="navbar-right">
            <li><a class="<?php echo ($currentPage == 'wishlist.php') ? 'active' : ''; ?>" href="viewWishlist.php"><i class="fa-solid fa-star"></i></a></li>
            <li><a class="<?php echo ($currentPage == 'cart.php') ? 'active' : ''; ?>" href="cart.php"><i class="fa-solid fa-shopping-cart"></i></a></li>
            
            <li class="dropdown profile-dropdown">
                <a href="#">
                    <i class="fa-solid fa-user"></i>
                </a>
                <div class="dropdown-menu profile-menu">
                    <div class="profile-header">
                        Join Exclusive Deals
                    </div>
                    <p class="profile-subtext">Log in or create an account to discover our loyalty program and our membership privileges</p>
                   <!-- <a href="login.php" class="profile-login-btn">Log In</a>
                    <a href="signup.php" class="profile-register-btn">Create an Account</a>-->
                    <a href="#" onclick="openLoginModal()" class="profile-login-btn">Login</a>
                    <a href="#" onclick="openSignupModal()" class="profile-register-btn">Create an Account</a>
                </div>
            </li>
              
        </ul>
    </div>
    
    <script src="../assets/js/MobileMenu.js"></script>
</section>

