<!DOCTYPE html>
<html>
    <head>
        <title>Verify Code</title>
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

    <main>
        <div id="changePasswordModal" class="verificationModal">
            <div class="verificationModalContent">
                <button class="closeModal" id="closePasswordModal">
                    <i class="fas fa-times"></i>
                </button>

                <div class="modalHeader">
                    <div class="modalIcon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2>Create New Password</h2>
                    <p class="modalSubtitle">Enter a strong password for your account</p>
                </div>

                <form id="changePasswordForm" action="successPasswordChange.php">
                    <div class="formGroup">
                        <label for="newPassword">New Password</label>
                        <div class="passwordInputWrapper">
                            <input 
                                type="password" 
                                id="newPassword" 
                                name="newPassword" 
                                placeholder="Enter new password" 
                                required
                                minlength="8"
                                autocomplete="new-password">
                            <button type="button" class="togglePassword" data-target="newPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="passwordStrength" id="passwordStrength">
                            <div class="strengthBar">
                                <div class="strengthBarFill" id="strengthBarFill"></div>
                            </div>
                            <span class="strengthText" id="strengthText"></span>
                        </div>
                    </div>

                    <div class="formGroup">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="passwordInputWrapper">
                            <input 
                                type="password" 
                                id="confirmPassword" 
                                name="confirmPassword" 
                                placeholder="Re-enter new password" 
                                required
                                minlength="8"
                                autocomplete="new-password">
                            <button type="button" class="togglePassword" data-target="confirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="passwordMatch" id="passwordMatch"></span>
                    </div>

                    <div class="passwordRequirements">
                        <p class="requirementsTitle">Password must contain:</p>
                        <ul>
                            <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                            <li id="req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                            <li id="req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                            <li id="req-number"><i class="fas fa-circle"></i> One number</li>
                        </ul>
                    </div>

                    <button type="submit" class="verifyButton" id="changePasswordBtn" onclick="window.location.href='../pages/successPasswordChange.php'" disabled>Confirm Change Password</button>
                </form>
            </div>
        </div>

        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/forgotPassword.js"></script>
    </main>
</html>