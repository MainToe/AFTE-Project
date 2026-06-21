<?php

session_start();
require '../config/database.php';

if (
    !isset($_SESSION['admin_verified'])
) {
    header("Location: admin_pin.php");
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

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM banners
        WHERE id='$id'"
    )
);

include '../includes/header.php';
include '../includes/navbar.php';

?>

<div class="admin-form-container">

    <div class="admin-form-card">

        <h1>Edit Banner</h1>

        <form id="bannerUpdateForm" action="update_banner.php" method="POST" enctype="multipart/form-data" class="admin-form">

            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <input type="hidden" name="old_image" value="<?= $data['image'] ?>">

            <div class="form-group">

                <label>Preview Banner Saat Ini</label>

                <img src="../uploads/banner/<?= htmlspecialchars($data['image']) ?>" class="banner-preview">

            </div>

            <div class="form-group">

                <label>Ganti Banner</label>

                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">

                <small>
                    Kosongkan jika tidak ingin mengganti banner
                </small>

            </div>

            <div class="form-group">

                <label>Judul Banner</label>

                <input type="text" name="title" maxlength="100" value="<?= htmlspecialchars($data['title']) ?>">

            </div>

            <div class="form-group">

                <label>Target Device</label>

                <select name="device">

                    <option value="desktop" <?= $data['device'] == 'desktop' ? 'selected' : '' ?>>

                        Desktop

                    </option>

                    <option value="mobile" <?= $data['device'] == 'mobile' ? 'selected' : '' ?>>

                        Mobile

                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Tipe Banner</label>

                <select name="banner_type">

                    <option value="none" <?= $data['banner_type'] == 'none' ? 'selected' : '' ?>>

                        Tidak Bisa Diklik

                    </option>

                    <option value="internal" <?= $data['banner_type'] == 'internal' ? 'selected' : '' ?>>

                        Internal Link

                    </option>

                    <option value="external" <?= $data['banner_type'] == 'external' ? 'selected' : '' ?>>

                        External Link

                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Target Link</label>

                <input type="text" name="target_link" value="<?= htmlspecialchars($data['target_link']) ?>"
                    placeholder="product.php?id=1 atau https://google.com">

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status">

                    <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>

                        Active

                    </option>

                    <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>

                        Inactive

                    </option>

                </select>

            </div>

            <button type="submit" class="admin-save-btn">

                <i class="fa-solid fa-floppy-disk"></i>
                Update Banner

            </button>


        </form>

    </div>

</div>

<div id="bannerLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Mengperbarui Banner...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<?php include '../includes/footer.php'; ?>