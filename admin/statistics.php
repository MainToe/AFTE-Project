<?php

session_start();

require '../config/database.php';

if (
    !isset($_SESSION['admin_verified'])
) {
    header(
        "Location: admin_pin.php"
    );
    exit;
}

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    include '../includes/access_denied.php';
    exit;
}

$totalRevenue = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT
        COALESCE(
            SUM(total_price),
            0
        ) total

        FROM orders

        WHERE status='complete'
        "
    )

)['total'];

$totalOrders = mysqli_num_rows(

    mysqli_query(
        $conn,
        "SELECT id FROM orders"
    )

);

$totalUsers = mysqli_num_rows(

    mysqli_query(
        $conn,
        "SELECT id FROM users"
    )

);

$totalProducts = mysqli_num_rows(

    mysqli_query(
        $conn,
        "SELECT id FROM products"
    )

);

$pendingOrders = mysqli_num_rows(

    mysqli_query(
        $conn,
        "
        SELECT id
        FROM orders
        WHERE status='pending'
        "
    )

);

$processOrders = mysqli_num_rows(

    mysqli_query(
        $conn,
        "
        SELECT id
        FROM orders
        WHERE status='process'
        "
    )

);

$completeOrders = mysqli_num_rows(

    mysqli_query(
        $conn,
        "
        SELECT id
        FROM orders
        WHERE status='complete'
        "
    )

);

include '../includes/header.php';
include '../includes/navbar.php';

?>

<div class="admin-container">

    <h1>📈 Statistik Penjualan AFTE</h1>

    <div class="stats-grid">

        <div class="stat-card">

            <h3>Total Pendapatan</h3>

            <h2>

                Rp <?= number_format($totalRevenue) ?>

            </h2>

        </div>

        <div class="stat-card">

            <h3>Total Pesanan</h3>

            <h2>

                <?= $totalOrders ?>

            </h2>

        </div>

        <div class="stat-card">

            <h3>Total User</h3>

            <h2>

                <?= $totalUsers ?>

            </h2>

        </div>

        <div class="stat-card">

            <h3>Total Produk</h3>

            <h2>

                <?= $totalProducts ?>

            </h2>

        </div>

        <div class="stat-card pending">

            <h3>Pending</h3>

            <h2>

                <?= $pendingOrders ?>

            </h2>

        </div>

        <div class="stat-card process">

            <h3>Diproses</h3>

            <h2>

                <?= $processOrders ?>

            </h2>

        </div>

        <div class="stat-card complete">

            <h3>Selesai</h3>

            <h2>

                <?= $completeOrders ?>

            </h2>

        </div>

    </div>

    <?php

    $bestProducts = mysqli_query(

        $conn,

        "
    SELECT

    products.name,

    SUM(order_items.qty) total_sold

    FROM order_items

    JOIN products

    ON order_items.product_id = products.id

    GROUP BY products.id

    ORDER BY total_sold DESC

    LIMIT 5
    "

    );

    ?>

    <div class="top-product-box">

        <h2>Produk Terlaris</h2>

        <table class="admin-table">

            <tr>

                <th>Produk</th>
                <th>Terjual</th>

            </tr>

            <?php while ($row = mysqli_fetch_assoc($bestProducts)): ?>

                <tr>

                    <td>

                        <?= htmlspecialchars($row['name']) ?>

                    </td>

                    <td>

                        <?= $row['total_sold'] ?>

                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    </div>

    <?php

    $chartData = [];

    for ($i = 1; $i <= 12; $i++) {

        $row = mysqli_fetch_assoc(

            mysqli_query(
                $conn,
                "
            SELECT
            COALESCE(
                SUM(total_price),
                0
            ) total

            FROM orders

            WHERE status='complete'

            AND MONTH(created_at)='$i'
            "
            )

        );

        $chartData[] = $row['total'];
    }

    ?>

    <div class="chart-box">

        <h2>Grafik Penjualan Bulanan</h2>

        <canvas id="salesChart" data-sales='<?= json_encode($chartData) ?>'>
        </canvas>

    </div>

</div>

<?php include '../includes/footer.php'; ?>