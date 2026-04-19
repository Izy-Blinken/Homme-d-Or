<?php
session_start();
date_default_timezone_set('Asia/Manila');
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$user_id = $_SESSION['pending_user_id'] ?? null;
$otp = trim($_POST['code'] ?? ''); // JS sends a plain 6-digit string

if (!$user_id || strlen($otp) !== 6) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$safe_otp = mysqli_real_escape_string($conn, $otp);

// Check OTP exists, matches, is not expired, and not already used
$result = mysqli_query($conn, "
    SELECT * FROM email_verifications
    WHERE user_id = '$user_id'
      AND token = '$safe_otp'
      AND expires_at > NOW()
      AND verified_at IS NULL
    LIMIT 1
");

$data = mysqli_fetch_assoc($result);

if (!$data || strtotime($data['expires_at']) < time() ) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired code. Please try again.']);
    exit;
}

// Verfied na
mysqli_query($conn, "UPDATE email_verifications SET verified_at = NOW() WHERE id = '{$data['id']}'");
mysqli_query($conn, "UPDATE users SET is_verified = 1 WHERE user_id = '$user_id'");

$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1"
));

// Clear pending session data
unset(
    $_SESSION['pending_user_id'],
    $_SESSION['pending_user_email'],
    $_SESSION['pending_user_fname']
);

// ── GUEST MIGRATION ───────────────────────────────────────────────
if (!empty($_SESSION['guest_id'])) {
    $guest_session_id = mysqli_real_escape_string($conn, $_SESSION['guest_id']);

    $g = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT guest_id FROM guests WHERE session_id = '$guest_session_id'"
    ));

    if ($g) {
        $guest_id_int = (int)$g['guest_id'];

        // Migrate cart
        mysqli_query($conn, "
            INSERT INTO cart (user_id, product_id, quantity)
            SELECT '$user_id', product_id, quantity
            FROM cart c WHERE c.guest_id = '$guest_id_int'
            ON DUPLICATE KEY UPDATE cart.quantity = cart.quantity + VALUES(quantity)
        ");
        mysqli_query($conn, "DELETE FROM cart WHERE guest_id = '$guest_id_int'");

        // Migrate orders
        mysqli_query($conn, "
            UPDATE orders SET user_id = '$user_id', guest_id = NULL
            WHERE guest_id = '$guest_id_int'
        ");

        // Migrate guest notifications
        $notifs = mysqli_query($conn, "
            SELECT notif_type, notif_message, reference_id, is_read, created_at
            FROM guest_notifications
            WHERE session_id = '$guest_session_id'
        ");
        while ($notif = mysqli_fetch_assoc($notifs)) {
            $type    = mysqli_real_escape_string($conn, $notif['notif_type']);
            $msg     = mysqli_real_escape_string($conn, $notif['notif_message']);
            $ref     = $notif['reference_id'] ? (int)$notif['reference_id'] : 'NULL';
            $is_read = (int)$notif['is_read'];
            $created = mysqli_real_escape_string($conn, $notif['created_at']);
            mysqli_query($conn, "
                INSERT INTO notifications (user_id, notif_type, notif_message, reference_id, is_read, created_at)
                VALUES ('$user_id', '$type', '$msg', $ref, '$is_read', '$created')
            ");
        }
        mysqli_query($conn, "DELETE FROM guest_notifications WHERE session_id = '$guest_session_id'");

        unset($_SESSION['guest_id']);
    }
}

// Set logged-in session
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['user_fname'] = $user['fname'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['signup_success'] = true;

echo json_encode(['success' => true]);
exit;