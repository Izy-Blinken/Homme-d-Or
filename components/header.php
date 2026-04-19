<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
}
$currentLang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';

include_once '../backend/db_connect.php';

if (!empty($_SESSION['user_id'])) {
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT is_blocked FROM users WHERE user_id = '{$_SESSION['user_id']}'"));
    if ($check && $check['is_blocked']) {
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: ../pages/index.php');
        exit;
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<section id="header">
    <div class="logo-wrapper">
        <a href="index.php" class="logo-link">
            <img src="../assets/images/brand_images/prodLogo.png" class="logo" alt="Brand Logo">
        </a>

        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle navigation" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

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
                        
                        <div class="search-suggestions-dropdown" id="desktop-suggestions"></div>
                    </div>
                </form>
            </li>
        </ul>
    </div>

    <div class="nav-wrapper">
        <ul id="navbar-right">
            

            <li class="notif-dropdown" id="notif-item" style="position:relative;">
                <?php 
                    $userLoggedIn = !empty($_SESSION['user_id']) ? 'true' : 'false';
                    $headerIsGuest = (!empty($_SESSION['guest_id'])) ? 'true' : 'false';
                ?>
                <a href="#" id="notif-bell" data-loggedin="<?php echo $userLoggedIn; ?>" data-isguest="<?php echo $headerIsGuest; ?>" onclick="return false;">                    <i class="fa-solid fa-bell"></i>
                    <span class="notif-count" id="notif-count" style="display:none;">0</span>
                </a>
            
                <div class="notif-panel" id="notif-panel">
                    <div class="notif-panel-header">
                        <span>Notifications</span>
                        <button id="mark-all-read" style="background:none; border:none; color:white; font-size:0.8rem; cursor:pointer; font-weight:600;">Mark all read</button>
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
                            <?= htmlspecialchars($_SESSION['user_fname'] ?? $_SESSION['admin_fname'] ?? 'User') ?>
                        </div>
                        <p class="profile-subtext">Welcome!</p>
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

            if (!input || !dropdown) return;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 1) {
                    dropdown.style.display = 'none';
                    dropdown.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`../backend/search_suggestions.php?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            dropdown.innerHTML = '';

                            if (data.length === 0) {
                                dropdown.innerHTML = `
                                    <div style="padding: 12px 16px; color: #aaa; font-size: 0.85rem;">
                                        No results found.
                                    </div>`;
                                dropdown.style.display = 'flex';
                                return;
                            }

                            data.forEach(item => {
                                const imgSrc = item.image
                                    ? `../assets/images/products/${item.image}`
                                    : `../assets/images/brand_images/nocturne.png`;

                                const formattedPrice = new Intl.NumberFormat('en-PH', {
                                    style: 'currency',
                                    currency: 'PHP'
                                }).format(item.price);

                                const a = document.createElement('a');
                                a.href = `productDetails.php?id=${item.product_id}`;
                                a.className = 'search-suggestion-item';
                                a.innerHTML = `
                                    <img src="${imgSrc}" alt="${item.name}"
                                        onerror="this.src='../assets/images/brand_images/nocturne.png'">
                                    <div class="suggestion-info">
                                        <span class="suggestion-name">${item.name}</span>
                                        <span class="suggestion-price">${formattedPrice}</span>
                                    </div>
                                `;
                                dropdown.appendChild(a);
                            });

                            dropdown.style.display = 'flex';
                        })
                        .catch(() => {
                            dropdown.innerHTML = '';
                            dropdown.style.display = 'none';
                        });
                }, 300);
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Reopen on focus if there's already a query
            input.addEventListener('focus', function() {
                if (this.value.trim().length >= 1 && dropdown.innerHTML !== '') {
                    dropdown.style.display = 'flex';
                }
            });
        }

        setupLiveSearch('desktop-search', 'desktop-suggestions');
        setupLiveSearch('mobile-search', 'mobile-suggestions');
    });
    </script>
</section>