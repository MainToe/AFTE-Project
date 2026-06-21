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
        AND user_id='$user_id'"
    )
);

$user = mysqli_fetch_assoc(
    mysqli_query(
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
    )
);

if (!$order) {
    die("Pesanan tidak ditemukan");
}

$items = mysqli_query(
    $conn,
    "SELECT
        order_items.*,
        products.name,
        products.image
    FROM order_items
    JOIN products
        ON order_items.product_id = products.id
    WHERE order_items.order_id='$order_id'"
);

include 'includes/header.php';
include 'includes/navbar.php';

?>

<div class="orders-container">

    <div class="order-card">

        <div class="order-top">

            <div>

                <h2>
                    <?= htmlspecialchars($order['order_code']) ?>
                </h2>

                <p>
                    <?= $order['created_at'] ?>
                </p>

            </div>

            <span class="status-badge status-<?= $order['status'] ?>">

                <?= ucfirst($order['status']) ?>

            </span>

        </div>

        <h3>📍Alamat Pengiriman</h3>

        <div class="order-card">

            <?php if (!empty($user['province'])): ?>

                <div class="shipping-detail">

                    <p>

                        <strong>

                            <?= htmlspecialchars($user['fullname']) ?>

                        </strong>

                    </p>

                    <p>

                        <?= htmlspecialchars($user['phone']) ?>

                    </p>

                    <p>

                        <?= htmlspecialchars($user['street']) ?>

                        <?= !empty($user['block'])
                            ? ' Blok ' . htmlspecialchars($user['block'])
                            : '' ?>

                    </p>

                    <p>

                        <?= htmlspecialchars($user['village']) ?>,
                        <?= htmlspecialchars($user['district']) ?>

                    </p>

                    <p>

                        <?= htmlspecialchars($user['city']) ?>,
                        <?= htmlspecialchars($user['province']) ?>

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
                <strong>Metode Pembayaran :</strong>
                <?= htmlspecialchars($order['payment_method']) ?>
            </p>

            <p>
                <strong>Total :</strong>
                Rp <?= number_format($order['total_price']) ?>
            </p>

        </div>

    </div>

    <h3 style="margin:25px 0 15px;">
        Produk Pesanan
    </h3>

    <?php while ($item = mysqli_fetch_assoc($items)): ?>

        <div class="order-card">

            <div style="
            display:flex;
            gap:15px;
            align-items:center;
        ">

                <img src="uploads/product/<?= htmlspecialchars($item['image']) ?>" alt="" style="
                width:90px;
                height:90px;
                object-fit:cover;
                border-radius:15px;
                ">

                <div style="flex:1;">

                    <h4>
                        <?= htmlspecialchars($item['name']) ?>
                    </h4>

                    <p>
                        Qty :
                        <?= $item['qty'] ?>
                    </p>

                    <p>
                        Harga :
                        Rp <?= number_format($item['price']) ?>
                    </p>

                </div>

                <div>

                    <strong>

                        Rp <?= number_format(
                            $item['qty'] * $item['price']
                        ) ?>

                    </strong>

                </div>

            </div>

        </div>

    <?php endwhile; ?>

    <?php if (!empty($order['payment_proof'])): ?>

        <div class="order-card">

            <h3>Bukti Pembayaran</h3>

            <br>

            <img src="uploads/payment/<?= htmlspecialchars($order['payment_proof']) ?>" style="
            max-width:350px;
            width:100%;
            border-radius:20px;
            ">

        </div>

    <?php endif; ?>

    <?php if ($order['status'] == 'pending'): ?>

        <div class="order-action-box">

            <a href="payment.php?id=<?= $order['id'] ?>" class="action-btn btn-pay">

                <i class="fa-solid fa-credit-card"></i>
                Upload Bukti Pembayaran

            </a>

            <button type="button" class="action-btn btn-method" onclick="openPaymentModal()">

                <i class="fa-solid fa-repeat"></i>
                Ganti Metode Pembayaran

            </button>

        </div>

    <?php endif; ?>

</div>

<?php

$payments = mysqli_query(
    $conn,
    "SELECT *
    FROM payment_methods
    ORDER BY id ASC"
);

?>

<div id="paymentModal" class="payment-modal">

    <div class="payment-modal-content">

        <div class="payment-modal-header">

            <h3>
                Pilih Metode Pembayaran
            </h3>

            <button type="button" class="close-modal" onclick="closePaymentModal()">

                ×

            </button>

        </div>

        <form action="change_payment.php" method="POST">

            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

            <?php while ($pay = mysqli_fetch_assoc($payments)): ?>

                <label class="payment-select-card">

                    <input type="radio" name="payment_method" value="<?= $pay['method_name'] ?>"
                        <?= $order['payment_method'] == $pay['method_name'] ? 'checked' : '' ?>>

                    <div>

                        <strong>
                            <?= $pay['method_name'] ?>
                        </strong>

                        <?php if (!empty($pay['account_number'])): ?>

                            <small>

                                <?= $pay['account_number'] ?>

                            </small>

                        <?php endif; ?>

                    </div>

                </label>

            <?php endwhile; ?>

            <div class="payment-modal-footer">

                <button type="button" class="modal-cancel-btn" onclick="closePaymentModal()">

                    Batal

                </button>

                <button type="submit" class="modal-save-btn">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>

<?php if(isset($_GET['success'])): ?>
<input type="hidden" id="payment_change" value="1">
<?php endif; ?>

<?php include 'includes/footer.php'; ?>