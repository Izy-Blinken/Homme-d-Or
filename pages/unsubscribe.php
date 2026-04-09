<?php
include '../backend/db_connect.php';

$email = trim($_GET['email'] ?? '');
$message = '';
$success = false;

if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $safe_email = mysqli_real_escape_string($conn, $email);
    $check = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM newsletter_subscribers WHERE email = '$safe_email'"));

    if ($check) {
        mysqli_query($conn, "UPDATE newsletter_subscribers SET is_active = 0 WHERE email = '$safe_email'");
        $message = 'You have been unsubscribed successfully.';
        $success = true;
    } else {
        $message = 'Email not found in our subscribers list.';
    }
} else {
    $message = 'Invalid unsubscribe link.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe — Homme d'Or</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
</head>
<body>
<?php include '../components/header.php'; ?>
<main style="background-image:url('../assets/images/brand_images/bg-image.jpg'); background-size:cover; background-position:center; background-attachment:fixed; background-color:#0e101f; min-height:100vh; display:flex; align-items:center; justify-content:center;">
    <div style="text-align:center; padding:60px 40px; background:rgba(0,0,0,0.6); border:1px solid rgba(212,175,55,0.3); max-width:480px; width:90%;">
        <i class="fa-solid <?= $success ? 'fa-circle-check' : 'fa-circle-xmark' ?>"
           style="font-size:3rem; color:<?= $success ? '#c9a961' : '#ef5350' ?>; margin-bottom:20px;"></i>
        <h2 style="color:#c9a961; letter-spacing:3px; font-size:1rem; text-transform:uppercase; margin-bottom:12px;">
            <?= $success ? 'Unsubscribed' : 'Oops' ?>
        </h2>
        <p style="color:#aaa; font-size:14px; line-height:1.8;"><?= htmlspecialchars($message) ?></p>
        <a href="index.php" style="display:inline-block; margin-top:24px; color:#c9a961; font-size:13px; letter-spacing:2px; text-decoration:none;">← Back to Home</a>
    </div>
</main>
<?php include '../components/footer.php'; ?>
</body>
</html>