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
        ============================================== -->
        <!-- Unified Modal Start -->
            <div id="unifiedModal" class="romcomOverlay">
                <div class="romcomModalContent">
                    <div class="romcomHeader">
                        <span class="view-close-btn" onclick="closeUnifiedModal()">&times;</span>
                        <h2 id="unifiedModalTitle">Details</h2>
                    </div>
                    <div class="romcomDivider"></div>

                    <div class="romcomBody">
                        <!-- Product Info -->
                        <div class="view-section">
                            <h4>PRODUCT</h4>
                            <div class="view-product">
                                <img id="unifiedImage" src="" width="70" alt="Product Image">
                                <div>
                                    <p id="unifiedProduct"></p>
                                    <small id="unifiedVariant"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Order/Transaction Info -->
                        <div class="view-section">
                            <h4>INFORMATION</h4>
                            <p><strong>Quantity:</strong> <span id="unifiedQty"></span></p>
                            <p><strong>Price:</strong> <span id="unifiedPrice"></span></p>
                            <p><strong>Subtotal:</strong> <span id="unifiedSubtotal"></span></p>
                            <p><strong>Payment Method:</strong> <span id="unifiedPayment"></span></p>
                            <p><strong>Date:</strong> <span id="unifiedDate"></span></p>
                            <p><strong>Status:</strong> <span id="unifiedStatus"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <style>
            /* Overlay */
            .romcomOverlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                z-index: 99999;
                align-items: center;
                justify-content: center;
            }

            /* Modal Content */
            .romcomModalContent {
                background: #2A2D34;
                width: 100%;
                max-width: 32rem;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                color: #e2e8f0;
            }

            /* Header */
            .romcomHeader {
                padding: 2rem 2.5rem 1.5rem 2.5rem;
                text-align: center;
                position: relative;
            }
            .romcomHeader h2 {
                font-size: 2rem;
                font-weight: 700;
                color: #c9a961;
            }
            .view-close-btn {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 25px;
                cursor: pointer;
                color: #fff;
                line-height: 1;
                transition: color 0.2s ease;
            }
            .view-close-btn:hover { color: #c9a961; }

            /* Divider */
            .romcomDivider {
                height: 1px;
                background: whitesmoke;
                margin: 0 2rem;
            }

            /* Body */
            .romcomBody {
                padding: 2rem 2.5rem;
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            /* Sections */
            .view-section {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .view-section h4 {
                font-size: 1.125rem;
                font-weight: 600;
                color: #c9a961;
                margin-bottom: 0.5rem;
            }

            /* Product */
            .view-product {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            .view-product img {
                width: 70px;
                height: auto;
                object-fit: cover;
                border: 1px solid #c9a961;
            }
            .view-product p {
                font-size: 1rem;
                font-weight: 600;
                margin: 0;
            }
            .view-product small {
                font-size: 0.875rem;
                color: whitesmoke;
                margin: 0;
            }

            /* Info Text */
            .view-section p {
                font-size: 0.875rem;
                margin: 0.25rem 0;
                display: flex;
                justify-content: space-between;
                color: #e0c27f;
            }
            .view-section p strong { color: #fff; }

            /* Scrollbar */
            .romcomBody::-webkit-scrollbar {
                width: 6px;
            }
            .romcomBody::-webkit-scrollbar-thumb { background: #c9a961; }
            .romcomBody::-webkit-scrollbar-track { background: #2A2D34; }

            /* Responsive */
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
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeUnifiedModal();
                });
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
                    <p class="copyright">&copy; 2026 Homme d'Or. All rights reserved.</p>
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

        <script>
            const USER_ID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        </script>

        <script src="../assets/js/ChatBubble.js"></script>
        <script src="../assets/js/forgotPassword.js"></script>
        <script src="../assets/js/MobileMenu.js"></script>
