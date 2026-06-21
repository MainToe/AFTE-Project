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

$name = mysqli_real_escape_string($conn, $_POST['name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$price = (int) $_POST['price'];

if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
    header("Location: add_product.php?module=product&action=upload&error=image_required");
    exit;
}

$image = $_FILES['image']['name'];

$allowedExt = ['jpg','jpeg','png','webp'];
$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    header("Location: add_product.php?module=product&action=upload&error=product_type");
    exit;
}

if ($_FILES['image']['size'] > 1024 * 1024) {
    header("Location: add_product.php?module=product&action=upload&error=product_size");
    exit;
}

move_uploaded_file(
    $_FILES['image']['tmp_name'],
    "../uploads/product/" . $image
);

mysqli_query($conn, "
    INSERT INTO products (name, description, price, image)
    VALUES ('$name', '$description', '$price', '$image')
");

/* SUCCESS */
header("Location: dashboard.php?module=product&action=upload&status=success");
exit;