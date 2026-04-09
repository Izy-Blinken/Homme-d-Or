<?php
session_start();
include __DIR__ . '/../db_connect.php';

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    $_SESSION['error'] = 'Unauthorized.';
    header('Location: ../../pages/Admin Pages/admin_about.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/admin_about.php');
    exit;
}

$section_key = trim($_POST['section_key'] ?? '');
$valid_keys = ['hero', 'story', 'values', 'team', 'milestones'];

if (!in_array($section_key, $valid_keys)) {
    $_SESSION['error'] = 'Invalid section.';
    header('Location: ../../pages/Admin Pages/admin_about.php');
    exit;
}

$title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
$body = mysqli_real_escape_string($conn, trim($_POST['body']  ?? ''));

// Ensure upload directory exists
$upload_dir = __DIR__ . '/../../assets/images/about/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle image upload for hero and story
$image_url = null;
if (in_array($section_key, ['hero', 'story']) && !empty($_FILES['image']['name'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image_url = $filename;
        }
    }
}

// Handle JSON repeater fields
$extra_data = null;
if ($section_key === 'values') {
    $icons = $_POST['icon'] ?? [];
    $titles = $_POST['vtitle'] ?? [];
    $descs = $_POST['desc'] ?? [];
    $items = [];
    foreach ($icons as $i => $icon) {
        if (empty($icon) && empty($titles[$i])) continue;
        $items[] = [
            'icon' => trim($icon),
            'title' => trim($titles[$i] ?? ''),
            'desc' => trim($descs[$i]  ?? '')
        ];
    }
    $extra_data = json_encode($items);

} elseif ($section_key === 'team') {
    $names = $_POST['tname'] ?? [];
    $roles = $_POST['role']  ?? [];
    $photos = $_POST['existing_photo'] ?? [];
    $items = [];
    foreach ($names as $i => $name) {
        if (empty($name)) continue;
        $photo = $photos[$i] ?? '';

        // Handle new photo upload per team member
        if (
            isset($_FILES['photo']['name'][$i]) &&
            !empty($_FILES['photo']['name'][$i]) &&
            $_FILES['photo']['error'][$i] === UPLOAD_ERR_OK
        ) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'][$i], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $filename = time() . '_' . $i . '_' . basename($_FILES['photo']['name'][$i]);
                if (move_uploaded_file($_FILES['photo']['tmp_name'][$i], $upload_dir . $filename)) {
                    $photo = $filename;
                }
            }
        }

        $items[] = [
            'name' => trim($name),
            'role' => trim($roles[$i] ?? ''),
            'photo' => $photo
        ];
    }
    $extra_data = json_encode($items);

} elseif ($section_key === 'milestones') {
    $years = $_POST['year']  ?? [];
    $events = $_POST['event'] ?? [];
    $items = [];
    foreach ($years as $i => $year) {
        if (empty($year) && empty($events[$i])) continue;
        $items[] = [
            'year'  => trim($year),
            'event' => trim($events[$i] ?? '')
        ];
    }
    $extra_data = json_encode($items);
}

$safe_extra = $extra_data ? mysqli_real_escape_string($conn, $extra_data) : null;
$safe_key = mysqli_real_escape_string($conn, $section_key);

// Build clauses
$img_clause = $image_url  ? mysqli_real_escape_string($conn, $image_url)  : '';
$extra_clause = $safe_extra ?? '';

$query = "INSERT INTO about_us_sections (section_key, title, body"
       . ($img_clause ? ", image_url"  : '')
       . ($extra_clause ? ", extra_data" : '')
       . ") VALUES ('$safe_key', '$title', '$body'"
       . ($img_clause ? ", '$img_clause'" : '')
       . ($extra_clause ? ", '$extra_clause'" : '')
       . ") ON DUPLICATE KEY UPDATE"
       . "  title = '$title',"
       . "  body = '$body'"
       . ($img_clause ? ", image_url  = '$img_clause'" : '')
       . ($extra_clause ? ", extra_data = '$extra_clause'" : '');

mysqli_query($conn, $query);

$_SESSION['success'] = ucfirst($section_key) . ' section updated.';
header('Location: ../../pages/Admin Pages/admin_about.php');
exit;