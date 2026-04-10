<?php
function insertNotif($conn, $user_id, $type, $message, $reference_id = null) {
    $user_id = intval($user_id);
    $type = mysqli_real_escape_string($conn, $type);
    $message = mysqli_real_escape_string($conn, $message);
    $ref = $reference_id ? intval($reference_id) : 'NULL';

    mysqli_query($conn, "
        INSERT INTO notifications (user_id, notif_type, notif_message, reference_id)
        VALUES ('$user_id', '$type', '$message', $ref)
    ");
}