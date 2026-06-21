<?php

session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = (int) $_GET['id'];

$order = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT *
        FROM orders
        WHERE id='$order_id'
        AND user_id='$user_id'
        LIMIT 1"
    )
);

if (!$order) {
    die("Pesanan tidak ditemukan");
}

$payment = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT *
        FROM payment_methods
        WHERE method_name='{$order['payment_method']}'
        LIMIT 1"
    )
);

include 'includes/header.php';
include 'includes/navbar.php';

?>

<div class="payment-container">

    <div class="payment-card">

        <h2>Pembayaran Pesanan</h2>

        <div class="payment-info">

            <p>
                <strong>Kode Pesanan</strong><br>
                <?= htmlspecialchars($order['order_code']) ?>
            </p>

            <p>
                <strong>Total Pembayaran</strong><br>
                Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
            </p>

        </div>

        <div class="payment-method-box">

            <h3>
                <?= htmlspecialchars($payment['method_name']) ?>
            </h3>

            <?php if ($payment['method_name'] == 'QRIS'): ?>

                <p class="payment-desc">
                    Scan QRIS berikut untuk melakukan pembayaran
                </p>

                <img src="uploads/payment/<?= htmlspecialchars($payment['qr_image']) ?>" class="payment-qris" alt="QRIS">

                <a href="uploads/payment/<?= htmlspecialchars($payment['qr_image']) ?>" download class="download-qris">

                    <i class="fa-solid fa-download"></i>
                    Download QRIS

                </a>

            <?php else: ?>

                <p class="payment-label">
                    Nomor Pembayaran
                </p>

                <div class="copy-box">

                    <span id="paymentNumber">

                        <?= htmlspecialchars($payment['account_number']) ?>

                    </span>

                    <button type="button" id="copyPaymentBtn">

                        <i class="fa-solid fa-copy"></i>
                        Salin

                    </button>

                </div>

                <p class="payment-name">

                    a.n

                    <strong>

                        <?= htmlspecialchars($payment['account_name']) ?>

                    </strong>

                </p>

            <?php endif; ?>

        </div>

        <form id="paymentUploadForm" action="upload_payment.php" method="POST" enctype="multipart/form-data"
            class="payment-form">

            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

            <input type="file" name="proof" id="proofFile" accept=".jpg,.jpeg,.png,.webp" required>

            <button type="submit" class="payment-btn">

                Upload Bukti Pembayaran

            </button>

        </form>

        <a href="orders.php" class="pay-later-btn">

            Bayar Nanti

        </a>

    </div>

</div>

<div id="paymentLoading" class="upload-loading">

    <div class="upload-loading-box">

        <div class="upload-spinner"></div>

        <h3>Mengupload Bukti...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<?php if (isset($_GET['error'])): ?>
    <input type="hidden" id="payment_error" value="<?= htmlspecialchars($_GET['error']) ?>">
<?php endif; ?>

<?php include 'includes/footer.php'; ?>