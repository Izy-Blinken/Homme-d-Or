<?php
session_start();
include __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$post_id = intval($_POST['post_id'] ?? 0);
$field   = $_POST['field'] ?? '';

if (!$post_id || !in_array($field, ['is_published', 'is_featured'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

mysqli_query($conn, "UPDATE blog_posts SET $field = NOT $field WHERE post_id = '$post_id'");
$updated = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT $field FROM blog_posts WHERE post_id = '$post_id'"));

echo json_encode(['success' => true, 'value' => (int) $updated[$field]]);
exit;