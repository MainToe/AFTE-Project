<?php

session_start();
require 'config/database.php';

$order_id = (int)$_POST['order_id'];
$payment_method = $_POST['payment_method'];

mysqli_query(
    $conn,
    "UPDATE orders
    SET payment_method='$payment_method'
    WHERE id='$order_id'
    AND status='pending'"
);

header(
    "Location: order_detail.php?id=".$order_id."&success=payment_changed"
);
exit;