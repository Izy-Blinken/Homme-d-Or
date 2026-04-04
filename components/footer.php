    <div id="signupModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="signupTitle">
                <h2><b>SIGN UP</b></h2>
            </div>

            <form id="registerForm" action="../backend/loginSignUp/signup.php" method="POST">
                
                <div class="inputGroup">
                    <input type="text" name="firstname" id="signupFirstname" required>
                    <label>FIRST NAME*</label>
                </div>

                <div class="inputGroup">
                    <input type="text" name="lastname" id="signupLastname" required>
                    <label>LAST NAME*</label>
                </div>

                <div class="inputGroup">
                    <input type="text" name="username" id="signupUsername" required>
                    <label>USERNAME*</label>
                </div>

                <div class="inputGroupBD">
                    <input type="date" name="birthday" id="signupBirthday" required>
                    <label>BIRTHDAY*</label>
                </div>

                <div class="inputGroup">
                    <input type="text" name="phone" id="signupPhone" maxlength="11" required>
                    <label>PHONE*</label>
                </div>

                <div class="inputGroup">
                    <input type="email" id="signupEmail" name="email" required>
                    <label for="signupEmail">EMAIL*</label>
                </div>

                <div class="passwordRequirementsReg">
                    <p class="requirementsTitleReg">PASSWORD REQUIREMENTS:</p>
                    <ul>
                        <li id="reg-req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                        <li id="reg-req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                        <li id="reg-req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                        <li id="reg-req-number"><i class="fas fa-circle"></i> One number</li>
                    </ul>
                </div>

                <div class="inputGroupCP passwordInputGroup">
                    <input type="password" id="regPassword" name="password" required>
                    <label>PASSWORD*</label>
                    <button type="button" class="togglePasswordReg" id="toggleRegPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="passwordStrengthReg">
                    <div class="strengthBarReg">
                        <div class="strengthBarFillReg" id="regStrengthBarFill"></div>
                    </div>
                    <span class="strengthTextReg" id="regStrengthText"></span>
                </div>

                <div class="inputGroupCP passwordInputGroup">
                    <input type="password" id="regConfirmPassword" name="confirm_password" required>
                    <label>CONFIRM PASSWORD*</label>
                    <button type="button" class="togglePasswordReg" id="toggleRegConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="passwordMatchReg" id="regPasswordMatch"></span>

                <div class="inputGroupCheckbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I have read and agreed on <a href="terms.php" target="_blank">Terms and Conditions</a></label>
                </div>

                <div class="captcha-placeholder" style="border:1px dashed #ccc; padding:1rem; text-align:center; color:#aaa; font-size:0.85rem; margin-bottom:1rem;">
                    [ Google reCAPTCHA — to be implemented ]
                </div>

                <div class="regBtn">
                    <button type="submit">CREATE ACCOUNT</button>
                </div>

                <div class="regLog">
                    <label>
                        <a href="#" onclick="event.preventDefault(); openLoginModal();">LOGIN</a> or
                        <a href="#" onclick="event.preventDefault(); closeSignupModal();">CONTINUE AS GUEST</a>
                    </label>
                </div>
            </form>
        </div>
    </div>

    <div id="signupVerifyModal" class="signupVerificationModal" style="display: none;">
        <div class="signupVerificationContent">
            <button class="signupCloseBtn" id="closeSignupVerify"><i class="fas fa-times"></i></button>
            <div class="signupModalHeader">
                <h2>Enter Verification Code</h2>
                <p class="signupModalSubtitle">We've sent a 6-digit code to <span id="signupUserEmail"></span></p>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal">

        <div class="modal-content modal-content-login">

            <span class="close">&times;</span>

            <div class="signupTitle">
                <h2><b>SIGN IN</b></h2>
            </div>

            <form action="../backend/loginSignUp/login.php" method="POST">

                <div class="inputGroupLog">
                    <input type="text" name="username" required>
                    <label>USERNAME*</label>
                </div>

                <div class="inputGroupLog">
                    <input type="password" name="password" required>
                    <label>PASSWORD*</label>
                </div>

                <div class="inputGroupCheckbox checkbox-remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <div class="forgotP">
                    <a href="forgotPassword.php">Forgot Password?</a>
                </div>

                <div class="regBtn">
                    <button type="submit">LOGIN</button>
                </div>

                <div class="orContainer">
                    <div class="or-divider"><span>or</span></div>
                    <div class="member-exclusive">
                        <h3><i><b>Member Exclusive</b></i></h3>
                        <p class="member-description">Unlock a world of scent. Gain early access to limited-edition releases.</p>
                        <button type="button" class="create-account-btn" onclick="closeLoginModal(); openSignupModal();">CREATE ACCOUNT</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div id="orderSuccessModal" class="orderSuccessModal" style="display: none;">

        <div class="orderSuccessModalContent">

            <button class="orderSuccessCloseBtn" id="closeOrderSuccess"><i class="fas fa-times"></i></button>
            <h1>Order Placed Successfully!</h1>
            <p class="orderNumber">Order #<span id="orderNumberDisplay">1</span></p>
            
            <div class="statusMessage">
                <i class="fa-solid fa-clock"></i>
                <p>Waiting to ship your order</p>
            </div>

            <div class="actionButtons">
                <a href="viewAllTabs.php" class="btn btnPrimary">View My Order</a>
                <a href="index.php" class="btn btnSecondary">Order Again</a>
            </div>
        </div>
    </div>

    <!-- =============================================
        TRANSACTION MODAL
        Placed here (outside <main>) so it is never
        trapped inside a CSS stacking context.
        Uses unique class names (trans-*) to avoid
        any conflict with the login/signup modals above.
    ============================================== -->
    <div id="transactionModal" style="
        display: none;
        position: fixed;
        z-index: 99999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.65);
    ">
        <div class="trans-modal-content">
        <span class="trans-close-btn">&times;</span>
        <h2>TRANSACTION DETAILS</h2>
        <div class="trans-modal-body">
            <img src="../assets/images/products_images/nocturne.png" width="200" height="200">
            <div class="trans-modal-text">
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
                <p><strong>Product:</strong> <span id="modalProduct"></span></p>
                <p><strong>Price:</strong> <span id="modalPrice"></span></p>
                <p><strong>Quantity:</strong> <span id="modalQty"></span></p>
                <p><strong>Subtotal:</strong> <span id="modalSubtotal"></span></p>
                <p><strong class="status">Status:</strong> <span id="modalStatus"></span></p>
            </div>
        </div>
    </div>
