<?php

session_start();
require '../config/database.php';

$id = (int)$_GET['id'];

$item = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT quantity
        FROM cart
        WHERE id='$id'"
    )
);

if($item['quantity'] <= 1){

    mysqli_query(
        $conn,
        "DELETE FROM cart
        WHERE id='$id'"
    );

}else{

    mysqli_query(
        $conn,
        "UPDATE cart
        SET quantity = quantity - 1
        WHERE id='$id'"
    );
}

header("Location: ../cart.php");
exit;