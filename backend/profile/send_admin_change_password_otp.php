<?php
session_start();
date_default_timezone_set('Asia/Manila');
require '../db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';

header('Content-Type: application/json');

if (!isset($_SESSION['superadmin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$superadmin_id = (int) $_SESSION['superadmin_id'];
$current_password = $_POST['current_password'] ?? '';

$admin = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM superadmins WHERE superadmin_id = $superadmin_id LIMIT 1"
));

if (!$admin) {
    echo json_encode(['success' => false, 'message' => 'Admin not found.']);
    exit;
}

// Only verify current password on first send (not resend)
if ($current_password && !password_verify($current_password, $admin['sadmin_password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

$email = $admin['email'];
$safe_email = mysqli_real_escape_string($conn, $email);

// 60-second cooldown
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

$_SESSION['admin_cp_email'] = $email;

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
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Admin Password Change Verification Code";
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
            <h2 style='color:#333;'>Change Admin Password</h2>
            <p>A password change was requested for your administrator account.</p>
            <p>Use the 6-digit code below to confirm:</p>
            <div style='font-size:36px;font-weight:bold;letter-spacing:10px;
                        text-align:center;padding:20px;background:#f4f4f4;
                        border-radius:8px;margin:20px 0;'>{$otp}</div>
            <p>This code expires in <strong>5 minutes</strong>.</p>
            <p>If you didn't request this, please secure your account immediately.</p>
            <p style='color:#aaa;font-size:12px;'>Homme d'Or &copy; 2026</p>
        </div>
    ";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    mysqli_query($conn, "DELETE FROM password_resets WHERE email = '$safe_email'");
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to send email: ' . $mail->ErrorInfo  // <-- dito lang dinagdag
    ]);
}
exit;