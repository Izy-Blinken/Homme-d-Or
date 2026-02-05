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

<!--
    1. Ayusin yung modal for change pw, verify code, success pw change
    2. Gaiwng modal nalang yung orderAgain.php
    3. Gawing responsive lahat ng page
    4. naglagay js from ai para aralin
    5. add verification code din for signing up tanginuh
    * nag add and commit ako but di pa napupush since di pa tapos
    -->

    <body>
        <?php include '../components/header.php'; ?>

        <main class="mainBG">
            <div class="forgotPasswordContainer">
                <div class="fpHeader">
                    <h1>Forgot Password</h1>
                    <p class="fpSubtitle">Enter your email address and we'll send you a verification code</p>
                </div>

                <div class="fpForm">
                    <form id="emailForm">
                        <div class="formGroup">
                            <label for="email">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Enter your email" 
                                required
                                autocomplete="email">
                        </div>

                        <button type="submit" class="fpSendCode">Send Verification Code</button>

                        <div class="fpFooter">
                            <a href="index.php" class="backToLogin">
                                <i class="fas fa-arrow-left"></i> Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        
        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/forgotPassword.js"></script>
    </body>
</html>