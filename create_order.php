<?php

session_start();
require 'config/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$selected =
$_POST['selected'] ?? [];

$payment_method =
$_POST['payment_method'] ?? '';

if(
    empty($selected) ||
    empty($payment_method)
){
    header("Location: cart.php");
    exit;
}

$payment_method =
mysqli_real_escape_string(
    $conn,
    $payment_method
);

$ids = implode(
    ',',
    array_map(
        'intval',
        $selected
    )
);

$cart = mysqli_query(
    $conn,
    "SELECT
        cart.*,
        products.price,
        products.stock,
        products.name
    FROM cart
    JOIN products
        ON cart.product_id = products.id
    WHERE cart.id IN ($ids)"
);

if(mysqli_num_rows($cart) < 1){
    header("Location: cart.php");
    exit;
}

$total = 0;
$cart_items = [];

while($item = mysqli_fetch_assoc($cart)){

    if($item['stock'] < $item['quantity']){

        die(
            "Stok produk ".
            htmlspecialchars($item['name']).
            " tidak mencukupi"
        );
    }

    $subtotal =
        $item['price'] *
        $item['quantity'];

    $total += $subtotal;

    $cart_items[] = $item;
}

if($total <= 0){
    die("Total pesanan tidak valid");
}

$order_code =
"AF" .
date("ym") .
rand(1000,9999);

mysqli_query(
    $conn,
    "INSERT INTO orders(
        user_id,
        order_code,
        total_price,
        payment_method,
        status
    )
    VALUES(
        '$user_id',
        '$order_code',
        '$total',
        '$payment_method',
        'pending'
    )"
);

$order_id = mysqli_insert_id($conn);

foreach($cart_items as $item){

    mysqli_query(
        $conn,
        "INSERT INTO order_items(
            order_id,
            product_id,
            qty,
            price
        )
        VALUES(
            '$order_id',
            '{$item['product_id']}',
            '{$item['quantity']}',
            '{$item['price']}'
        )"
    );
}

mysqli_query(
    $conn,
    "DELETE FROM cart
    WHERE id IN ($ids)"
);

header(
    "Location: payment.php?id=".$order_id
);
exit;