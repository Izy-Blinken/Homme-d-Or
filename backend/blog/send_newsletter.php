<?php
session_start();
include __DIR__ . '/../db_connect.php';

if (empty($_SESSION['superadmin_id'])) {
    $_SESSION['error'] = 'Unauthorized.';
    header('Location: ../../pages/Admin Pages/admin_newsletter.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/admin_newsletter.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';

$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($subject) || empty($message)) {
    $_SESSION['error'] = 'Subject and message are required.';
    header('Location: ../../pages/Admin Pages/admin_newsletter.php');
    exit;
}

$subscribers = [];
$result = mysqli_query($conn, "SELECT email FROM newsletter_subscribers WHERE is_active = 1");
while ($row = mysqli_fetch_assoc($result)) {
    $subscribers[] = $row['email'];
}

if (empty($subscribers)) {
    $_SESSION['error'] = 'No active subscribers to send to.';
    header('Location: ../../pages/Admin Pages/admin_newsletter.php');
    exit;
}

$sent = 0;
$failed = 0;
$safe_message = nl2br(htmlspecialchars($message));

foreach ($subscribers as $email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hommedor2026@gmail.com';
        $mail->Password = 'esoczvhrdrmilpbn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->Timeout = 20;
        $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];

        $mail->setFrom('hommedor2026@gmail.com', "Homme d'Or");
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;background:#0a0f1e;color:#e0d5c1;padding:40px;'>
                <div style='text-align:center;margin-bottom:32px;'>
                    <h1 style='color:#c9a961;letter-spacing:4px;font-size:22px;'>HOMME D'OR</h1>
                    <div style='width:60px;height:1px;background:#c9a961;margin:12px auto;'></div>
                </div>
                <h2 style='color:#c9a961;font-size:18px;margin-bottom:20px;'>" . htmlspecialchars($subject) . "</h2>
                <div style='font-size:14px;line-height:1.8;color:#ccc;'>$safe_message</div>
                <div style='margin-top:40px;padding-top:24px;border-top:1px solid rgba(212,175,55,0.2);font-size:11px;color:#666;text-align:center;'>
                    You're receiving this because you subscribed to Homme d'Or updates.<br>
                <a href='http://localhost/homme_dor/pages/unsubscribe.php?email=" . urlencode($email) . "' style='color:#c9a961;'>Unsubscribe</a>                </div>
            </div>
        ";

        $mail->send();
        $sent++;
    } catch (Exception $e) {
        $failed++;
    }
}

$_SESSION['success'] = "Newsletter sent to $sent subscriber" . ($sent !== 1 ? 's' : '') . ($failed ? ". $failed failed." : ".");
header('Location: ../../pages/Admin Pages/admin_newsletter.php');
exit;