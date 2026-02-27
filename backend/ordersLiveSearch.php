<?php
include __DIR__ . '/db_connect.php';

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

$safe  = mysqli_real_escape_string($conn, $query);
$result = mysqli_query($conn,
    "SELECT order_id, fname, lname, order_status
     FROM orders
     WHERE (fname LIKE '%$safe%' OR lname LIKE '%$safe%' OR order_id LIKE '%$safe%')
     ORDER BY fname ASC
     LIMIT 10");

$suggestions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $suggestions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($suggestions);
?>