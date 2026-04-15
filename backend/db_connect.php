<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "homme_dor_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure session is active before accessing $_SESSION for remember-me logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (session_status() === PHP_SESSION_ACTIVE && empty($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])) {
    $safe_token = mysqli_real_escape_string($conn, $_COOKIE['remember_token']);
    $remembered = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM users WHERE remember_token = '$safe_token' AND is_blocked = 0 LIMIT 1"
    ));

    if ($remembered) {
        $_SESSION['user_id'] = $remembered['user_id'];
        $_SESSION['user_fname'] = $remembered['fname'];
        $_SESSION['user_email'] = $remembered['email'];
        $_SESSION['user_username'] = $remembered['username'];

        // Rotate token for security ; old token is now invalid
        $new_token = bin2hex(random_bytes(32));
        $safe_new = mysqli_real_escape_string($conn, $new_token);
        mysqli_query($conn, "UPDATE users SET remember_token = '$safe_new' WHERE user_id = '{$remembered['user_id']}'");
        setcookie('remember_token', $new_token, time() + (30 * 24 * 60 * 60), '/');
    } else {
        // Token invalid or user blocked — clear the cookie
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// Add this to the very bottom of db_connect.php
// ── CSRF TOKEN HELPERS ──────────────────────────────────────────────
if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], (string)$token);
    }
    function requireCsrf() {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!validateCsrfToken($token)) {
            http_response_code(403);
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid CSRF token.']);
            } else {
                echo 'CSRF validation failed.';
            }
            exit;
        }
    }
}

if (!function_exists('getCurrentUserId')) {
    function getCurrentUserId() {
        // 1. Registered User
        if (!empty($_SESSION['user_id'])) {
            return ['type' => 'user_id', 'id' => $_SESSION['user_id']];
        }
        // 2. Explicit Guest (from clicking the button)
        if (!empty($_SESSION['guest_id'])) {
            return ['type' => 'guest_id', 'id' => $_SESSION['guest_id']];
        }
        // 3. Complete Stranger (Just looking around)
        return ['type' => 'stranger', 'id' => null];
    }
}

?>

