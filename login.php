<?php
include 'includes/header.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login AFTE</title>
       <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime('assets/css/style.css'); ?>">
</head>

<body>

    <div class="auth-page">

        <div class="auth-card">
            <?php if (isset($_GET['error'])): ?>

                <div class="alert error">

                    <?php

                    switch ($_GET['error']) {

                        case 'email_not_found':
                            echo "Email tidak ditemukan";
                            break;

                        case 'wrong_password':
                            echo "Password salah";
                            break;

                    }

                    ?>

                </div>

            <?php endif; ?>

            <div class="auth-logo">

                <h1>AFTE</h1>

                <p>
                    Selamat datang kembali
                </p>

            </div>

            <form action="auth/login_process.php" method="POST" class="auth-form">

                <input type="email" name="email" placeholder="Email" required>

                <input type="password" name="password" placeholder="Password" required>

                <button class="auth-btn" type="submit">

                    Login

                </button>

            </form>

            <div class="auth-footer">

                Belum punya akun?

                <a href="register.php">
                    Register
                </a>

            </div>

        </div>

    </div>

    <script src="/assets/js/script.js?v=<?php echo time(); ?>"></script>
    
</body>

</html>