<?php
session_start();
include '../db_connect.php';
include '../auth/auth_check.php';
checkAdminAccess($conn);

if (empty($_SESSION['superadmin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

header('Content-Type: application/json');

$superadmin_id = (int) $_SESSION['superadmin_id'];
$errors = [];

// store settings fields 
$brand_name = trim($_POST['brand_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$store_address = trim($_POST['store_address'] ?? '');
$website = trim($_POST['website'] ?? '');
$established_year = trim($_POST['established_year'] ?? '');
$facebook  = trim($_POST['facebook'] ?? '');
$instagram = trim($_POST['instagram'] ?? '');
$twitter = trim($_POST['twitter'] ?? '');
$youtube = trim($_POST['youtube'] ?? '');

// pw fields
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password']     ?? '';

// input validation
if (empty($brand_name)) {
    $errors[] = 'Brand name is required.';
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}

if (!empty($established_year) && (!ctype_digit($established_year) || strlen($established_year) !== 4)) {
    $errors[] = 'Established year must be a valid 4-digit year.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// logo upload 
$logo_filename = null; // null means no change

if (!empty($_FILES['logo']['name'])) {

    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $file_type = mime_content_type($_FILES['logo']['tmp_name']);
    $file_size = $_FILES['logo']['size'];
    $file_ext  = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Logo must be a JPG, PNG, WEBP, or GIF image.']);
        exit;
    }
    if ($file_size > $max_size) {
        echo json_encode(['success' => false, 'message' => 'Logo must be 2MB or smaller.']);
        exit;
    }

    $upload_dir = __DIR__ . '/../../assets/images/store_images/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $logo_filename = time() . '_logo.' . $file_ext;
    $destination = $upload_dir . $logo_filename;

    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
        echo json_encode(['success' => false, 'message' => 'Failed to save logo. Check folder permissions.']);
        exit;
    }

    // Delete old logo file if it exists
    $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT logo FROM store_settings WHERE id = 1"));
    
    if ($old && !empty($old['logo'])) {
        $old_path = $upload_dir . $old['logo'];
        if (file_exists($old_path)) {
            unlink($old_path);
        }
    }
}

// Update store_settings
if ($logo_filename !== null) {
    $stmt = mysqli_prepare($conn,
        "UPDATE store_settings SET
            brand_name = ?, email = ?, phone = ?, store_address = ?,
            website = ?, established_year = ?, facebook = ?, instagram = ?,
            twitter = ?, youtube = ?, logo = ?
         WHERE id = 1"
    );

    mysqli_stmt_bind_param($stmt, 'sssssssssss',
        $brand_name, $email, $phone, $store_address,
        $website, $established_year, $facebook, $instagram,
        $twitter, $youtube, $logo_filename
    );

} else {

    $stmt = mysqli_prepare($conn,
        "UPDATE store_settings SET
            brand_name = ?, email = ?, phone = ?, store_address = ?,
            website = ?, established_year = ?, facebook = ?, instagram = ?,
            twitter = ?, youtube = ?
         WHERE id = 1"
    );
    mysqli_stmt_bind_param($stmt, 'ssssssssss',
        $brand_name, $email, $phone, $store_address,
        $website, $established_year, $facebook, $instagram,
        $twitter, $youtube
    );
}

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => false, 'message' => 'Failed to update store settings.']);
    exit;
}
mysqli_stmt_close($stmt);

// password change
if (!empty($new_password)) {
    if (empty($current_password)) {
        echo json_encode(['success' => false, 'message' => 'Current password is required to set a new password.']);
        exit;
    }
    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
        exit;
    }

    // Fetch current hashed password
    $row = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT sadmin_password FROM superadmins WHERE superadmin_id = $superadmin_id"
    ));

    if (!$row || !password_verify($current_password, $row['sadmin_password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt2 = mysqli_prepare($conn,
        "UPDATE superadmins SET sadmin_password = ? WHERE superadmin_id = ?"
    );

    mysqli_stmt_bind_param($stmt2, 'si', $new_hash, $superadmin_id);

    if (!mysqli_stmt_execute($stmt2)) {
        echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
        exit;
    }
    mysqli_stmt_close($stmt2);
}

echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);