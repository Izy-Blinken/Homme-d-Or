<!--Register Modal-->

<div id="signupModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        
        <div class="signupTitle">
            <h2><b>SIGN UP</b></h2>
        </div>

        <form action="index.php" method="POST">
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

            <div class="inputGroup">
                <input type="password" name="password" required>
                <label>PASSWORD*</label>
            </div>

            <div class="inputGroupCP">
                <input type="password" name="confirm_password" required>
                <label>CONFIRM PASSWORD*</label>
            </div>

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
                    <a href="#" onclick="event.preventDefault(); closeSignupModal();">CANCEL</a>
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
                <a href="forgotP.php">Forgot Password?</a>
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


<script src="../assets/js/regModal.js"></script>
<script src="../assets/js/logModal.js"></script>


<footer id="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Us</h3>
            <p>Homme d'Or is a bla bla bla. We provide quality products and excellent service.</p>
        </div>
        
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="AboutUs.php">About Us</a></li>
                <li><a href="ContactUs.php">Contact</a></li>
                <li><a href="blog.php">Blog</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
        
        <div class="footer-section">
            <h3>Contact Info</h3>
            <p><i class="fa-solid fa-location-dot"></i> Bulacan State University - Hagonoy Campus</p>
            <p><i class="fa-solid fa-phone"></i> +63 123 456 7890</p>
            <p><i class="fa-solid fa-envelope"></i> info@hommedor.com</p>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2024 Homme d'Or. All rights reserved.</p>
    </div>
</footer>