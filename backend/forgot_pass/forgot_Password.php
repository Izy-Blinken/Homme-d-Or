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

$email = trim($_POST['email'] ?? '');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$safe_email = mysqli_real_escape_string($conn, $email);

// Check if email exists
$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT user_id, fname FROM users WHERE email = '$safe_email' AND is_verified = 1 LIMIT 1"
));

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'No account found with that email address.']);
    exit;
}

// 60s cooldown
$last = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT created_at FROM password_resets WHERE email = '$safe_email' ORDER BY created_at DESC LIMIT 1"
));
if ($last && (time() - strtotime($last['created_at'])) < 60) {
    echo json_encode(['success' => false, 'message' => 'Please wait before requesting a new code.']);
    exit;
}

// Generate OTP
$otp        = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
$safe_otp   = mysqli_real_escape_string($conn, $otp);

// Delete old tokens for this email
mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");

// Insert new token
mysqli_query($conn,
    "INSERT INTO password_resets (email, token, expires_at, used)
     VALUES ('$safe_email', '$safe_otp', '$expires_at', 0)"
);

// Send email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hommedor2026@gmail.com';
    $mail->Password   = 'esoczvhrdrmilpbn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = 15;
    $mail->SMTPOptions = ['ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]];

    $mail->setFrom('hommedor2026@gmail.com', "Homme d'Or");
    $mail->addAddress($email, $user['fname']);
    $mail->isHTML(true);
    $mail->Subject = "Your Homme d'Or Password Reset Code";
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
            <h2 style='color:#333;'>Password Reset</h2>
            <p>Hi <strong>{$user['fname']}</strong>,</p>
            <p>Use the 6-digit code below to reset your password:</p>
            <div style='font-size:36px;font-weight:bold;letter-spacing:10px;
                        text-align:center;padding:20px;background:#f4f4f4;
                        border-radius:8px;margin:20px 0;'>{$otp}</div>
            <p>This code expires in <strong>5 minutes</strong>.</p>
            <p>If you didn't request this, ignore this email.</p>
            <p style='color:#aaa;font-size:12px;'>Homme d'Or &copy; 2026</p>
        </div>
    ";

    $mail->send();

    // Store email in session for next steps
    $_SESSION['reset_email'] = $email;

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Rollback
    mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");
    echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again.', 'debug' => $mail->ErrorInfo]);
}
exit;