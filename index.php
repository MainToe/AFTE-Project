<?php

session_start();

require 'config/database.php';

$isMobile = preg_match(
    "/Android|iPhone|iPad|iPod|Mobile/i",
    $_SERVER['HTTP_USER_AGENT']
);

if($isMobile){

    $banners = mysqli_query(
        $conn,
        "
        SELECT *
        FROM banners
        WHERE status='active'
        AND device IN ('mobile','all')
        ORDER BY id DESC
        LIMIT 5
        "
    );

}else{

    $banners = mysqli_query(
        $conn,
        "
        SELECT *
        FROM banners
        WHERE status='active'
        AND device IN ('desktop','all')
        ORDER BY id DESC
        LIMIT 5
        "
    );

}

$sort =
    isset($_GET['sort'])
    ? $_GET['sort']
    : 'newest';

$search =
    isset($_GET['search'])
    ? mysqli_real_escape_string(
        $conn,
        $_GET['search']
    )
    : '';

$orderBy = "id DESC";

switch ($sort) {

    case 'cheap':
        $orderBy = "price ASC";
        break;

    case 'expensive':
        $orderBy = "price DESC";
        break;

    case 'name':
        $orderBy = "name ASC";
        break;

}

$productQuery = mysqli_query(

    $conn,

    "SELECT *
    FROM products
    WHERE name LIKE '%$search%'
    ORDER BY $orderBy"

);

include 'includes/header.php';
include 'includes/navbar.php';

?>

<section class="banner-slider">

    <div class="slider-wrapper">

        <?php while ($banner = mysqli_fetch_assoc($banners)): ?>

            <div class="banner-slide">

                <img src="uploads/banner/<?= htmlspecialchars($banner['image']) ?>" alt="Banner">

            </div>

        <?php endwhile; ?>

    </div>

</section>

<section class="search-section">

    <form method="GET">

        <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>

        </button>

    </form>

</section>

<section class="product-toolbar">

    <div class="product-title">

        <h2>Produk Kami</h2>

    </div>

    <div class="product-sort">

        <form method="GET">

            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <select name="sort" onchange="this.form.submit()">

                <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>

                    Terbaru

                </option>

                <option value="cheap" <?= $sort == 'cheap' ? 'selected' : '' ?>>

                    Harga Termurah

                </option>

                <option value="expensive" <?= $sort == 'expensive' ? 'selected' : '' ?>>

                    Harga Termahal

                </option>

                <option value="name" <?= $sort == 'name' ? 'selected' : '' ?>>

                    Nama A-Z

                </option>

            </select>

        </form>

    </div>

</section>

<section class="products">

    <?php if (mysqli_num_rows($productQuery) > 0): ?>

        <?php while ($product = mysqli_fetch_assoc($productQuery)): ?>

            <div class="product-card">

                <div class="product-image">

                    <img src="uploads/product/<?= htmlspecialchars($product['image']) ?>" alt="produk">

                </div>

                <div class="product-info">

                    <h3>

                        <?= htmlspecialchars($product['name']) ?>

                    </h3>

                    <p class="product-desc">

                        <?= htmlspecialchars(
                            substr(
                                $product['description'],
                                0,
                                70
                            )
                        ) ?>

                    </p>

                    <div class="stock-bottom">

                        Stock:
                        <?= $product['stock'] ?>

                    </div>

                    <div class="product-footer">

                        <span class="price">

                            Rp <?= number_format($product['price']) ?>

                        </span>

                        <a href="cart/add_to_cart.php?id=<?= $product['id'] ?>" class="add-btn">

                            +

                        </a>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="empty-state">

            <i class="fa-solid fa-box-open"></i>

            <h3>Produk Tidak Ditemukan</h3>

            <p>
                Coba gunakan kata kunci lain atau hapus filter pencarian.
            </p>

        </div>

    <?php endif; ?>

</section>

<?php include 'includes/footer.php'; ?>