<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if (empty($_SESSION['superadmin_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
if (!$email) {
    echo json_encode(['success' => false]);
    exit;
}

mysqli_query($conn, "UPDATE newsletter_subscribers SET is_active = 0 WHERE email = '$email'");
echo json_encode(['success' => true]);
exit;