<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Put this near your session_start() later!
if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
}
$currentLang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';

include '../backend/db_connect.php';
 
if (empty($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    $token = mysqli_real_escape_string($conn, $_COOKIE['remember_token']);
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE remember_token = '$token' AND is_blocked = 0"));
    
    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_fname'] = $user['fname'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_username'] = $user['username'];
    }
}

if (!empty($_SESSION['user_id'])) {

    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT is_blocked FROM users WHERE user_id = '{$_SESSION['user_id']}'"));

    if ($check && $check['is_blocked']) {
        
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: ../pages/index.php');
        exit;
    }
}

?>

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
                <form action="search.php" method="GET" style="display: flex; align-items: center; padding: 10px 20px;">
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <input type="text" name="q" placeholder="Search..." required style="background: transparent; border: none; border-bottom: 1px solid #ccc; color: whitesmoke; margin-left: 10px; outline: none; width: 100%; font-size: 15px;">
                </form>
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
                <form action="search.php" method="GET" class="search-form" style="display: flex; align-items: center; margin: 0;">
                    <button type="submit" class="search-link" style="background: none; border: none; cursor: pointer; padding: 0; outline: none;">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <div class="search-input-wrapper">
                        <input type="text" name="q" class="search-input" placeholder="Search for fragrances..." required>
                    </div>
                </form>
            </li>
        </ul>
    </div>

    <div class="nav-wrapper">
        <ul id="navbar-right">

            <li class="header-lang-toggle">
                <a href="?lang=en" class="lang-btn active">EN</a>
                <span class="lang-divider">/</span>
                <a href="?lang=fil" class="lang-btn">FIL</a>
            </li>

            <li class="notif-dropdown" id="notif-item" style="position:relative;">
                <?php $userLoggedIn = !empty($_SESSION['user_id']) ? 'true' : 'false'; ?>
                <a href="#" id="notif-bell" data-loggedin="<?php echo $userLoggedIn; ?>" onclick="return false;">
                    <i class="fa-solid fa-bell"></i>
                    <span class="notif-count" id="notif-count" style="display:none;">0</span>
                </a>
            
                <div class="notif-panel" id="notif-panel">
                    <div class="notif-panel-header">
                        <span>Notifications</span>
                        <button id="mark-all-read" style="background:none; border:none; color:#c9a961; font-size:0.8rem; cursor:pointer; font-weight:600;">Mark all read</button>
                    </div>
            
                    <div class="notif-list" id="notif-list">
                        <div class="notif-empty">No notifications yet.</div>
                    </div>
                </div>
            </li>            
            
            <li><a class="<?php echo ($currentPage == 'cart.php') ? 'active' : ''; ?>" href="cart.php"><i class="fa-solid fa-shopping-cart"></i></a></li>
            
            <li class="dropdown profile-dropdown">
                <a href="#">
                    <i class="fa-solid fa-user"></i>
                </a>
                <div class="dropdown-menu profile-menu">

                    <?php if (!empty($_SESSION['user_id'])): ?>

                        <div class="profile-header">
                            <?= htmlspecialchars($_SESSION['user_fname']) ?>
                        </div>
                        <p class="profile-subtext">Welcome back!</p>
                        <a href="../backend/loginSignUp/logout.php" class="profile-login-btn">Logout</a>

                    <?php else: ?>

                        <div class="profile-header">
                            Join Exclusive Deals
                        </div>

                        <p class="profile-subtext">Log in or create an account to discover our loyalty program and our membership privileges</p>
                        <a href="#" onclick="openLoginModal()" class="profile-login-btn">Login</a>
                        <a href="#" onclick="openSignupModal()" class="profile-register-btn">Create an Account</a>
                    
                    <?php endif; ?>
                    
                </div>
            </li>
              
        </ul>
    </div>
    
    <script src="../assets/js/notif.js"></script>
</section>

