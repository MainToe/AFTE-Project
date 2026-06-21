<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$user_id = (int) $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id' LIMIT 1"
);

$profile = mysqli_fetch_assoc($query);

if (!$profile) {
    die("Data user tidak ditemukan.");
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="profile-container">

    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-avatar">

                <?php if (!empty($profile['avatar'])): ?>

                    <img src="uploads/avatar/<?= htmlspecialchars($profile['avatar']) ?>" alt="Avatar"
                        style="width:100%;height:100%;object-fit:cover;border-radius:50%;">

                <?php else: ?>

                    <i class="fa-solid fa-circle-user"></i>

                <?php endif; ?>

            </div>

            <h2>
                <?= htmlspecialchars($profile['fullname']) ?>
            </h2>

            <p>
                <?= htmlspecialchars($profile['email']) ?>
            </p>

        </div>

        <div class="profile-body">

            <div class="profile-row">

                <label>Nama Lengkap</label>

                <span>
                    <?= htmlspecialchars($profile['fullname']) ?>
                </span>

            </div>

            <div class="profile-row">

                <label>Nomor WhatsApp</label>

                <span>
                    <?= !empty($profile['phone']) ? htmlspecialchars($profile['phone']) : 'Belum diisi' ?>
                </span>

            </div>

            <div class="profile-row">

                <label>Alamat</label>

                <div class="profile-address">

                    <?php if (!empty($profile['province'])): ?>

                        <?= htmlspecialchars($profile['street']) ?>

                        <?php if (!empty($profile['block'])): ?>

                            <br>

                            Blok <?= htmlspecialchars($profile['block']) ?>

                        <?php endif; ?>

                        <br>

                        <?= htmlspecialchars($profile['village']) ?>

                        <br>

                        <?= htmlspecialchars($profile['district']) ?>

                        <br>

                        <?= htmlspecialchars($profile['city']) ?>

                        <br>

                        <?= htmlspecialchars($profile['province']) ?>

                    <?php else: ?>

                        Belum dipilih

                    <?php endif; ?>

                </div>

            </div>

            <div class="profile-row">

                <label>Umur</label>

                <span>
                    <?= !empty($profile['age']) ? htmlspecialchars($profile['age']) : 'Belum diisi' ?>
                </span>

            </div>

        </div>

        <a href="edit_profile.php" class="edit-profile-btn">

            <i class="fa-solid fa-pen"></i>

            Edit Profil

        </a>

        <div class="profile-menu">

            <a href="orders.php" class="menu-card">

                <i class="fa-solid fa-box"></i>

                <span>Riwayat Pesanan</span>

            </a>

            <a href="music.php" class="menu-card">

                <i class="fa-solid fa-music"></i>

                <span>Album Music</span>

            </a>

            <a href="logout.php" class="menu-card logout-card">

                <i class="fa-solid fa-right-from-bracket"></i>

                <span>Logout</span>

            </a>

        </div>

    </div>

</div>

<?php if(isset($_GET['success'])): ?>
<input type="hidden" id="profile_success" value="1">
<?php endif; ?>

<?php include 'includes/footer.php'; ?>