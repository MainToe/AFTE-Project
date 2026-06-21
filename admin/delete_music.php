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

mysqli_query(
$conn,
"DELETE FROM music_album
WHERE id='$id'"
);

header("Location: music.php");
exit;
