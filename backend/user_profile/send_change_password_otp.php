<?php
session_start();
date_default_timezone_set('Asia/Manila');
require '../db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'] ?? '';

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'"));

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

// Only verify current password on first send, not resend
if ($current_password && !password_verify($current_password, $user['user_password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

// 60s cooldown
$email = $user['email'];
$safe_email = mysqli_real_escape_string($conn, $email);
$last = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT created_at FROM password_resets WHERE email = '$safe_email' ORDER BY created_at DESC LIMIT 1"
));
if ($last && (time() - strtotime($last['created_at'])) < 60) {
    echo json_encode(['success' => false, 'message' => 'Please wait before requesting a new code.']);
    exit;
}

$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
$safe_otp = mysqli_real_escape_string($conn, $otp);

mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");
mysqli_query($conn, "INSERT INTO password_resets (email, token, expires_at, used) VALUES ('$safe_email', '$safe_otp', '$expires_at', 0)");

$_SESSION['cp_email'] = $email;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hommedor2026@gmail.com';
    $mail->Password = 'esoczvhrdrmilpbn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Timeout = 15;
    $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];

    $mail->setFrom('hommedor2026@gmail.com', "Homme d'Or");
    $mail->addAddress($email, $user['fname']);
    $mail->isHTML(true);
    $mail->Subject = "Your Password Change Verification Code";
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
            <h2 style='color:#333;'>Change Password</h2>
            <p>Hi <strong>{$user['fname']}</strong>,</p>
            <p>Use the 6-digit code below to confirm your password change:</p>
            <div style='font-size:36px;font-weight:bold;letter-spacing:10px;
                        text-align:center;padding:20px;background:#f4f4f4;
                        border-radius:8px;margin:20px 0;'>{$otp}</div>
            <p>This code expires in <strong>5 minutes</strong>.</p>
            <p>If you didn't request this, ignore this email.</p>
            <p style='color:#aaa;font-size:12px;'>Homme d'Or &copy; 2026</p>
        </div>
    ";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");
    echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again.']);
}
exit;