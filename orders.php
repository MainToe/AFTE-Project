<?php

session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$orders = mysqli_query(
    $conn,
    "SELECT *
    FROM orders
    WHERE user_id='$user_id'
    ORDER BY id DESC"
);

$pending_count = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id
        FROM orders
        WHERE user_id='$user_id'
        AND status='pending'"
    )
);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="orders-container">

<h2>Riwayat Pesanan</h2>

<?php if($pending_count > 0): ?>

    <div class="pending-alert">

        <i class="fa-solid fa-clock"></i>

        Anda memiliki
        <strong><?= $pending_count ?></strong>
        pesanan yang belum dibayar.

    </div>

<?php endif; ?>

<?php if (mysqli_num_rows($orders) > 0): ?>

    <?php while ($order = mysqli_fetch_assoc($orders)): ?>

        <div class="order-card">

            <div class="order-top">

                <div>

                    <h3>
                        <?= htmlspecialchars($order['order_code']) ?>
                    </h3>

                    <p>
                        <?= $order['created_at'] ?>
                    </p>

                </div>

                <span class="status-badge status-<?= $order['status'] ?>">

                    <?= ucfirst($order['status']) ?>

                </span>

            </div>

            <div class="order-body">

                <p>Total Pesanan</p>

                <h4>
                    Rp <?= number_format($order['total_price'],0,',','.') ?>
                </h4>

            </div>

            <div class="order-footer">

                <a
                    href="order_detail.php?id=<?= $order['id'] ?>"
                    class="action-btn btn-method">

                    <i class="fa-solid fa-eye"></i>
                    Detail Pesanan

                </a>

                <?php if($order['status'] == 'pending'): ?>

                    <a
                        href="payment.php?id=<?= $order['id'] ?>"
                        class="action-btn btn-pay">

                        <i class="fa-solid fa-wallet"></i>
                        Bayar Sekarang

                    </a>

                    <button
                        type="button"
                        class="action-btn btn-cancel"
                        onclick="cancelOrder(<?= $order['id'] ?>)">

                        <i class="fa-solid fa-xmark"></i>
                        Batalkan Pesanan

                    </button>

                <?php endif; ?>

            </div>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="empty-order">

        <i class="fa-solid fa-box-open"></i>

        <h3>Belum Ada Pesanan</h3>

        <p>Pesananmu akan muncul di sini.</p>

    </div>

<?php endif; ?>

</div>

<?php if(isset($_GET['success'])): ?>
<input type="hidden" id="payment_success" value="1">
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
