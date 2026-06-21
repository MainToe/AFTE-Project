<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$user_id = (int) $_SESSION['user_id'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id' LIMIT 1"
);

$profile = mysqli_fetch_assoc($result);

if (!$profile) {
    die("Data pengguna tidak ditemukan");
}

include 'includes/header.php';

?>

<div class="profile-container">

    <div class="profile-card">

        <h2>Edit Profil</h2>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">

            <div class="avatar-preview">

                <?php if (!empty($profile['avatar'])): ?>

                    <img src="uploads/avatar/<?= htmlspecialchars($profile['avatar']) ?>" alt="Avatar">

                <?php else: ?>

                    <i class="fa-solid fa-circle-user"></i>

                <?php endif; ?>

            </div>

            <label>Foto Profil</label>

            <input type="file" name="avatar" accept="image/*">

            <label>Nama Lengkap</label>

            <input type="text" id="fullname" name="fullname" maxlength="50"
                value="<?= htmlspecialchars($profile['fullname'] ?? '') ?>">

            <small id="nameCounter">0/50 karakter</small>

            <label>Email</label>

            <div class="email-group">

                <input type="text" id="email" name="email" maxlength="30"
                    value="<?= htmlspecialchars(str_replace('@gmail.com', '', $profile['email'] ?? '')) ?>">

                <span>@gmail.com</span>

            </div>

            <small id="emailCounter">0/30 karakter</small>

            <input type="text" id="phone" name="phone" maxlength="13"
                value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">

            <small id="phoneCounter">0/13 digit</small>

            <label>Umur</label>

            <input type="number" id="age" name="age" min="10" max="99"
                value="<?= htmlspecialchars($profile['age'] ?? '') ?>">

            <small id="ageCounter">0/2 digit</small>

            <label>Alamat</label>

            <div class="address-section">

                <label>Pilih Lokasi Rumah</label>

                <button type="button" id="currentLocationBtn" class="location-btn">

                    📍 Gunakan Lokasi Saat Ini

                </button>

                <div id="map"></div>

                <div class="address-grid">

                    <input type="text" id="province" name="province" placeholder="Provinsi"
                        value="<?= htmlspecialchars($profile['province'] ?? '') ?>">

                    <input type="text" id="city" name="city" placeholder="Kota"
                        value="<?= htmlspecialchars($profile['city'] ?? '') ?>">

                    <input type="text" id="district" name="district" placeholder="Kecamatan"
                        value="<?= htmlspecialchars($profile['district'] ?? '') ?>">

                    <input type="text" id="village" name="village" placeholder="Desa"
                        value="<?= htmlspecialchars($profile['village'] ?? '') ?>">

                    <input type="text" id="street" name="street" placeholder="Jalan"
                        value="<?= htmlspecialchars($profile['street'] ?? '') ?>">

                    <input type="text" id="block" name="block" placeholder="Blok / Nomor Rumah"
                        value="<?= htmlspecialchars($profile['block'] ?? '') ?>">>

                </div>

                <small class="address-note">
                    Klik lokasi rumah pada peta untuk mengisi alamat otomatis atau isi manual.
                </small>

            </div>

            <input type="hidden" id="latitude" name="latitude"
                value="<?= htmlspecialchars($profile['latitude'] ?? '') ?>">

            <input type="hidden" id="longitude" name="longitude"
                value="<?= htmlspecialchars($profile['longitude'] ?? '') ?>">

            <button type="submit">

                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan

            </button>

        </form>

    </div>

</div>

<?php include 'includes/footer.php'; ?>