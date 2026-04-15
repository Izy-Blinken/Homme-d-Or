<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../db_connect.php';

$new_guest_id = uniqid('guest_', true);

$stmt = $conn->prepare("INSERT INTO guests (session_id) VALUES (?)");
$stmt->bind_param("s", $new_guest_id);
$stmt->execute();

// 4. Save to session
$_SESSION['guest_id'] = $new_guest_id;
$_SESSION['user_fname'] = 'Guest';

// Figure out what page they were on
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../pages/index.php';

// Attach our secret "guest_activated" signal to the URL
if (strpos($redirect, '?') !== false) {
    $redirect .= '&guest_activated=true';
} else {
    $redirect .= '?guest_activated=true';
}

header("Location: " . $redirect);
exit;
?>