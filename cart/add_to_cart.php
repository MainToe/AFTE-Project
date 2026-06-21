<?php

session_start();

require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int) $_GET['id'];

$check = mysqli_query(
    $conn,
    "SELECT * FROM cart
    WHERE user_id='$user_id'
    AND product_id='$product_id'"
);

if (mysqli_num_rows($check) > 0) {

    mysqli_query(
        $conn,
        "UPDATE cart
        SET quantity = quantity + 1
        WHERE user_id='$user_id'
        AND product_id='$product_id'"
    );

} else {

    mysqli_query(
        $conn,
        "INSERT INTO cart
        (
            user_id,
            product_id,
            quantity
        )
        VALUES
        (
            '$user_id',
            '$product_id',
            1
        )"
    );

}

$_SESSION['success'] =
"Produk berhasil ditambahkan ke keranjang";

header("Location: ../index.php");
exit;