</div>

    <style>
        /* Transaction modal — scoped with trans-* prefix to avoid conflicts */
.trans-modal-content {
    background: #222;
    margin: 8px auto;
    padding: 30px;
    width: 500px;
    max-width: 90%;
    color: #fff;
    border: 1px solid #c9a961;
    position: relative;
    display: flex;
    flex-direction: column;
    margin-top: 215px;
}

.trans-modal-content h2 {
    margin-bottom: 1rem;
    color: #c9a961;
    font-size: 1.3rem;
    text-align: center;
}

/* Body container with image and text side by side */
.trans-modal-body {
    display: flex;
    gap: 20px; /* space between image and text */
    align-items: flex-start;
    justify-content: flex-start;
    flex-wrap: wrap; /* allows stacking on small screens */
}

.trans-modal-body img {
    border: 1px solid #c9a961;
    flex-shrink: 0;
    margin-left: 20px;
    margin-bottom: 20px;
}

.trans-modal-text p {
    margin-bottom: 0.6rem;
    font-size: 0.95rem;
    color: #fff;
}

.trans-modal-text strong {
    color: #c9a961;
}

.trans-close-btn {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 25px;
    cursor: pointer;
    color: #fff;
    line-height: 1;
    transition: color 0.2s ease;
}

.trans-close-btn:hover {
    color: #c9a961;
}

@media (max-width: 480px) {
    .trans-modal-content {
        width: 90%;
        margin: 20% auto;
    }
    .trans-modal-body {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .trans-modal-text p {
        margin-left: 0;
    }
}
    </style>

    <div class="chatWidget">
        <button class="chatBubble" id="chatBubbleID">
            <i class="fa-solid fa-message"></i>
        </button>

        <div class="chatBox" id="chatBoxID">
            <div class="chatHeader">
                <div class="chatHeaderInfo">
                    <div class="chatAvatar"><i class="fa-solid fa-user"></i></div>
                    <div class="chatHeaderText">
                        <h3>Customer Support</h3>
                        <p class="chatStatus">Online just now</p>
                    </div>
                </div>
                <button class="chatCloseBtn" id="chatCloseID"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="chatMessages" id="chatMessagesID">

            </div>
            <div class="chatInput">
                <input type="text" id="chatInputField" placeholder="Type your message...">
                <button id="chatSendBtn"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <footer id="footer">

        <div class="newsletter-section">
            <div class="newsletter-container">
                <h2>Newsletter</h2>
                <p>Sign up to receive our latest updates</p>
                <form class="newsletter-form" id="newsletterForm">
                    <input type="email" placeholder="EMAIL" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="footer-links-section">
            <div class="footer-links-container">
                <div class="footer-column">
                    <h3>About Us</h3>
                    <p>Homme d'Or is your premier destination for luxury scents.</p>
                </div>

                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="AboutUs.php">About Us</a></li>
                        <li><a href="blog.php">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Follow Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-column">
                    <h3>Contact Info</h3>
                    <ul class="contact-list">
                        <li><i class="fa-solid fa-location-dot"></i> BulSU - Hagonoy Campus</li>
                        <li><i class="fa-solid fa-phone"></i> +63 123 456 7890</li>
                        <li><i class="fa-solid fa-envelope"></i> info@hommedor.com</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom-section">
            <div class="footer-bottom-container">
                <p class="copyright">&copy; 2024 Homme d'Or. All rights reserved.</p>
                <div class="footer-logo">
                    <a href="index.php">
                        <img src="../assets/images/brand_images/prodLogo.png" alt="Logo">
                    </a>
                </div>
                <div class="language-selector">
                    <i class="fas fa-globe"></i>
                    <select id="languageSelect">
                        <option value="en">ENG</option>
                        <option value="fil">FIL</option>
                    </select>
                </div>
            </div>
        </div>
    </footer>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/msgStyle.css">

    <script src="../assets/js/regModal.js"></script>
    <script src="../assets/js/logModal.js"></script>

    <script>
        const USER_ID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
    </script>

    <script src="../assets/js/ChatBubble.js"></script>
    <script src="../assets/js/forgotPassword.js"></script>
    <script src="../assets/js/MobileMenu.js"></script>