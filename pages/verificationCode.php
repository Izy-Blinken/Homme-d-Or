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

    <body>
        <main>
            <!-- Verification Code Modal -->
            <div id="verificationModal" class="verificationModal">
                <div class="verificationModalContent">
                    <button class="closeModal" id="closeVerificationModal">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="modalHeader">
                        <div class="modalIcon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h2>Enter Verification Code</h2>
                        <p class="modalSubtitle">We've sent a 6-digit code to <span id="userEmail"></span></p>
                    </div>

                    <form id="verificationForm">
                        <div class="codeInputContainer">
                            <input type="text" maxlength="1" class="codeInput" data-index="0" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="1" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="2" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="3" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="4" inputmode="numeric" />
                            <input type="text" maxlength="1" class="codeInput" data-index="5" inputmode="numeric" />
                        </div>

                        <button type="submit" class="verifyButton">Verify Code</button>

                        <div class="resendSection">
                            <p>Didn't receive the code?</p>
                            <button type="button" class="resendButton" id="resendButton">Resend Code</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/forgotPassword.js"></script>
    </body>
</html>