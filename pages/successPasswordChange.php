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
        <!-- Success Modal -->
        <div id="successModal" class="verificationModal">
            <div class="verificationModalContent successModalContent">
                <div class="modalHeader">
                    <div class="modalIcon successIcon">
                        <i class="fas fa-check"></i>
                    </div>
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
    </main>
</html>