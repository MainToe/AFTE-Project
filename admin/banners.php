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

$query = mysqli_query(
    $conn,
    "SELECT * FROM banners ORDER BY id DESC"
);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="admin-container">

    <div class="admin-header">

        <h1>Kelola Banner</h1>

        <a href="add_banner.php" class="admin-add-btn">
            Tambah Banner
        </a>

    </div>

    <table class="admin-table">

        <tr>

            <th>ID</th>
            <th>Banner</th>
            <th>Device</th>
            <th>Tipe</th>
            <th>Status</th>
            <th>Aksi</th>

        </tr>

        <?php while($row = mysqli_fetch_assoc($query)): ?>

        <tr>

            <td><?= $row['id'] ?></td>

            <td>

                <img
                src="../uploads/banner/<?= $row['image'] ?>"
                width="180">

            </td>

            <td>

                <?= ucfirst($row['device']) ?>

            </td>

            <td>

                <?= ucfirst($row['banner_type']) ?>

            </td>

            <td>

                <?= ucfirst($row['status']) ?>

            </td>

            <td>

                <a href="edit_banner.php?id=<?= $row['id'] ?>">
                    Edit
                </a>

                |

                <a
                href="delete_banner.php?id=<?= $row['id'] ?>"
                onclick="return confirm('Hapus banner?')">

                    Hapus

                </a>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php include '../includes/footer.php'; ?>