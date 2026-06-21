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
$artist = mysqli_real_escape_string($conn, $_POST['artist']);
$category = mysqli_real_escape_string($conn, $_POST['category']);

/* GET OLD */
$music = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM music_album WHERE id='$id'")
);

if (!$music) {
    header("Location: music.php?module=music&action=update&error=notfound");
    exit;
}

$cover = $music['cover'];
$music_file = $music['music_file'];

/* COVER */
if (!empty($_FILES['cover']['name'])) {

    $allowedCover = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedCover)) {
        header("Location: edit_music.php?id=$id&module=music&action=update&error=cover_type");
        exit;
    }

    if ($_FILES['cover']['size'] > 1024 * 1024) {
        header("Location: edit_music.php?id=$id&module=music&action=update&error=cover_size");
        exit;
    }

    if (!empty($music['cover']) && $music['cover'] != 'default-music.jpg') {
        @unlink('../uploads/music/cover/' . $music['cover']);
    }

    $cover = time() . '_' . basename($_FILES['cover']['name']);

    move_uploaded_file(
        $_FILES['cover']['tmp_name'],
        '../uploads/music/cover/' . $cover
    );
}

/* MUSIC FILE */
if (!empty($_FILES['music_file']['name'])) {

    $audioExt = strtolower(pathinfo($_FILES['music_file']['name'], PATHINFO_EXTENSION));

    if ($audioExt !== 'mp3') {
        header("Location: edit_music.php?id=$id&module=music&action=update&error=music_type");
        exit;
    }

    if ($_FILES['music_file']['size'] > 15 * 1024 * 1024) {
        header("Location: edit_music.php?id=$id&module=music&action=update&error=music_size");
        exit;
    }

    if (!empty($music['music_file'])) {
        @unlink('../uploads/music/audio/' . $music['music_file']);
    }

    $music_file = time() . '_' . basename($_FILES['music_file']['name']);

    move_uploaded_file(
        $_FILES['music_file']['tmp_name'],
        '../uploads/music/audio/' . $music_file
    );
}

/* UPDATE DB */
mysqli_query($conn, "
    UPDATE music_album SET
    title='$title',
    artist='$artist',
    category='$category',
    cover='$cover',
    music_file='$music_file'
    WHERE id='$id'
");

header("Location: music.php?module=music&action=update&status=success");
exit;