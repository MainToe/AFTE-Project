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

$id = (int)$_GET['id'];

$data = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT image
        FROM banners
        WHERE id='$id'"
    )

);

if(
    !empty($data['image']) &&
    file_exists(
        '../uploads/banner/'.$data['image']
    )
){
    unlink(
        '../uploads/banner/'.$data['image']
    );
}

header("Location: banners.php");
exit;