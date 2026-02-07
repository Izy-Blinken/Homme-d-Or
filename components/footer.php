
<!--Register Modal-->
<div id="signupModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        
        <div class="signupTitle">
            <h2><b>SIGN UP</b></h2>
        </div>

        <form id="registerForm" action="index.php" method="POST">
            <div class="inputGroup">
                <input type="text" name="firstname" required>
                <label>FIRST NAME*</label>
            </div>

            <div class="inputGroup">
                <input type="text" name="lastname" required>
                <label>LAST NAME*</label>
            </div>

            <div class="inputGroupBD">
                <input type="date" name="birthday" required>
                <label>BIRTHDAY*</label>
            </div>
            
            <div class="inputGroup">
                <input type="text" name="phone" maxlength="11" required>
                <label>PHONE*</label>
            </div>

            <div class="inputGroup">
                <input type="email" name="email" required>
                <label>EMAIL*</label>
            </div>

            <!-- Password Requirements Box -->
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

            <!-- Password Strength Indicator -->
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

            <!-- Password Match Indicator -->
            <span class="passwordMatchReg" id="regPasswordMatch"></span>

            <div class="inputGroupCheckbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I have read and agreed on <a href="terms.php" target="_blank">Terms and Conditions</a></label>
            </div>

            <div class="captchaContainer">
                
            </div>

            <div class="regBtn">
                <button type="submit">CREATE ACCOUNT</button>
            </div>

            <div class="regLog">
                <label>
                    <a href="#" onclick="event.preventDefault(); openLoginModal();">LOGIN</a>
                        or
                    <a href="#" onclick="event.preventDefault(); closeSignupModal();">CONTINUE AS GUEST</a>
                </label>
            </div>
        </form>
    </div>
</div>

<!--Login Modal-->
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
                <a href="sendEmailCode.php">Forgot Password?</a>
            </div>
            
            <div class="regBtn">
                <button type="submit">LOGIN</button>
            </div>
            
            <div class="orContainer">
                <div class="or-divider">
                    <span>or</span>
                </div>

                <div class="member-exclusive">
                    <h3><i><b>Member Exclusive</b></i></h3>
                    
                    <p class="member-description">
                        Unlock a world of scent. Gain early access to 
                        limited-edition releases and private events. 
                        Members enjoy priority access to limited releases, bespoke consultations, and 
                        signature rewards tailored to your unique profile.
                    </p>
                    
                    <a href="index.php" class="discover-btn">DISCOVER</a>
                    
                    <button type="button" class="create-account-btn" onclick="closeLoginModal(); openSignupModal();">CREATE ACCOUNT</button>
                </div>
            </div>
        </form>
    </div>
</div>



        
            <div class="chatWidget">
                
                <button class="chatBubble" id="chatBubbleID">
                    <i class="fa-solid fa-message"></i>
                </button>

                <div class="chatBox" id="chatBoxID">
                    
                    <div class="chatHeader">
                        <div class="chatHeaderInfo">
                            <div class="chatAvatar">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="chatHeaderText">
                                <h3>Customer Support</h3>
                                <p class="chatStatus">Online</p>
                            </div>
                        </div>
                        <button class="chatCloseBtn" id="chatCloseID">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <div class="chatMessages" id="chatMessagesID">
                        <div class="chatMessage chatMessageLeft">
                            <div class="chatMessageAvatar">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="chatInput">
                        <input type="text" id="chatInputField" placeholder="Type your message...">
                        <button id="chatSendBtn">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                    
                </div>
            </div>

<link rel="stylesheet" href="../assets/css/msgStyle.css">
<script src="../assets/js/regModal.js"></script>
<script src="../assets/js/logModal.js"></script>
<script src="../assets/js/ChatBubble.js"></script>


<footer id="footer">
    <!-- Newsletter Section -->
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

    <!-- Footer Links Section -->
    <div class="footer-links-section">
        <div class="footer-links-container">
            <!-- About Us Column -->
            <div class="footer-column">
                <h3>About Us</h3>
                <p>Homme d'Or is a bla bla bla. We provide quality products and excellent service.</p>
            </div>

            <!-- Quick Links Column -->
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="AboutUs.php">About Us</a></li>
                    <li><a href="blog.php">Blog</a></li>
                </ul>
            </div>

            <!-- Follow Us Column -->
            <div class="footer-column">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <!-- Contact Info Column -->
            <div class="footer-column">
                <h3>Contact Info</h3>
                <ul class="contact-list">
                    <li><i class="fa-solid fa-location-dot"></i> Bulacan State University - Hagonoy Campus</li>
                    <li><i class="fa-solid fa-phone"></i> +63 123 456 7890</li>
                    <li><i class="fa-solid fa-envelope"></i> info@hommedor.com</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Section -->
    <div class="footer-bottom-section">
        <div class="footer-bottom-container">
            <p class="copyright">&copy; 2024 Homme d'Or. All rights reserved.</p>
            <div class="footer-logo">
                <a href="index.php">
                    <img src="../assets/images/brand_images/prodLogo.png" alt="Homme d'Or Logo">
                </a>
            </div>
            <div class="language-selector">
                <i class="fas fa-globe"></i>
                <select id="languageSelect">
                    <option value="en">ENG</option>
                    <option value="fil">FIL</option>
                    <option value="es">ESP</option>
                </select>
            </div>
        </div>
    </div>
</footer>