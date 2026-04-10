<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$safe_email = mysqli_real_escape_string($conn, $email);

$existing = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT id FROM newsletter_subscribers WHERE email = '$safe_email' LIMIT 1"));

if ($existing) {
    echo json_encode(['success' => false, 'message' => 'You are already subscribed.']);
    exit;
}

mysqli_query($conn, "INSERT INTO newsletter_subscribers (email) VALUES ('$safe_email')");

if (mysqli_affected_rows($conn) > 0) {
    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again.']);
}
exit;