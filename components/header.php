<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

session_write_close();

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
                <form action="search.php" method="GET" style="display: flex; align-items: center; padding: 10px 20px; position: relative;">
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <input type="text" name="q" id="mobile-search" placeholder="Search..." required autocomplete="off" style="background: transparent; border: none; border-bottom: 1px solid #ccc; color: whitesmoke; margin-left: 10px; outline: none; width: 100%; font-size: 15px;">
                    
                    <div class="search-suggestions-dropdown mobile-suggestions-box" id="mobile-suggestions"></div>
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
                        <input type="text" name="q" id="desktop-search" class="search-input" placeholder="Search for fragrances..." required autocomplete="off">
                        
                        <div class="search-suggestions-dropdown" id="desktop-suggestions" style="display: flex;">
                            <a href="#" class="search-suggestion-item">
                                <img src="../assets/images/brand_images/placeholder.jpg" alt="Perfume 1">
                                <div class="suggestion-info">
                                    <span class="suggestion-name">Golden Night Special Edition</span>
                                    <span class="suggestion-price">₱1,800.00</span>
                                </div>
                            </a>
                            <a href="#" class="search-suggestion-item">
                                <img src="../assets/images/brand_images/placeholder.jpg" alt="Perfume 2">
                                <div class="suggestion-info">
                                    <span class="suggestion-name">Midnight Oud Extrait</span>
                                    <span class="suggestion-price">₱2,450.00</span>
                                </div>
                            </a>
                            <a href="#" class="search-suggestion-item">
                                <img src="../assets/images/brand_images/placeholder.jpg" alt="Perfume 3" >
                                <div class="suggestion-info">
                                    <span class="suggestion-name">Velvet Rose & Vanilla</span>
                                    <span class="suggestion-price">₱1,200.00</span>
                                </div>
                            </a>
                        </div>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupLiveSearch(inputId, dropdownId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            let debounceTimer;

            if(!input || !dropdown) return;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }

                const fakeData = [
                    { product_id: 1, name: "Golden Night Special Edition", price: 1800, image: "placeholder.jpg" },
                    { product_id: 2, name: "Midnight Oud Extrait", price: 2450, image: "placeholder.jpg" }
                ];

                debounceTimer = setTimeout(() => {
                    dropdown.innerHTML = ''; 
                    
                    fakeData.forEach(item => {
                        const a = document.createElement('a');
                        a.href = `#`; 
                        a.className = 'search-suggestion-item';
                        
                        const formattedPrice = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(item.price);
                        
                        a.innerHTML = `
                            <img src="../assets/images/brand_images/placeholder.jpg" alt="${item.name}" onerror="this.src='https://via.placeholder.com/45'">
                            <div class="suggestion-info">
                                <span class="suggestion-name">${item.name}</span>
                                <span class="suggestion-price">${formattedPrice}</span>
                            </div>
                        `;
                        dropdown.appendChild(a);
                    });
                    
                    dropdown.style.display = 'flex'; 
                    
                }, 300); 
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
            
            input.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && dropdown.innerHTML !== '') {
                    dropdown.style.display = 'flex';
                }
            });
        }

        setupLiveSearch('desktop-search', 'desktop-suggestions');
        setupLiveSearch('mobile-search', 'mobile-suggestions');
    });
    </script>
</section>


<!-- TERMS AND CONDITIONS MODAL -->
<div id="termsModal" class="modal-overlay" style="display:none;">

    <head>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    
    <div class="terms-modal-box">

        <button class="terms-close-btn" onclick="closeTermsModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2>Terms and Conditions</h2>
        <div class="terms-content">

            <h3>1. Acceptance of Terms</h3>
            <p>By creating an account and using Homme d'Or, you agree to be bound by these Terms and Conditions. If you do not agree, please do not use our services.</p>

            <h3>2. Account Responsibility</h3>
            <p>You are responsible for maintaining the confidentiality of your account credentials. Any activity that occurs under your account is your responsibility. Notify us immediately of any unauthorized use.</p>

            <h3>3. Orders and Payments</h3>
            <p>All orders are subject to product availability. We reserve the right to cancel or refuse any order at our discretion. Prices are listed in Philippine Peso (₱) and are subject to change without notice.</p>

            <h3>4. Shipping and Delivery</h3>
            <p>Delivery times are estimates only and are not guaranteed. Homme d'Or is not liable for delays caused by courier services, weather, or other circumstances beyond our control.</p>

            <h3>5. Returns and Refunds</h3>
            <p>We accept returns within 7 days of delivery for damaged or defective items only. Items must be unused and in their original packaging. Refunds will be processed within 5–10 business days upon approval.</p>

            <h3>6. Privacy</h3>
            <p>Your personal information is collected solely for order processing and communication purposes. We do not sell or share your data with third parties without your consent.</p>

            <h3>7. Intellectual Property</h3>
            <p>All content on this website including images, logos, and text are the property of Homme d'Or. Unauthorized use or reproduction is strictly prohibited.</p>

            <h3>8. Limitation of Liability</h3>
            <p>Homme d'Or shall not be held liable for any indirect, incidental, or consequential damages arising from the use of our products or services.</p>

            <h3>9. Changes to Terms</h3>
            <p>We reserve the right to update these Terms and Conditions at any time. Continued use of our platform after changes have been made constitutes your acceptance of the new terms.</p>

            <h3>10. Contact Us</h3>
            <p>If you have any questions about these Terms and Conditions, please contact us through our website or reach out to our customer support team.</p>

        </div>
    </div>

</div>