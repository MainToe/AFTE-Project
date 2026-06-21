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

$title = mysqli_real_escape_string($conn, $_POST['title']);
$device = mysqli_real_escape_string($conn, $_POST['device']);
$banner_type = mysqli_real_escape_string($conn, $_POST['banner_type']);
$target_link = mysqli_real_escape_string($conn, $_POST['target_link']);
$status = mysqli_real_escape_string($conn, $_POST['status']);

$old_image = $_POST['old_image'] ?? '';
$image = $old_image;

/* IMAGE UPLOAD */
if (!empty($_FILES['image']['name'])) {

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        header("Location: edit_banner.php?id=$id&module=banner&action=update&error=image_type");
        exit;
    }

    if ($_FILES['image']['size'] > 1024 * 1024) {
        header("Location: edit_banner.php?id=$id&module=banner&action=update&error=image_size");
        exit;
    }

    if (!empty($old_image) && file_exists('../uploads/banner/' . $old_image)) {
        unlink('../uploads/banner/' . $old_image);
    }

    $image = time() . '_' . basename($_FILES['image']['name']);

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        '../uploads/banner/' . $image
    );
}

/* UPDATE */
mysqli_query($conn, "
    UPDATE banners SET
    title='$title',
    image='$image',
    device='$device',
    banner_type='$banner_type',
    target_link='$target_link',
    status='$status'
    WHERE id='$id'
");

header("Location: banner.php?module=banner&action=update&status=success");
exit;