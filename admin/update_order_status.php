<?php

session_start();
require '../config/database.php';

if (
    !isset($_SESSION['admin_verified'])
) {
    header(
        "Location: admin_pin.php"
    );
    exit;
}

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    include '../includes/access_denied.php';
    exit;
}

$id = (int) $_GET['id'];
$status = $_GET['status'];


if ($status == 'process') {

    $items = mysqli_query(
        $conn,
        "SELECT *
    FROM order_items
    WHERE order_id='$id'"
    );

    while ($item = mysqli_fetch_assoc($items)) {

        mysqli_query(
            $conn,
            "UPDATE products
        SET stock = stock - {$item['qty']}
        WHERE id='{$item['product_id']}'"
        );

    }
}

mysqli_query(
    $conn,
    "UPDATE orders
    SET status='$status'
    WHERE id='$id'"
);

header("Location: admin_orders.php");
exit;