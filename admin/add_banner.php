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

include '../includes/navbar.php';
?>

<div class="admin-form-container">

    <form id="bannerUploadForm" method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label>Judul Banner</label>
            <input type="text" name="title">
        </div>

        <div class="form-group">
            <label>Gambar Banner</label>
            <input type="file" name="image">
        </div>

        <div class="form-group">
            <label>Target Device</label>
            <select name="device">
                <option value="desktop">Desktop</option>
                <option value="mobile">Mobile</option>
            </select>
        </div>

        <div class="form-group">
            <label>Tipe Banner</label>
            <select name="banner_type">
                <option value="none">Tidak Bisa Diklik</option>
                <option value="internal">Internal Link</option>
                <option value="external">External Link</option>
            </select>
        </div>

        <div class="form-group">
            <label>Target Link</label>
            <input type="text" name="target_link">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="admin-save-btn">

            <i class="fa-solid fa-floppy-disk"></i>
            Simpan Banner

        </button>

    </form>

</div>

<div id="bannerLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Mengupload Banner...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<?php include '../includes/footer.php'; ?>