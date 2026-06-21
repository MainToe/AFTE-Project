<?php

session_start();

require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$userQuery = mysqli_query(
    $conn,
    "SELECT
        fullname,
        phone,
        province,
        city,
        district,
        village,
        street,
        block
    FROM users
    WHERE id='$user_id'
    LIMIT 1"
);

$user = mysqli_fetch_assoc($userQuery);
$addressComplete =
    !empty($user['province']) &&
    !empty($user['city']) &&
    !empty($user['district']) &&
    !empty($user['village']) &&
    !empty($user['street']);

$query = mysqli_query(
    $conn,
    "SELECT
        cart.id,
        cart.quantity,
        products.name,
        products.price,
        products.image
    FROM cart
    JOIN products
        ON cart.product_id = products.id
    WHERE cart.user_id='$user_id'"
);

$cart_count = mysqli_num_rows($query);

mysqli_data_seek($query, 0);

$total = 0;

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="cart-container">

    <h1>Keranjang Saya</h1>

    <?php if ($cart_count > 0): ?>

        <form action="create_order.php" method="POST" id="cartForm">

            <?php while ($item = mysqli_fetch_assoc($query)): ?>

                <?php
                $subtotal =
                    $item['price'] *
                    $item['quantity'];

                $total += $subtotal;
                ?>

                <div class="cart-card">

                    <div class="cart-left">

                        <input type="checkbox" class="cart-check" name="selected[]" value="<?= $item['id'] ?>"
                            data-price="<?= $subtotal ?>" checked>

                    </div>

                    <div class="cart-image">

                        <img src="uploads/cart/<?= htmlspecialchars($item['image']) ?>" alt="">

                    </div>

                    <div class="cart-info">

                        <h3>
                            <?= htmlspecialchars($item['name']) ?>
                        </h3>

                        <p>

                            Rp <?= number_format(
                                $item['price'],
                                0,
                                ',',
                                '.'
                            ) ?>

                        </p>

                        <small>

                            Subtotal :
                            Rp <?= number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ) ?>

                        </small>

                        <div class="qty-box">

                            <a href="cart/minus.php?id=<?= $item['id'] ?>">

                                -

                            </a>

                            <span>

                                <?= $item['quantity'] ?>

                            </span>

                            <a href="cart/add.php?id=<?= $item['id'] ?>">

                                +

                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

            <div class="shipping-address">

                <h3>Alamat Pengiriman</h3>

                <?php if ($addressComplete): ?>

                    <p>

                        <?= htmlspecialchars($user['street']) ?>

                        <?= !empty($user['block'])
                            ? ' Blok ' . htmlspecialchars($user['block'])
                            : '' ?>

                        <br>

                        <?= htmlspecialchars($user['village']) ?>

                        <br>

                        <?= htmlspecialchars($user['district']) ?>

                        <br>

                        <?= htmlspecialchars($user['city']) ?>

                        <br>

                        <?= htmlspecialchars($user['province']) ?>

                    </p>

                <?php else: ?>

                    <p class="address-empty">

                        Alamat belum diisi

                    </p>

                    <a href="edit_profile.php" class="complete-address-btn">

                        Lengkapi Alamat

                    </a>

                <?php endif; ?>

            </div>

            <input type="hidden" id="address_complete" value="<?= $addressComplete ? 1 : 0 ?>">

            <div class="payment-picker">

                <label>

                    Metode Pembayaran

                </label>

                <div class="payment-card-picker" onclick="choosePayment()">

                    <div>

                        <small>

                            Pilih Metode

                        </small>

                        <h4 id="selectedMethod">

                            Pilih Metode Pembayaran

                        </h4>

                    </div>

                    <i class="fa-solid fa-chevron-down"></i>

                </div>

                <input type="hidden" name="payment_method" id="payment_method" required>

            </div>

            <div class="cart-bottom">

                <div>

                    <small>Total</small>

                    <h3 id="cartTotal">

                        Rp <?= number_format(
                            $total,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </h3>

                </div>

                <button type="submit" class="checkout-btn">

                    Checkout

                </button>

            </div>

        </form>

    <?php else: ?>

        <div class="empty-cart">

            <i class="fa-solid fa-cart-shopping"></i>

            <h3>
                Keranjang Masih Kosong
            </h3>

            <p>
                Yuk pilih produk favoritmu terlebih dahulu.
            </p>

            <a href="index.php" class="shop-btn">

                Belanja Sekarang

            </a>

        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>