<?php

session_start();

if (
    !isset($_SESSION['admin_verified'])
) {
    header(
        "Location: admin_pin.php"
    );
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    include '../includes/access_denied.php';
    exit;
}

include '../config/database.php';
include '../includes/header.php';

$total_products = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM products"
    )
);

$total_banner = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id
        FROM banners"
    )
);

$total_orders = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM orders"
    )
);

$total_users = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM users"
    )
);

$total_pending = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id
        FROM orders
        WHERE status='verification'"
    )
);

$total_music = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id
        FROM music_album"
    )
);

?>

<div class="admin-container">

    <div class="admin-header">

        <h1>Dashboard Admin</h1>

        <p>
            Selamat datang di Panel AFTE Store
        </p>

    </div>

    <div class="admin-stats">

        <div class="stat-card">

            <i class="fa-solid fa-cart-shopping"></i>

            <h3><?= $total_orders ?></h3>

            <span>Pesanan</span>

        </div>

        <div class="stat-card">

            <i class="fa-solid fa-images"></i>

            <h3><?= $total_banner ?></h3>

            <span>Banner</span>

        </div>

        <div class="stat-card">

            <i class="fa-solid fa-box"></i>

            <h3><?= $total_products ?></h3>

            <span>Produk</span>

        </div>

        <div class="stat-card">

            <i class="fa-solid fa-users"></i>

            <h3><?= $total_users ?></h3>

            <span>User</span>

        </div>

        <div class="stat-card">

            <i class="fa-solid fa-music"></i>

            <h3><?= $total_music ?></h3>

            <span>Music</span>

        </div>

        <div class="stat-card">

            <i class="fa-solid fa-money-check"></i>

            <h3><?= $total_pending ?></h3>

            <span>Verifikasi</span>

        </div>

    </div>

    <div class="admin-menu">

        <a href="statistics.php" class="admin-card">

            <i class="fa-solid fa-chart-line"></i>

            <h4>Statistik Penjualan</h4>

            <p>Lihat laporan penjualan toko</p>

        </a>

        <a href="banners.php" class="admin-card">

            <i class="fa-solid fa-images"></i>

            <h4>Kelola Banner</h4>

            <p>Upload banner slider beranda</p>

        </a>

        <a href="products.php" class="admin-card">

            <i class="fa-solid fa-box-open"></i>

            <h4>Kelola Produk</h4>

            <p>Edit dan hapus produk</p>

        </a>

        <a href="admin_orders.php" class="admin-card">

            <i class="fa-solid fa-receipt"></i>

            <h4>Kelola Pesanan</h4>

            <p>Verifikasi pembayaran</p>

        </a>

        <a href="music.php" class="admin-card">

            <i class="fa-solid fa-music"></i>

            <h4>Kelola Music</h4>

            <p>Tambah lagu Indonesia & Jepang</p>

        </a>

        <a href="../index.php" class="admin-card">

            <i class="fa-solid fa-store"></i>

            <h4>Lihat Toko</h4>

            <p>Buka halaman toko</p>

        </a>

    </div>

    <?php include '../includes/footer.php'; ?>