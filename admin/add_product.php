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

<div class="admin-form-card">

    <h1>Tambah Produk</h1>

    <form id="productUploadForm" action="save_product.php" method="POST" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Nama Produk" required>

        <textarea name="description" placeholder="Deskripsi Produk"></textarea>

        <input type="number" name="price" placeholder="Harga Produk" required>

        <input type="file" name="image" required>

        <button type="submit" class="admin-save-btn">

            <i class="fa-solid fa-floppy-disk"></i>
            Simpan Produk

        </button>

    </form>

</div>

<div id="productLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Mengupload Produk...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<?php include '../includes/footer.php'; ?>