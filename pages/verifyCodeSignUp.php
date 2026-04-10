<?php
session_start();

if (empty($_SESSION['pending_user_id'])) {
    header('Location: index.php');
    exit;
}

$pending_email = htmlspecialchars($_SESSION['pending_user_email'] ?? '');

if (!empty($_SESSION['signup_email_failed'])) {
    unset($_SESSION['signup_email_failed'], $_SESSION['pending_user_id']);
    // redirect back with error
    header("Location: index.php?signup_error=email_failed");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up Verification – Homme d'Or</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
    <link rel="stylesheet" href="../assets/css/VerifySignUp.css">
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="mainBG"></main>

    <!-- OTP Modal -->
    <div id="signupVerifyModal" class="signupVerificationModal">
        <div class="signupVerificationContent">
            <button class="signupCloseBtn" id="closeSignupVerify">
                <i class="fas fa-times"></i>
            </button>

            <div class="signupModalHeader">
                <h2>Enter Verification Code</h2>
                <p class="signupModalSubtitle">
                    We've sent a 6-digit code to
                    <span id="signupUserEmail"><?= $pending_email ?></span>
                </p>
            </div>

            <form id="signupVerifyForm">
                <div class="signupCodeContainer">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" maxlength="1" class="signupCodeInput"
                               data-index="<?= $i ?>" inputmode="numeric" autocomplete="off" />
                    <?php endfor; ?>
                </div>

                <p id="otpError" style="color:red; text-align:center; display:none; margin-top:8px;"></p>

                <button type="submit" class="signupVerifyBtn">Verify Code</button>

                <div class="signupResendSection">
                    <p>Didn't receive the code?</p>
                    <button type="button" class="signupResendBtn" id="signupResendBtn">Resend Code</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="signupSuccessModal" class="signupSuccessModal">
        <div class="signupSuccessContent">
            <div class="signupModalHeader">
                <h2>Account Verified!</h2>
                <p>Your account has been successfully verified. You can now log in.</p>
            </div>
            <button id="signupSuccessBtn" class="signupSuccessBtn">Go to Homepage</button>
        </div>
    </div>

    <?php include '../components/footer.php'; ?>

    <link rel="stylesheet" href="../assets/css/VerifySignUp.css">
    <script src="../assets/js/VCSignUp.js"></script>