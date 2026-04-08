<?php
session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$user_id = $_SESSION['pending_user_id'] ?? null;
$email   = $_SESSION['pending_user_email'] ?? null;
$fname   = $_SESSION['pending_user_fname'] ?? null;

if (!$user_id || !$email) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please sign up again.']);
    exit;
}

//60 seconds before resend
$last_result = mysqli_query($conn,
    "SELECT expires_at FROM email_verifications
     WHERE user_id = '$user_id'
     ORDER BY expires_at DESC LIMIT 1"
);
$last = mysqli_fetch_assoc($last_result);
$last = mysqli_fetch_assoc($last_result);
if ($last) {
    $issued_at = strtotime($last['expires_at']) - (5 * 60); // expires_at minus 5 min = when it was created
    if ((time() - $issued_at) < 60) {
        echo json_encode(['success' => false, 'message' => 'Please wait before requesting a new code.']);
        exit;
    }
}

// Generate random OTP
$otp        = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
$safe_otp   = mysqli_real_escape_string($conn, $otp);

mysqli_query($conn, "DELETE FROM email_verifications WHERE user_id = '$user_id'");
mysqli_query($conn, "
    INSERT INTO email_verifications (user_id, token, expires_at)
    VALUES ('$user_id', '$safe_otp', '$expires_at')
");

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hommedor2026@gmail.com';
    $mail->Password   = 'esoczvhrdrmilpbn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->SMTPOptions = ['ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true
    ]];

    $mail->setFrom('hommedor2026@gmail.com', "Homme d'Or");
    $mail->addAddress($email, $fname);
    $mail->isHTML(true);
    $mail->Subject = "Your Homme d'Or OTP Verification Code";
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
            <h2 style='color:#333;'>Email Verification</h2>
            <p>Hi <strong>{$fname}</strong>,</p>
            <p>Here is your new verification code:</p>
            <div style='font-size:36px;font-weight:bold;letter-spacing:10px;
                        text-align:center;padding:20px;background:#f4f4f4;
                        border-radius:8px;margin:20px 0;'>{$otp}</div>
            <p>This code expires in <strong>5 minutes</strong>.</p>
            <p style='color:#aaa;font-size:12px;'>Homme d'Or &copy; 2026</p>
        </div>
    ";
    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
            'success' => false, 
            'message' => 'Failed to send email. Please try again.',
            'debug'   => $mail->ErrorInfo
        ]);}
exit;