<div id="signupModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="signupTitle">
            <h2><b>SIGN UP</b></h2>
        </div>

        <form id="registerForm">
            <div class="inputGroup">
                <input type="text" name="firstname" id="signupFirstname" required>
                <label>FIRST NAME*</label>
            </div>
            <div class="inputGroup">
                <input type="text" name="lastname" id="signupLastname" required>
                <label>LAST NAME*</label>
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
        <form id="signupVerifyForm">
            <div class="signupCodeContainer">
                <input type="text" maxlength="1" class="signupCodeInput" data-index="0" inputmode="numeric" autocomplete="off" />
                <input type="text" maxlength="1" class="signupCodeInput" data-index="1" inputmode="numeric" autocomplete="off" />
                <input type="text" maxlength="1" class="signupCodeInput" data-index="2" inputmode="numeric" autocomplete="off" />
                <input type="text" maxlength="1" class="signupCodeInput" data-index="3" inputmode="numeric" autocomplete="off" />
                <input type="text" maxlength="1" class="signupCodeInput" data-index="4" inputmode="numeric" autocomplete="off" />
                <input type="text" maxlength="1" class="signupCodeInput" data-index="5" inputmode="numeric" autocomplete="off" />
            </div>
            <button type="submit" class="signupVerifyBtn">Verify Code</button>
            <div class="signupResendSection">
                <p>Didn't receive the code?</p>
                <button type="button" class="signupResendBtn" id="signupResendBtn">Resend Code</button>
            </div>
        </form>
    </div>
</div>

<div id="signupSuccessModal" class="signupSuccessModal" style="display: none;">
    <div class="signupSuccessContent">
        <h2>Account Created!</h2>
        <p>Your account has been created successfully.</p>
        <button class="signupSuccessBtn" id="signupSuccessBtn">Continue</button>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content modal-content-login">
        <span class="close">&times;</span>
        <div class="signupTitle">
            <h2><b>SIGN IN</b></h2>
        </div>
        <form action="index.php" method="POST">
            <div class="inputGroupLog">
                <input type="email" name="email" required>
                <label>EMAIL*</label>
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
                    <p class="chatStatus">Online</p>
                </div>
            </div>
            <button class="chatCloseBtn" id="chatCloseID"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="chatMessages" id="chatMessagesID">
            <div class="chatMessage chatMessageLeft">
                <div class="chatMessageAvatar"><i class="fa-solid fa-user"></i></div>
            </div>
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
<link rel="stylesheet" href="../assets/css/VerifySignUp.css">

<script src="../assets/js/regModal.js"></script>
<script src="../assets/js/logModal.js"></script>
<script src="../assets/js/ChatBubble.js"></script>
<script src="../assets/js/forgotPassword.js"></script>
<script src="../assets/js/VCSignUp.js"></script>
<script src="../assets/js/MobileMenu.js"></script>