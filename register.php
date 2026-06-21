<?php
session_start();

include 'includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register AFTE</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime('assets/css/style.css'); ?>">
</head>

<body>

    <div class="auth-page">

        <div class="auth-card">
            <?php if (isset($_GET['error'])): ?>

                <div class="alert error">

                    <?php

                    if ($_GET['error'] == 'already_used') {
                        echo "Username atau Email sudah digunakan";
                    }

                    ?>

                </div>

            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>

                <div class="alert success">

                    <?php

                    if ($_GET['success'] == 'registered') {
                        echo "Akun berhasil dibuat";
                    }

                    ?>

                </div>

            <?php endif; ?>
            <div class="auth-logo">

                <h1>AFTE</h1>

                <p>
                    Daftar dan mulai belanja
                </p>

            </div>

            <form action="auth/register_process.php" method="POST" class="auth-form">

                <input type="text" name="username" placeholder="Username" maxlength="30" required>

                <input type="text" name="fullname" placeholder="Nama Lengkap" maxlength="50" required>

                <input type="email" name="email" placeholder="Email" required>

                <input type="password" name="password" placeholder="Password" required>

                <button class="auth-btn" type="submit">

                    Register

                </button>

            </form>


            <div class="auth-footer">

                Sudah punya akun?

                <a href="login.php">
                    Login
                </a>

            </div>

        </div>

    </div>

    <script src="/assets/js/script.js?v=<?php echo time(); ?>"></script>

</body>

</html>