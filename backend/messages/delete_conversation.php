<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

$isSuperadmin = !empty($_SESSION['superadmin_id']);

if (!$isSuperadmin) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit;
}

$convo_type = $_POST['convo_type'] ?? '';
$convo_ref_id = intval($_POST['convo_ref_id'] ?? 0);


if (!$convo_ref_id || !in_array($convo_type, ['user', 'admin', 'superadmin', 'escalated'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

$current_superadmin_id = $_SESSION['superadmin_id'];

if ($convo_type === 'escalated') {

    $cs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM chatbot_sessions WHERE session_id = '$convo_ref_id'"));
    $cs_user_id = $cs['user_id'] ?? null;

    mysqli_query($conn,
        "DELETE FROM admin_messages WHERE session_id = '$convo_ref_id'");

    if ($cs_user_id) {

        mysqli_query($conn, "DELETE FROM admin_messages WHERE session_id IS NULL
               AND (sender_id = '$cs_user_id' OR receiver_id = '$cs_user_id')");
    }

    mysqli_query($conn, "DELETE FROM conversation_archives
         WHERE convo_type = 'escalated' AND convo_ref_id = '$convo_ref_id'");

    mysqli_query($conn, "DELETE FROM chatbot_logs WHERE session_id = '$convo_ref_id'");

    mysqli_query($conn, "DELETE FROM chatbot_sessions WHERE session_id = '$convo_ref_id'");

} elseif ($convo_type === 'user') {

    mysqli_query($conn, "DELETE FROM admin_messages
         WHERE (sender_type = 'user' AND sender_id = '$convo_ref_id')
            OR (receiver_type = 'user' AND receiver_id = '$convo_ref_id')");

    mysqli_query($conn, "DELETE FROM conversation_archives
         WHERE convo_type = 'user' AND convo_ref_id = '$convo_ref_id'");

} elseif ($convo_type === 'admin') {

    mysqli_query($conn, "DELETE FROM admin_messages
         WHERE (sender_type = 'admin' AND sender_id = '$convo_ref_id' AND receiver_type = 'superadmin' AND receiver_id = '$current_superadmin_id')
            OR (receiver_type = 'admin' AND receiver_id = '$convo_ref_id' AND sender_type = 'superadmin' AND sender_id = '$current_superadmin_id')");

    mysqli_query($conn, "DELETE FROM conversation_archives
         WHERE convo_type = 'admin' AND convo_ref_id = '$convo_ref_id'");

} elseif ($convo_type === 'superadmin') {

    mysqli_query($conn, "DELETE FROM admin_messages
         WHERE (sender_type = 'superadmin' AND sender_id = '$current_superadmin_id' AND receiver_type = 'admin' AND receiver_id = '$convo_ref_id')
            OR (receiver_type = 'superadmin' AND receiver_id = '$current_superadmin_id' AND sender_type = 'admin' AND sender_id = '$convo_ref_id')");

    mysqli_query($conn, "DELETE FROM conversation_archives
         WHERE convo_type = 'superadmin' AND convo_ref_id = '$convo_ref_id'");
}

echo json_encode(['success' => true]);
