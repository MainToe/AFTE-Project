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

$title = mysqli_real_escape_string($conn, $_POST['title']);
$device = $_POST['device'];
$banner_type = $_POST['banner_type'];
$target_link = mysqli_real_escape_string($conn, $_POST['target_link']);
$status = $_POST['status'];

/* =========================
   VALIDATION FILE
========================= */
if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
    header("Location: add_banner.php?module=banner&action=upload&error=image_required");
    exit;
}

$image = time() . '_' . basename($_FILES['image']['name']);

$allowedExt = ['jpg','jpeg','png','webp'];
$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    header("Location: add_banner.php?module=banner&action=upload&error=image_type");
    exit;
}

if ($_FILES['image']['size'] > 1024 * 1024) {
    header("Location: add_banner.php?module=banner&action=upload&error=image_size");
    exit;
}

/* UPLOAD */
move_uploaded_file(
    $_FILES['image']['tmp_name'],
    '../uploads/banner/' . $image
);

/* INSERT */
mysqli_query($conn, "
    INSERT INTO banners (
        title,
        image,
        device,
        banner_type,
        target_link,
        status
    ) VALUES (
        '$title',
        '$image',
        '$device',
        '$banner_type',
        '$target_link',
        '$status'
    )
");

/* SUCCESS */
header("Location: banners.php?module=banner&action=upload&status=success");
exit;