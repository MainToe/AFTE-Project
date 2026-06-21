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

$id = (int) $_GET['id'];

$music = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT *
FROM music_album
WHERE id='$id'"
    )

);

include '../includes/header.php';
?>

<div class="admin-form-container">

    <div class="admin-form-card">

        <h1>Edit Music</h1>

        <form id="musicUpdateForm" action="update_music.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $music['id'] ?>">

            <div class="form-group">

                <label>Judul Lagu</label>

                <input type="text" name="title" value="<?= htmlspecialchars($music['title']) ?>" required>

            </div>

            <div class="form-group">

                <label>Nama Artist</label>

                <input type="text" name="artist" value="<?= htmlspecialchars($music['artist']) ?>" required>

            </div>

            <div class="form-group">

                <label>Kategori</label>

                <select name="category">

                    <option value="indonesia" <?= $music['category'] == 'indonesia' ? 'selected' : '' ?>>

                        Indonesia

                    </option>

                    <option value="jepang" <?= $music['category'] == 'jepang' ? 'selected' : '' ?>>

                        Jepang

                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Cover Album</label>

                <input type="file" name="cover" accept=".jpg,.jpeg,.png">

            </div>

            <div class="form-group">

                <label>File Musik</label>

                <input type="file" name="music_file" accept=".mp3">

            </div>

            <button type="submit" class="admin-save-btn">

                <i class="fa-solid fa-floppy-disk"></i>
                Update Music

            </button>

        </form>

    </div>

</div>

<div id="musicLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Memperbarui Music...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<?php include '../includes/footer.php'; ?>