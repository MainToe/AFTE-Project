<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;

if (isset($_SESSION['user_id'])) {

    require_once __DIR__ . '/../config/database.php';

    $cart_query = mysqli_query(
        $conn,
        "SELECT SUM(quantity) total
        FROM cart
        WHERE user_id=" . $_SESSION['user_id']
    );

    $cart_data = mysqli_fetch_assoc($cart_query);

    $cart_count = $cart_data['total'] ?? 0;
}
?>

<nav class="navbar">

    <div class="nav-left">

        <a href="/index.php" class="logo">
            AFTE
        </a>

    </div>

    <div class="nav-center">

        <a href="/index.php">
            <i class="fa-solid fa-house"></i>
            Beranda
        </a>

        <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>

            <a href="/admin/admin_pin.php">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>

        <?php endif; ?>

    </div>

    <div class="nav-right">

        <?php if(isset($_SESSION['user_id'])): ?>

            <a href="/cart.php" class="cart-icon">

                <i class="fa-solid fa-cart-shopping"></i>

                <?php if($cart_count > 0): ?>
                    <span><?= $cart_count ?></span>
                <?php endif; ?>

            </a>

            <a href="/profile.php" class="profile-btn">

                <i class="fa-solid fa-circle-user"></i>

            </a>

        <?php else: ?>

            <a href="/login.php" class="login-btn">

                <i class="fa-solid fa-right-to-bracket"></i>
                Login

            </a>

            <a href="/register.php" class="register-btn">

                <i class="fa-solid fa-user-plus"></i>
                Register

            </a>

        <?php endif; ?>

    </div>

</nav>

<?php if(isset($_SESSION['success'])): ?>

<div id="globalToast" class="global-toast success">

    <i class="fa-solid fa-circle-check"></i>

    <span>
        <?= $_SESSION['success']; ?>
    </span>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['error'])): ?>

<div id="globalToast" class="global-toast error">

    <i class="fa-solid fa-circle-xmark"></i>

    <span>
        <?= $_SESSION['error']; ?>
    </span>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>