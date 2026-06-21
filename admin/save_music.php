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
$artist = mysqli_real_escape_string($conn, $_POST['artist']);
$category = mysqli_real_escape_string($conn, $_POST['category']);

if (!$title || !$artist || !$category) {
    header("Location: add_music.php?module=music&action=upload&error=notfound");
    exit;
}

/* DEFAULT */
$cover = 'default-music.jpg';
$music_file = '';

/* =========================
   COVER
========================= */
if (!empty($_FILES['cover']['name'])) {

    $allowedCover = ['jpg','jpeg','png','webp'];
    $coverExt = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

    if (!in_array($coverExt, $allowedCover)) {
        header("Location: add_music.php?module=music&action=upload&error=cover_type");
        exit;
    }

    if ($_FILES['cover']['size'] > 1024 * 1024) {
        header("Location: add_music.php?module=music&action=upload&error=cover_size");
        exit;
    }

    $cover = time() . '_' . basename($_FILES['cover']['name']);

    move_uploaded_file(
        $_FILES['cover']['tmp_name'],
        '../uploads/music/cover/' . $cover
    );
}

/* =========================
   MUSIC FILE
========================= */
if (!empty($_FILES['music_file']['name'])) {

    $allowedAudio = ['mp3'];
    $audioExt = strtolower(pathinfo($_FILES['music_file']['name'], PATHINFO_EXTENSION));

    if (!in_array($audioExt, $allowedAudio)) {
        header("Location: add_music.php?module=music&action=upload&error=music_type");
        exit;
    }

    if ($_FILES['music_file']['size'] > 15 * 1024 * 1024) {
        header("Location: add_music.php?module=music&action=upload&error=music_size");
        exit;
    }

    $music_file = time() . '_' . basename($_FILES['music_file']['name']);

    move_uploaded_file(
        $_FILES['music_file']['tmp_name'],
        '../uploads/music/audio/' . $music_file
    );

} else {
    header("Location: add_music.php?module=music&action=upload&error=music_required");
    exit;
}

/* INSERT */
mysqli_query($conn, "
    INSERT INTO music_album (title, artist, category, cover, music_file)
    VALUES ('$title', '$artist', '$category', '$cover', '$music_file')
");

/* SUCCESS */
header("Location: music.php?module=music&action=upload&status=success");
exit;