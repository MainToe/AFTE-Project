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

$query = mysqli_query(
    $conn,
    "SELECT *
    FROM products
    WHERE id='$id'
    LIMIT 1"
);

$product = mysqli_fetch_assoc($query);

if (!$product) {
    die('Produk tidak ditemukan');
}

include '../includes/header.php';
?>

<div class="admin-form-container">

    <div class="admin-form-card">

        <h1>Edit Produk</h1>

        <form id="productUpdateForm" action="update_product.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <div class="form-group">

                <label>Gambar Saat Ini</label>

                <img src="../uploads/product/<?= htmlspecialchars($product['image']) ?>?v=<?= time() ?>"
                    class="edit-product-preview" alt="Preview Produk">

            </div>

            <div class="form-group">

                <label>Ganti Gambar Produk</label>

                <input type="file" name="image" accept="image/*">

                <small>
                    Kosongkan jika tidak ingin mengganti gambar
                </small>

            </div>

            <div class="form-group">

                <label>Nama Produk</label>

                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            </div>

            <div class="form-group">

                <label>Harga Produk</label>

                <input type="number" name="price" value="<?= $product['price'] ?>" required>

            </div>

            <div class="form-group">

                <label>Stok Produk</label>

                <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0" required>

            </div>

            <div class="form-group">

                <label>Deskripsi Produk</label>

                <textarea name="description" rows="5"><?= htmlspecialchars($product['description']) ?></textarea>

            </div>

            <div class="admin-btn-group">

                <a href="products.php" class="admin-cancel-btn">

                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali

                </a>

                <button type="submit" class="admin-save-btn">

                    <i class="fa-solid fa-floppy-disk"></i>
                    Update Produk

                </button>

            </div>

        </form>

    </div>

    <div id="productLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Memperbarui Product...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

    <?php include '../includes/footer.php'; ?>