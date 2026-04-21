<?php
/**
 * search_customers.php
 * Returns non-admin users matching the search query as JSON.
 * Used by adminManagement.js for the live customer search inside the Assign Admin modal.
 *
 * GET param: q — search term (name or email)
 */

session_start();
include '../../backend/db_connect.php';
include '../../backend/auth/auth_check.php';

// Only allow admins with can_assign_admins or superadmins
$isSuperadmin = !empty($_SESSION['superadmin_id']);
$isAdmin      = !empty($_SESSION['admin_id']);

if (!$isSuperadmin && !$isAdmin) {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$like = '%' . $conn->real_escape_string($q) . '%';

// Fetch users who are NOT already admins
$sql = "SELECT u.user_id, u.fname, u.lname, u.email
        FROM users u
        WHERE u.user_id NOT IN (SELECT user_id FROM admins)
          AND (u.fname LIKE ? OR u.lname LIKE ? OR u.email LIKE ?)
        ORDER BY u.fname ASC
        LIMIT 15";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = [
        'user_id' => $row['user_id'],
        'fname'   => $row['fname'],
        'lname'   => $row['lname'],
        'email'   => $row['email'],
    ];
}

echo json_encode($customers);