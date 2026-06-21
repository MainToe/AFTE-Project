<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    include '../includes/access_denied.php';
    exit;
}

include '../includes/header.php';

?>

<div class="pin-modal">

    <div class="pin-modal-card">

        <div class="pin-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h2>Verifikasi PIN Admin</h2>

        <p>
            Masukkan PIN 6 digit untuk melanjutkan
        </p>

        <form action="verify_pin.php" method="POST">

            <input type="hidden" name="pin" id="realPin">

            <div class="pin-wrapper">

                <input type="password" maxlength="1" class="pin-input">
                <input type="password" maxlength="1" class="pin-input">
                <input type="password" maxlength="1" class="pin-input">
                <input type="password" maxlength="1" class="pin-input">
                <input type="password" maxlength="1" class="pin-input">
                <input type="password" maxlength="1" class="pin-input">

            </div>

            <button type="submit" class="pin-submit">

                Verifikasi

            </button>

        </form>

    </div>

</div>

<?php include '../includes/footer.php'; ?>