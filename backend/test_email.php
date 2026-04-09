<?php
// Test email sending - Remove this file after testing
session_start();
include 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email, fname FROM users WHERE user_id = '$user_id'"));

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$email = $user['email'];
$testCode = '123456';

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2; // Enable verbose output for debugging
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hommedor2026@gmail.com';
    $mail->Password   = 'esoczvhrdrmilpbn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = 20;
    $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];

    $mail->setFrom('hommedor2026@gmail.com', "Homme d'Or");
    $mail->addAddress($email, $user['fname']);
    $mail->isHTML(true);
    $mail->Subject = "Test Email - Change Password";
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
            <h2 style='color:#333;'>Test Email</h2>
            <p>Hi <strong>{$user['fname']}</strong>,</p>
            <p>This is a test email to verify the email sending system is working.</p>
            <p>Test Code: <strong>{$testCode}</strong></p>
        </div>
    ";
    
    if ($mail->send()) {
        echo json_encode(['success' => true, 'message' => 'Test email sent successfully!', 'email' => $email]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Send failed: ' . $mail->ErrorInfo]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
} catch (Throwable $throwable) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $throwable->getMessage()]);
}
exit;
