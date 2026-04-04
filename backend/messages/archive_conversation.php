<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$current_admin_id = $_SESSION['admin_id'] ?? null;
$current_superadmin_id = $_SESSION['superadmin_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit;
}

$convo_type = $_POST['convo_type'] ?? '';
$convo_ref_id = intval($_POST['convo_ref_id'] ?? 0);
$action = $_POST['action'] ?? 'archive'; // archive or unarchive

if (!$convo_ref_id || !in_array($convo_type, ['user', 'admin', 'superadmin', 'escalated'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

$archived_by_id = $isSuperadmin ? $current_superadmin_id : $current_admin_id;
$archived_by_type = $isSuperadmin ? 'superadmin' : 'admin';

if ($action === 'unarchive') {

    mysqli_query($conn, "DELETE FROM conversation_archives WHERE archived_by_id = '$archived_by_id'
           AND archived_by_type = '$archived_by_type'
           AND convo_type = '$convo_type'
           AND convo_ref_id = '$convo_ref_id'");

    echo json_encode(['success' => true]);
    exit;
}

// check if already archived
$existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT archive_id FROM conversation_archives
     WHERE archived_by_id = '$archived_by_id'
       AND archived_by_type = '$archived_by_type'
       AND convo_type = '$convo_type'
       AND convo_ref_id = '$convo_ref_id'"));

if ($existing) {
    echo json_encode(['success' => true]);
    exit;
}

mysqli_query($conn, "INSERT INTO conversation_archives (archived_by_id, archived_by_type, convo_type, convo_ref_id)
     VALUES ('$archived_by_id', '$archived_by_type', '$convo_type', '$convo_ref_id')");

if (mysqli_affected_rows($conn) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to archive.']);
}