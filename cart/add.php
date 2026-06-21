<?php

session_start();
require '../config/database.php';

$id = (int)$_GET['id'];

mysqli_query(
    $conn,
    "UPDATE cart
    SET quantity = quantity + 1
    WHERE id='$id'"
);

header("Location: ../cart.php");
exit;