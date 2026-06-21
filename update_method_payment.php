<?php

session_start();
require 'config/database.php';

$order_id = (int)$_POST['order_id'];

$payment_method =
mysqli_real_escape_string(
$conn,
$_POST['payment_method']
);

mysqli_query(
$conn,
"UPDATE orders
SET payment_method='$payment_method'
WHERE id='$order_id'
AND status='pending'"
);

header(
"Location: payment.php?id=".$order_id
);
exit;