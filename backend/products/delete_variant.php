<?php
session_start();
include '../db_connect.php';

if (isset($_GET['id'])) {
    $variant_id = $_GET['id'];

    if (mysqli_query($conn, "DELETE FROM product_variants WHERE variant_id = '$variant_id'")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>