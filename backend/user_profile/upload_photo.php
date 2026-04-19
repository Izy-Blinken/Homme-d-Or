<?php
session_start();
require '../db_connect.php';


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_FILES['photo'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    exit;
}

$file = $_FILES['photo'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Upload error. Please try again.']);
    exit;
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, GIF, and WEBP files are allowed.']);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File is too large. Max size is 5MB.']);
    exit;
}

// Path relative to this file (backend/user_profile/) going up to homme_dor root then into assets
$upload_dir = __DIR__ . '/../../assets/images/profile_photos/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$new_filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
$destination = $upload_dir . $new_filename;

error_log("UPLOAD DEBUG — destination=$destination | tmp=" . $file['tmp_name'] . " | user_id=$user_id");
if (move_uploaded_file($file['tmp_name'], $destination)) {

    // Delete old photo if it exists
    $get_old = $conn->prepare("SELECT profile_photo FROM users WHERE user_id = ?");
    $get_old->bind_param("i", $user_id);
    $get_old->execute();
    $old = $get_old->get_result()->fetch_assoc();

    if ($old['profile_photo'] && file_exists($upload_dir . $old['profile_photo'])) {
        unlink($upload_dir . $old['profile_photo']);
    }

    $update = $conn->prepare("UPDATE users SET profile_photo = ? WHERE user_id = ?");
    $update->bind_param("si", $new_filename, $user_id);
    if ($update->execute()) {
        error_log("UPLOAD DB SUCCESS — filename=$new_filename | user_id=$user_id");
        echo json_encode(['success' => true, 'filename' => $new_filename]);
    } else {
        unlink($destination); // delete the uploaded file if DB update fails
        echo json_encode(['success' => false, 'message' => 'Failed to update database.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save the photo.']);
}
?>