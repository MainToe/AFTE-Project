<?php

include '../includes/header.php';
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

$query = mysqli_query(
    $conn,
    "SELECT * FROM products ORDER BY id DESC"
);

?>

<div class="admin-container">

    <div class="admin-header">

        <h1>Kelola Produk</h1>

        <a href="add_product.php" class="admin-add-btn">

            <i class="fa-solid fa-plus"></i>
            Tambah Produk

        </a>

    </div>

    <table class="admin-table">

        <tr>

            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Aksi</th>

        </tr>

        <?php while ($row = mysqli_fetch_assoc($query)): ?>

            <tr>

                <td><?= $row['id'] ?></td>

                <td>
                    <?= htmlspecialchars($row['name']) ?>
                </td>

                <td>
                    Rp <?= number_format($row['price'], 0, ',', '.') ?>
                </td>

                <td>

                    <div class="admin-action">

                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn-edit">

                            <i class="fa-solid fa-pen"></i>
                            Edit

                        </a>

                        <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn-delete"
                            onclick="return confirm('Hapus produk ini?')">

                            <i class="fa-solid fa-trash"></i>
                            Hapus

                        </a>

                    </div>

                </td>

            </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php include '../includes/footer.php'; ?>