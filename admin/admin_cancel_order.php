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

$order_id = (int) $_GET['id'];

$order = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT *
        FROM orders
        WHERE id='$order_id'"
    )
);

if (!$order) {
    die('Pesanan tidak ditemukan');
}

$items = mysqli_query(
    $conn,
    "SELECT *
    FROM order_items
    WHERE order_id='$order_id'"
);

while ($item = mysqli_fetch_assoc($items)) {

    mysqli_query(
        $conn,
        "UPDATE products
        SET stock = stock + {$item['qty']}
        WHERE id = '{$item['product_id']}'
        LIMIT 1"
    );
}

mysqli_query(
    $conn,
    "UPDATE orders
    SET status='cancelled'
    WHERE id='$order_id'
    LIMIT 1"
);

header("Location: admin_orders.php");
exit;