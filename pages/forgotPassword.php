<!DOCTYPE html>
<html>
    <head>
        
        <title>Forgot Password</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
        <link rel="stylesheet" href="../assets/css/CheckoutPageStyle.css">
        <link rel="stylesheet" href="../assets/css/OrderAgainStyle.css">
        <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
        <link rel="stylesheet" href="../assets/css/AboutUsPageStyle.css">
        <link rel="stylesheet" href="../assets/css/ProductDetailsStyle.css">
        <link rel="stylesheet" href="../assets/css/CartPageStyle.css">
        <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
        <link rel="stylesheet" href="../assets/css/ProfilePageStyle.css">
        <link rel="stylesheet" href="../assets/css/ForgotPasswordStyle.css">
    </head>


    <body>
        <?php include '../components/header.php'; ?>

        <main class="mainBG">
            
            <div class="forgotPasswordContainer">
                <div class="fpHeader">
                    <h1>Forgot Password</h1>
                    <p class="fpSubtitle">Enter your email address and we'll send you a verification code</p>
                </div>

                <div class="fpForm">
                    <form id="emailForm" >
                        <div class="formGroup">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <button type="submit" class="fpSendCode" id="sendVC"  onclick="verifyCode()">Send Verification Code</button>

                        <div class="fpFooter">
                            <a href="index.php" class="backToLogin">
                                <i class="fas fa-arrow-left"></i> Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

            <!-- Verification Code Modal -->
            <div id="verificationModal" class="verificationModal">
                <div class="verificationModalContent">
                    <button class="closeModal" id="closeVerificationModal">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="modalHeader">
                        <h2>Enter Verification Code</h2>
                        <p class="modalSubtitle">We've sent a 6-digit code to <span id="userEmail"></span></p>
                    </div>

                    <form id="verificationForm"  >
                        <div class="codeInputContainer">
                            <input type="text" maxlength="1" class="codeInput" data-index="0" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="1" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="2" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="3" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="4" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="5" inputmode="numeric" />
                        </div>

                        <button type="submit" class="verifyButton" >Verify Code</button>

                        <div class="resendSection">
                            <p>Didn't receive the code?</p>
                            <button type="button" class="resendButton" id="resendButton">Resend Code</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Modal -->
            <div id="changePasswordModal" class="verificationModal">
                <div class="verificationModalContent">
                    <button class="closeModal" id="closeChangePasswordModal">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="modalHeader">
                        <h2>Create New Password</h2>
                        <p class="modalSubtitle">Your new password must be different from previous passwords</p>
                    </div>

                    <form id="changePasswordForm">
                        <!-- Password Requirements -->
                        <div class="passwordRequirements">
                            <p class="requirementsTitle">Password must contain:</p>
                            <ul>
                                <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                                <li id="req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                                <li id="req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                                <li id="req-number"><i class="fas fa-circle"></i> One number</li>
                            </ul>
                        </div>

                        <!-- New Password -->
                        <div class="formGroup">
                            <label for="newPassword">New Password</label>
                            <div class="passwordInputWrapper">
                                <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required>
                                <button type="button" class="togglePassword" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="passwordStrength">
                                <div class="strengthBar">
                                    <div class="strengthBarFill" id="strengthBarFill"></div>
                                </div>
                                <span class="strengthText" id="strengthText"></span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="formGroup">
                            <label for="confirmPassword">Confirm Password</label>
                            <div class="passwordInputWrapper">
                                <input 
                                    type="password" 
                                    id="confirmPassword" 
                                    name="confirmPassword" 
                                    placeholder="Confirm your password" 
                                    required>
                                <button type="button" class="togglePassword" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <span class="passwordMatch" id="passwordMatch"></span>
                        </div>

                        <button type="submit" class="verifyButton">Reset Password</button>
                    </form>
                </div>
            </div>

             <!-- Success Modal -->
            <div id="successModal" class="verificationModal">
                <div class="verificationModalContent successModalContent">
                    <div class="modalHeader">
                        
                        <h2>Password Changed Successfully!</h2>
                        <p class="modalSubtitle">You can now log in with your new password</p>
                    </div>

                    <a href="index.php" class="verifyButton" style="text-align: center; text-decoration: none; display: block;">
                        Home
                    </a>
                </div>
            </div>
        
        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/forgotPassword.js"></script>
    </body>
</html>