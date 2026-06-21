<?php

session_start();
require '../config/database.php';

if (!isset($_SESSION['admin_verified'])) {
    header("Location: admin_pin.php");
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    include '../includes/access_denied.php';
    exit;
}

$id = (int) $_POST['id'];
$name = mysqli_real_escape_string($conn, $_POST['name']);
$price = (int) $_POST['price'];
$stock = (int) $_POST['stock'];
$description = mysqli_real_escape_string($conn, $_POST['description']);

/* OPTIONAL IMAGE */
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        header("Location: edit_product.php?id=$id&module=product&action=update&error=product_type");
        exit;
    }

    if ($_FILES['image']['size'] > 1024 * 1024) {
        header("Location: edit_product.php?id=$id&module=product&action=update&error=product_size");
        exit;
    }

    $image = time() . '_' . basename($_FILES['image']['name']);

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        '../uploads/product/' . $image
    );

    mysqli_query($conn, "
        UPDATE products SET
        name='$name',
        price='$price',
        stock='$stock',
        description='$description',
        image='$image'
        WHERE id='$id'
    ");

} else {

    mysqli_query($conn, "
        UPDATE products SET
        name='$name',
        price='$price',
        stock='$stock',
        description='$description'
        WHERE id='$id'
    ");
}

header("Location: products.php?module=product&action=update&status=success");
exit;