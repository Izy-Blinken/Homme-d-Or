<?php
session_start();
include __DIR__ . '/../db_connect.php';

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/Admin Pages/admin_blog.php');
    exit;
}

$post_id = intval($_POST['post_id'] ?? 0);

if (!$post_id) {
    $_SESSION['error'] = 'Invalid post.';
    header('Location: ../../pages/Admin Pages/admin_blog.php');
    exit;
}

mysqli_query($conn, "DELETE FROM blog_posts WHERE post_id = '$post_id'");
$_SESSION['success'] = 'Post deleted.';
header('Location: ../../pages/Admin Pages/admin_blog.php');
exit;