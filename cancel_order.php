<?php

session_start();
require 'config/database.php';

if(!isset($_SESSION['user_id'])){
    exit;
}

$id = (int)$_GET['id'];

mysqli_query(
    $conn,
    "UPDATE orders
    SET status='cancelled'
    WHERE id='$id'
    AND user_id='{$_SESSION['user_id']}'"
);

header("Location: orders.php");
exit;