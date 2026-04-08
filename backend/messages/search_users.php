<?php
session_start();
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../auth/auth_check.php';

header('Content-Type: application/json');

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$current_superadmin_id = $_SESSION['superadmin_id'] ?? null;
$current_admin_id = $_SESSION['admin_id'] ?? null;

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 1) {
    echo json_encode([]);
    exit;
}

$safe_q = mysqli_real_escape_string($conn, $q);
$results = [];

// Always search customers (users)
$users = mysqli_query($conn, "SELECT user_id, fname, lname FROM users
     WHERE (fname LIKE '%$safe_q%' OR lname LIKE '%$safe_q%' OR CONCAT(fname, ' ', lname) LIKE '%$safe_q%')
     ORDER BY fname ASC
     LIMIT 10");

while ($row = mysqli_fetch_assoc($users)) {
    $results[] = [
        'type' => 'user',
        'id' => $row['user_id'],
        'name' => $row['fname'] . ' ' . $row['lname'],
    ];
}

// Superadmin can also search admins
if ($isSuperadmin) {
    $admins = mysqli_query($conn, "SELECT a.admin_id, u.fname, u.lname FROM admins a
         JOIN users u ON a.user_id = u.user_id
         WHERE (u.fname LIKE '%$safe_q%' OR u.lname LIKE '%$safe_q%' OR CONCAT(u.fname, ' ', u.lname) LIKE '%$safe_q%')
         ORDER BY u.fname ASC
         LIMIT 10");

    while ($row = mysqli_fetch_assoc($admins)) {
        $results[] = [
            'type' => 'admin',
            'id' => $row['admin_id'],
            'name' => $row['fname'] . ' ' . $row['lname'],
        ];
    }
}

echo json_encode($results);