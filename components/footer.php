<div id="signupModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="signupTitle">
                    <h2><b>SIGN UP</b></h2>
                </div>

                <form id="registerForm" method="POST">

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
                        <label for="terms">I have read and agreed on <a href="#" onclick="event.preventDefault(); openTermsModal();">Terms and Conditions</a></label>
                    </div>

                    <div class="captchaContainer">
                        <div class="captcha-left">
                            <canvas id="captchaCanvas" width="180" height="58"></canvas>
                            <button type="button" class="refresh-btn" onclick="generateCaptcha()">↻</button>
                        </div>
                        <div class="captcha-right">
                            <input type="text" id="captchaInput" placeholder="Enter characters" maxlength="6" autocomplete="off" />
                        </div>
                    </div>

                    <div class="regBtn">
                        <p id="signupServerError" style="color:red; text-align:center; display:none;"></p>
                        <button type="submit" id="createAccountBtn">CREATE ACCOUNT</button>
                    </div>
                    <div class="regLog">
                        <label>
                            <a href="#" onclick="event.preventDefault(); openLoginModal();">LOGIN</a> or
                            <a href="../backend/loginSignUp/guest_login.php">CONTINUE AS GUEST</a>
                        </label>
                    </div>
                </form>
            </div>
        </div>

        <div id="loginModal" class="modal">
            <div class="modal-content modal-content-login">
                <span class="close">&times;</span>
                <div class="signupTitle">
                    <h2><b>SIGN IN</b></h2>
                </div>

                <form action="../backend/loginSignUp/login.php" method="POST">
                     <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                     
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

        <!-- TRANSACTION MODAL -->
        <div id="unifiedModal" class="romcomOverlay">
            <div class="romcomModalContent">
                <div class="romcomHeader">
                    <span class="view-close-btn" onclick="closeUnifiedModal()">&times;</span>
                    <h2 id="unifiedModalTitle">Details</h2>
                </div>
                <div class="romcomDivider"></div>
                <div class="romcomBody">
                    <div class="view-section">
                        <h4>PRODUCT</h4>
                        <div class="view-product">
                            <img id="unifiedImage" src="" width="70" alt="Product Image">
                            <div>
                                <p id="unifiedProduct"></p>
                                <small id="unifiedVariant" style="color: gray; font-weight:bold"></small>
                            </div>
                        </div>
                    </div>
                    <div class="view-section">
                        <h4>INFORMATION</h4>
                        <p><strong style="color: gray;">Quantity:</strong> <span id="unifiedQty" style="color: #c9a961; font-weight:bold"></span></p>
                        <p><strong style="color: gray;">Price:</strong> <span id="unifiedPrice" style="color: #c9a961; font-weight:bold"></span></p>
                        <p><strong style="color: gray;">Subtotal:</strong> <span id="unifiedSubtotal" style="color: #c9a961; font-weight:bold"></span></p>
                        <p><strong style="color: gray;">Payment Method:</strong> <span id="unifiedPayment" style="color: #c9a961; font-weight:bold"></span></p>
                        <p><strong style="color: gray;">Date:</strong> <span id="unifiedDate" style="color: #c9a961; font-weight:bold"></span></p>
                        <p><strong style="color: gray;">Status:</strong> <span id="unifiedStatus" style="color: #c9a961; font-weight:bold"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .romcomOverlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 99999; align-items: center; justify-content: center; }
        .romcomModalContent { background: #2A2D34; width: 100%; max-width: 32rem; display: flex; flex-direction: column; overflow: hidden; color: #e2e8f0; }
        .romcomHeader { padding: 2rem 2.5rem 1.5rem 2.5rem; text-align: center; position: relative; }
        .romcomHeader h2 { font-size: 2rem; font-weight: 700; color: #c9a961; }
        .view-close-btn { position: absolute; top: 10px; right: 15px; font-size: 25px; cursor: pointer; color: #fff; line-height: 1; transition: color 0.2s ease; }
        .view-close-btn:hover { color: #c9a961; }
        .romcomDivider { height: 1px; background: whitesmoke; margin: 0 2rem; }
        .romcomBody { padding: 2rem 2.5rem; display: flex; flex-direction: column; gap: 1.5rem; }
        .view-section { display: flex; flex-direction: column; gap: 0.5rem; }
        .view-section h4 { font-size: 1.125rem; font-weight: 600; color: #c9a961; margin-bottom: 0.5rem; }
        .view-product { display: flex; align-items: center; gap: 1rem; }
        .view-product img { width: 70px; height: auto; object-fit: cover; border: 1px solid #c9a961; }
        .view-product p { font-size: 1rem; font-weight: 600; margin: 0; }
        .view-product small { font-size: 0.875rem; color: whitesmoke; margin: 0; }
        .view-section p { font-size: 0.875rem; margin: 0.25rem 0; display: flex; justify-content: space-between; color: #e0c27f; }
        .view-section p strong { color: #fff; }
        .romcomBody::-webkit-scrollbar { width: 6px; }
        .romcomBody::-webkit-scrollbar-thumb { background: #c9a961; }
        .romcomBody::-webkit-scrollbar-track { background: #2A2D34; }
        @media (max-width: 640px) {
            .romcomModalContent { max-width: 90%; }
            .romcomBody { padding: 1.5rem; }
            .view-product { gap: 0.75rem; }
            .view-section p { flex-direction: column; align-items: flex-start; }
        }
        </style>

        <script>
        function openUnifiedModal(options) {
            document.getElementById("unifiedModalTitle").textContent = options.title || "Details";
            document.getElementById("unifiedImage").src = options.img || "";
            document.getElementById("unifiedProduct").textContent = options.product || "";
            document.getElementById("unifiedVariant").textContent = options.variant || "";
            document.getElementById("unifiedQty").textContent = options.qty || "";
            document.getElementById("unifiedPrice").textContent = options.price || "";
            document.getElementById("unifiedSubtotal").textContent = options.subtotal || "";
            document.getElementById("unifiedPayment").textContent = options.payment || "";
            document.getElementById("unifiedDate").textContent = options.date || "";
            document.getElementById("unifiedStatus").textContent = options.status || "";

            const modal = document.getElementById("unifiedModal");
            modal.classList.remove("closing");
            modal.style.display = "flex";
            setTimeout(() => modal.classList.add("show"), 10);
        }

        function closeUnifiedModal() {
            const modal = document.getElementById("unifiedModal");
            modal.classList.add("closing");
            setTimeout(() => {
                modal.classList.remove("show", "closing");
                modal.style.display = "none";
            }, 200);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('unifiedModal');
            if(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeUnifiedModal();
                });
            }
        });

        function viewTransaction(btn) {
            const options = {
                title: btn.dataset.title,
                img: btn.dataset.img,
                product: btn.dataset.product,
                variant: btn.dataset.variant,
                qty: btn.dataset.qty,
                price: btn.dataset.price,
                subtotal: btn.dataset.subtotal,
                payment: btn.dataset.payment,
                date: btn.dataset.date,
                status: btn.dataset.status
            };
            openUnifiedModal(options);
        }
        </script>

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
                        </div>
                    </div>
                    <button class="chatCloseBtn" id="chatCloseID"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="chatMessages" id="chatMessagesID"></div>
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
                <div class="footer-bottom-container" style="justify-content: center; gap: 2rem;">
                    <p class="copyright">&copy; 2026 Homme d'Or. All rights reserved.</p>
                    <div class="footer-logo">
                        <a href="index.php">
                            <img src="../assets/images/brand_images/prodLogo.png" alt="Logo">
                        </a>
                    </div>

                </div>
            </div>


            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                
                // If the URL has our secret signal from the Guest button
                if (urlParams.get('guest_activated') === 'true') {
                    
                    // 1. Clean the URL so it looks normal again
                    const newUrl = window.location.pathname + window.location.search.replace(/[\?&]guest_activated=true/, '');
                    window.history.replaceState({}, document.title, newUrl);
                    
                    // 2. Trigger the visible "mark" (Your Toast Notification!)
                    if (typeof showGeneralToast === 'function') {
                        showGeneralToast('Guest Mode Activated! You can now add items to your cart.', 'success');
                    }
                }
            });
        </script>
        </footer>

        <!-- TERMS AND CONDITIONS MODAL -->
        <div id="termsModal" class="modal">
            <div class="modal-content" style="max-width:700px;">
                <span class="close" id="termsModalClose">&times;</span>
                <div class="signupTitle">
                    <h2><b>TERMS AND CONDITIONS</b></h2>
                </div>
                <div class="terms-body">
                    <p class="terms-effective">Effective Date: January 1, 2026</p>
                    <p>Welcome to <strong>Homme d'Or</strong>. By accessing or using our website and services, you agree to be bound by the following Terms and Conditions. Please read them carefully.</p>

                    <h4>1. Acceptance of Terms</h4>
                    <p>By creating an account or placing an order on our platform, you confirm that you have read, understood, and agreed to these Terms and Conditions. If you do not agree, please discontinue use of our services.</p>

                    <h4>2. Account Registration</h4>
                    <p>You must provide accurate and complete information when registering. You are responsible for maintaining the confidentiality of your account credentials. Homme d'Or reserves the right to suspend or terminate accounts found to be in violation of these terms.</p>

                    <h4>3. Orders and Payments</h4>
                    <p>All orders are subject to availability and confirmation. Prices are listed in Philippine Peso (₱) and may change without prior notice. We reserve the right to cancel any order due to pricing errors or stock unavailability.</p>

                    <h4>4. Shipping and Delivery</h4>
                    <p>Delivery times are estimates and not guaranteed. Homme d'Or is not liable for delays caused by third-party couriers or circumstances beyond our control. Risk of loss transfers to the buyer upon delivery.</p>

                    <h4>5. Returns and Refunds</h4>
                    <p>Due to the nature of our products, returns are only accepted for items that are damaged or defective upon arrival. Requests must be made within 7 days of receipt with valid proof. Refunds, if approved, will be processed within 5–10 business days.</p>

                    <h4>6. Intellectual Property</h4>
                    <p>All content on this site — including logos, images, and text — is the exclusive property of Homme d'Or. Unauthorized reproduction or distribution is strictly prohibited.</p>

                    <h4>7. Privacy</h4>
                    <p>Your personal information is collected and used in accordance with our Privacy Policy. We do not sell or share your data with third parties without your consent.</p>

                    <h4>8. Limitation of Liability</h4>
                    <p>Homme d'Or shall not be held liable for any indirect, incidental, or consequential damages arising from the use of our products or services beyond the amount paid for the transaction in question.</p>

                    <h4>9. Changes to Terms</h4>
                    <p>We reserve the right to update these Terms at any time. Continued use of our services after changes are posted constitutes your acceptance of the revised Terms.</p>

                    <h4>10. Contact Us</h4>
                    <p>For questions regarding these Terms, please reach out to us at <strong>info@hommedor.com</strong> or visit us at BulSU - Hagonoy Campus.</p>
                </div>
                <div class="regBtn" style="margin-top:30px;">
                    <button type="button" id="termsModalCloseBtn">CLOSE</button>
                </div>
            </div>
        </div>

        <style>
            .terms-body {
                color: white;
                font-family: 'Spartan', sans-serif;
                font-size: 13px;
                line-height: 1.9;
            }
            .terms-body h4 {
                color: #c9a961;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 1.5px;
                font-family: 'Spartan', sans-serif;
                margin: 1.4rem 0 0.4rem 0;
                border-left: 3px solid goldenrod;
                padding-left: 10px;
                text-transform: uppercase;
            }
            .terms-body p {
                margin: 0 0 0.5rem 0;
                color: #ccc;
                font-size: 12px;
                font-family: 'Spartan', sans-serif;
                line-height: 1.9;
            }
            .terms-body strong {
                color: #c9a961;
                font-family: 'Spartan', sans-serif;
            }
            .terms-effective {
                color: #c9a961 !important;
                font-weight: 600;
                font-size: 11px !important;
                letter-spacing: 1.5px;
                margin-bottom: 1rem !important;
                font-family: 'Spartan', sans-serif;
            }
        </style>

        <script>
            (function () {
                const modal     = document.getElementById('termsModal');
                const closeX    = document.getElementById('termsModalClose');
                const closeBtn  = document.getElementById('termsModalCloseBtn');

                function openTerms() {
                    modal.style.display = 'block';
                    setTimeout(() => modal.classList.add('show'), 10);
                }

                function closeTerms() {
                    modal.classList.add('closing');
                    modal.classList.remove('show');
                    setTimeout(() => {
                        modal.style.display = 'none';
                        modal.classList.remove('closing');
                    }, 400);
                }

                document.querySelectorAll('a[data-open-terms]').forEach(function(el) {
                    el.addEventListener('click', function(e) { e.preventDefault(); openTerms(); });
                });

                closeX.addEventListener('click', closeTerms);
                closeBtn.addEventListener('click', closeTerms);
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeTerms();
                });

                window.openTermsModal = openTerms;
            })();
        </script>

        <!-- CSS Inclusions -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/msgStyle.css">
        <link rel="stylesheet" href="../assets/css/VerifySignUp.css">

        <!-- JS Variables -->
        <script>
            const USER_ID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        </script>
        
        
        <script src="../assets/js/regModal.js"></script>
        <script src="../assets/js/logModal.js"></script>
        <script src="../assets/js/ChatBubble.js"></script>
        <script src="../assets/js/forgotPassword.js"></script>
        <script src="../assets/js/MobileMenu.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const newsletterForm = document.getElementById('newsletterForm');
                if (!newsletterForm) return;

                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const emailInput = newsletterForm.querySelector('input[type="email"]');
                    const btn = newsletterForm.querySelector('button[type="submit"]');
                    const email = emailInput.value.trim();

                    if (!email) return;

                    btn.disabled = true;
                    btn.textContent = 'Subscribing...';

                    const formData = new FormData();
                    formData.append('email', email);

                    fetch('../backend/blog/subscribe.php', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (typeof showGeneralToast === 'function') {
                                showGeneralToast(data.message, data.success ? 'success' : 'error');
                            } else {
                                alert(data.message);
                            }
                            if (data.success) {
                                emailInput.value = '';
                            }
                        })
                        .catch(() => {
                            if (typeof showGeneralToast === 'function') {
                                showGeneralToast('Could not connect. Please try again.', 'error');
                            }
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.textContent = 'Subscribe';
                        });
                });
            });
        </script>