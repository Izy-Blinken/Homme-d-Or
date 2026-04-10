<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    $_SESSION['error'] = 'Unauthorized.';
    header('Location: ../../pages/Admin Pages/admin_blog.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/admin_blog.php');
    exit;
}

$post_id = intval($_POST['post_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$category_id = intval($_POST['category_id'] ?? 0);
$excerpt = trim($_POST['excerpt'] ?? '');
$is_featured = isset($_POST['is_featured']) ? 1 : 0;
$is_published = isset($_POST['is_published']) ? 1 : 0;
$intro = trim($_POST['intro'] ?? '');
$sec1_heading = trim($_POST['sec1_heading'] ?? '');
$sec1_body = trim($_POST['sec1_body'] ?? '');
$sec2_heading = trim($_POST['sec2_heading'] ?? '');
$sec2_body = trim($_POST['sec2_body'] ?? '');
$quote = trim($_POST['quote'] ?? '');
$closing = trim($_POST['closing'] ?? '');

if (empty($title)) {
    $_SESSION['error'] = 'Title is required.';
    header('Location: ../../pages/Admin Pages/admin_blog_form.php' . ($post_id ? "?post_id=$post_id" : ''));
    exit;
}

$body = '';
if ($intro) $body .= '<p>' . nl2br(htmlspecialchars($intro)) . '</p>';
if ($sec1_heading) $body .= '<h2>' . htmlspecialchars($sec1_heading) . '</h2>';
if ($sec1_body) $body .= '<p>' . nl2br(htmlspecialchars($sec1_body)) . '</p>';
if ($sec2_heading) $body .= '<h2>' . htmlspecialchars($sec2_heading) . '</h2>';
if ($sec2_body) $body .= '<p>' . nl2br(htmlspecialchars($sec2_body)) . '</p>';
if ($quote) $body .= '<blockquote>' . htmlspecialchars($quote) . '</blockquote>';
if ($closing) $body .= '<p>' . nl2br(htmlspecialchars($closing)) . '</p>';

$base_slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
$slug = $base_slug;
$counter = 1;
while (true) {
    $safe_slug = mysqli_real_escape_string($conn, $slug);
    $slug_check = mysqli_query($conn,
        "SELECT post_id FROM blog_posts WHERE slug = '$safe_slug'" . ($post_id ? " AND post_id != '$post_id'" : ''));
    if (mysqli_num_rows($slug_check) === 0) break;
    $slug = $base_slug . '-' . $counter++;
}

$cover_image = null;
$blog_upload_dir = __DIR__ . '/../../assets/images/blog/';
if (!is_dir($blog_upload_dir)) {
    mkdir($blog_upload_dir, 0755, true);
}

if (!empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = 'Invalid image format. Use jpg, jpeg, png, or webp.';
        header('Location: ../../pages/Admin Pages/admin_blog_form.php' . ($post_id ? "?post_id=$post_id" : ''));
        exit;
    }
    $filename = time() . '_' . basename($_FILES['cover_image']['name']);
    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $blog_upload_dir . $filename)) {
        $cover_image = $filename;
    }
}

$safe_title = mysqli_real_escape_string($conn, $title);
$safe_slug = mysqli_real_escape_string($conn, $slug);
$safe_excerpt = mysqli_real_escape_string($conn, $excerpt);
$safe_body = mysqli_real_escape_string($conn, $body);
$safe_intro = mysqli_real_escape_string($conn, $intro);
$safe_sec1_heading = mysqli_real_escape_string($conn, $sec1_heading);
$safe_sec1_body = mysqli_real_escape_string($conn, $sec1_body);
$safe_sec2_heading = mysqli_real_escape_string($conn, $sec2_heading);
$safe_sec2_body = mysqli_real_escape_string($conn, $sec2_body);
$safe_quote = mysqli_real_escape_string($conn, $quote);
$safe_closing = mysqli_real_escape_string($conn, $closing);
$cat_val = $category_id ? "'$category_id'" : 'NULL';

if ($post_id) {
    $img_clause = $cover_image ? ", cover_image = '" . mysqli_real_escape_string($conn, $cover_image) . "'" : '';
    mysqli_query($conn, "UPDATE blog_posts SET
        title = '$safe_title',
        slug = '$safe_slug',
        category_id = $cat_val,
        excerpt = '$safe_excerpt',
        body = '$safe_body',
        intro = '$safe_intro',
        sec1_heading = '$safe_sec1_heading',
        sec1_body = '$safe_sec1_body',
        sec2_heading = '$safe_sec2_heading',
        sec2_body = '$safe_sec2_body',
        quote = '$safe_quote',
        closing = '$safe_closing',
        is_featured = '$is_featured',
        is_published = '$is_published'
        $img_clause
        WHERE post_id = '$post_id'");
    $_SESSION['success'] = 'Post updated successfully.';
} else {
    $img_val = $cover_image ? "'" . mysqli_real_escape_string($conn, $cover_image) . "'" : 'NULL';
    mysqli_query($conn, "INSERT INTO blog_posts
        (title, slug, category_id, excerpt, body, intro, sec1_heading, sec1_body,
        sec2_heading, sec2_body, quote, closing, cover_image, is_featured, is_published)
        VALUES
        ('$safe_title', '$safe_slug', $cat_val, '$safe_excerpt', '$safe_body',
        '$safe_intro', '$safe_sec1_heading', '$safe_sec1_body',
        '$safe_sec2_heading', '$safe_sec2_body', '$safe_quote', '$safe_closing',
        $img_val, '$is_featured', '$is_published')");
    $_SESSION['success'] = 'Post created successfully.';
}

header('Location: ../../pages/Admin Pages/admin_blog.php');
exit;