<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
 
require __DIR__ . '/../../vendor/autoload.php';

function sendOTPEmail($to_email, $to_name, $otp) {
    $mail = new PHPMailer(true);

 
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hommedor2026@gmail.com'; // Supposed to be Email account ng "Homme d'Or"
        $mail->Password = 'esoczvhrdrmilpbn'; // Generated 16 code from google account
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => true
            )
        );
 
        $mail->setFrom('hommedor2026@gmail.com', 'Homme d\'Or'); // ← replace
        $mail->addAddress($to_email, $to_name);
 
        $mail->isHTML(true);
        $mail->Subject = 'Your Homme d\'Or OTP Verification Code';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto;'>
                <h2 style='color: #333;'>Email Verification</h2>
                <p>Hi <strong>{$to_name}</strong>,</p>
                <p>Thank you for signing up! Use the 6 digits OTP below to verify your email:</p>
                <div style='font-size: 36px; font-weight: bold; letter-spacing: 10px;
                            text-align: center; padding: 20px; background: #f4f4f4;
                            border-radius: 8px; margin: 20px 0;'>
                    {$otp}
                </div>
                <p>This code expires in <strong>5 minutes</strong>.</p>
                <p style='color: #aaa; font-size: 12px;'>Homme d'Or &copy; 2026</p>
            </div>
        ";

        $mail->send();
        return true;
 
    } catch (Exception $e) {
        error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/index.php');
    exit;
}

$fname = trim($_POST['firstname'] ?? '');
$lname = trim($_POST['lastname'] ?? '');
$bday = trim($_POST['birthday'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$fname || !$lname || !$email || !$username || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Please fill in all required fields."
    ]);
    exit;
}

if ($password !== $confirm) {
    echo json_encode([
        "success" => false,
        "message" => "Passwords do not match."
    ]);
    exit;
}

if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
     echo json_encode([
        "success" => false,
        "message" => "Password does not meet requirements."
    ]);
    exit;
}

// check if email is existing na
$safe_email = mysqli_real_escape_string($conn, $email);
$existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$safe_email'"));

if ($existing) {
    echo json_encode([
        "success" => false,
        "message" => "Email is already registered."
    ]);
    exit;
}

// check if username is existing na
$safe_username = mysqli_real_escape_string($conn, $username);
$existing_username = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM users WHERE username = '$safe_username'"));

if ($existing_username) {
    echo json_encode([
        "success" => false,
        "message" => "Username is already taken."
    ]);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$safe_fname = mysqli_real_escape_string($conn, $fname);
$safe_lname = mysqli_real_escape_string($conn, $lname);
$safe_bday = mysqli_real_escape_string($conn, $bday);
$safe_phone = mysqli_real_escape_string($conn, $phone);

mysqli_query($conn, "INSERT INTO users (fname, lname, bday, phone, email, username, user_password, is_verified)
     VALUES ('$safe_fname', '$safe_lname', '$safe_bday', '$safe_phone', '$safe_email', '$safe_username', '$hashed', 0)");

if (mysqli_affected_rows($conn) === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed. Please try again."
    ]);
    exit;
}

$user_id = mysqli_insert_id($conn);

// Random 6 digit OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
$safe_otp = mysqli_real_escape_string($conn, $otp);

mysqli_query($conn,
    "DELETE FROM email_verifications WHERE user_id = '$user_id'"
);
 
// Insert user to email verification DB table
mysqli_query($conn,
    "INSERT INTO email_verifications (user_id, token, expires_at)
     VALUES ('$user_id', '$safe_otp', '$expires_at')"
);

// Email sent
$sent = sendOTPEmail($email, $fname, $otp);

if (!$sent) {
    // Email failed — clean up and let user retry
    mysqli_query($conn, "DELETE FROM users WHERE user_id = '$user_id'");
    mysqli_query($conn, "DELETE FROM email_verifications WHERE user_id = '$user_id'");
    echo json_encode([
        "success" => false,
        "message" => "Failed to send verification email."
    ]);
    exit;
}


$_SESSION['pending_user_id'] = $user_id;
$_SESSION['pending_user_fname'] = $fname;
$_SESSION['pending_user_email'] = $email;
$_SESSION['pending_signup_success'] = true;

echo json_encode([
    "success" => true
]);
exit;