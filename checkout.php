<?php

session_start();

if(empty($_POST['selected'])){
    die("Pilih minimal 1 produk");
}

require 'config/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(empty($_POST['selected'])){
    header("Location: cart.php");
    exit;
}

$selected = $_POST['selected'];

include 'includes/header.php';
include 'includes/navbar.php';

?>

<div class="checkout-container">

    <div class="checkout-card">

        <h2>Pilih Metode Pembayaran</h2>

        <form
            action="create_order.php"
            method="POST"
            class="payment-form">

            <?php foreach($selected as $cart_id): ?>

                <input
                    type="hidden"
                    name="selected[]"
                    value="<?= $cart_id ?>">

            <?php endforeach; ?>

            <label class="payment-option">

                <input
                    type="radio"
                    name="payment_method"
                    value="DANA"
                    required>

                <span>DANA</span>

            </label>

            <label class="payment-option">

                <input
                    type="radio"
                    name="payment_method"
                    value="GOPAY">

                <span>GOPAY</span>

            </label>

            <label class="payment-option">

                <input
                    type="radio"
                    name="payment_method"
                    value="SEABANK">

                <span>SEABANK</span>

            </label>

            <label class="payment-option">

                <input
                    type="radio"
                    name="payment_method"
                    value="QRIS">

                <span>QRIS</span>

            </label>

            <button
                type="submit"
                class="payment-btn">

                Buat Pesanan

            </button>

        </form>

    </div>

</div>

<?php include 'includes/footer.php'; ?>