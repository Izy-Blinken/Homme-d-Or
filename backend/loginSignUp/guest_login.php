<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Connect to the database so we can register the guest
include_once '../db_connect.php';

// 2. Generate the unique ID
$new_guest_id = uniqid('guest_', true);

// 3. Formally insert the guest into the database's `guests` table
// (This satisfies the foreign key constraint!)
$stmt = $conn->prepare("INSERT INTO guests (guest_id) VALUES (?)");
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