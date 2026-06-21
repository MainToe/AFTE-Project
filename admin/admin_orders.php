<?php

session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    include '../includes/access_denied.php';
    exit;
}

$orders = mysqli_query(
    $conn,
    "SELECT
        orders.*,
        users.fullname,
        users.phone,
        users.province,
        users.city,
        users.district,
        users.village,
        users.street,
        users.block
    FROM orders
    JOIN users
        ON orders.user_id = users.id
    ORDER BY orders.id DESC"
);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="orders-container">

    <h2>Manajemen Pesanan</h2>

    <?php while ($order = mysqli_fetch_assoc($orders)): ?>

        <div class="order-card">

            <div class="order-top">

                <div>

                    <h3>
                        <?= $order['order_code'] ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($order['fullname']) ?>
                    </p>

                </div>

                <span class="status-badge status-<?= $order['status'] ?>">

                    <?= ucfirst($order['status']) ?>

                </span>

            </div>

            <h3>📍Alamat Pengiriman</h3>

            <div class="order-card">

                <?php if (!empty($order['province'])): ?>

                    <div class="shipping-detail">

                        <p>
                            <strong>
                                <?= htmlspecialchars($order['fullname']) ?>
                            </strong>
                        </p>

                        <p>
                            <?= htmlspecialchars($order['phone']) ?>
                        </p>

                        <p>
                            <?= htmlspecialchars($order['street']) ?>

                            <?= !empty($order['block'])
                                ? ' Blok ' . htmlspecialchars($order['block'])
                                : '' ?>
                        </p>

                        <p>
                            <?= htmlspecialchars($order['village']) ?>,
                            <?= htmlspecialchars($order['district']) ?>
                        </p>

                        <p>
                            <?= htmlspecialchars($order['city']) ?>,
                            <?= htmlspecialchars($order['province']) ?>
                        </p>

                    </div>

                <?php else: ?>

                    <div class="warning-box">

                        <span>

                            Alamat belum dilengkapi

                        </span>

                    </div>

                <?php endif; ?>

            </div>

            <div class="order-body">

                <p>
                    Metode:
                    <?= $order['payment_method'] ?>
                </p>

                <h4>
                    Rp <?= number_format($order['total_price']) ?>
                </h4>

            </div>

            <?php if (!empty($order['payment_proof'])): ?>

                <div style="margin-bottom:15px;">

                    <img src="../uploads/payment/<?= $order['payment_proof'] ?>" style="
                    width:180px;
                    border-radius:15px;
                    ">

                </div>

            <?php endif; ?>

            <div class="order-footer admin-order-action">

                <a href="update_order_status.php?id=<?= $order['id'] ?>&status=process" class="action-btn btn-process">

                    <i class="fa-solid fa-gears"></i>
                    Proses

                </a>

                <a href="update_order_status.php?id=<?= $order['id'] ?>&status=complete" class="action-btn btn-complete">

                    <i class="fa-solid fa-circle-check"></i>
                    Selesai

                </a>

                <a href="admin_cancel_order.php?id=<?= $order['id'] ?>" class="action-btn btn-cancel"
                    onclick="return confirm('Batalkan pesanan ini?')">

                    <i class="fa-solid fa-ban"></i>
                    Batalkan

                </a>

            </div>

        </div>

    <?php endwhile; ?>

</div>

<?php include '../includes/footer.php'; ?>