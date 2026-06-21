<?php

session_start();

include '../includes/header.php';

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

?>

<div class="admin-form-container">

    <div class="admin-form-card">

        <h1>Tambah Music</h1>

        <form id="musicUploadForm" action="save_music.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">

                <label>Judul Lagu</label>

                <input type="text" name="title" required>

            </div>

            <div class="form-group">

                <label>Artist</label>

                <input type="text" name="artist" required>

            </div>

            <div class="form-group">

                <label>Kategori</label>

                <select name="category">

                    <option value="indonesia">
                        Indonesia
                    </option>

                    <option value="jepang">
                        Jepang
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Cover Album</label>

                <input type="file" name="cover">

            </div>

            <div class="form-group">

                <label>File Music</label>

                <input type="file" name="music_file" accept=".mp3,.wav,.ogg,.m4a" required>

            </div>

            <button type="submit" class="admin-save-btn">

                <i class="fa-solid fa-floppy-disk"></i>
                Upload Music

            </button>


        </form>

    </div>

</div>

<div id="musicLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Mengupload Musik...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<?php include '../includes/footer.php'; ?>