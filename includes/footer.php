<footer>
    <p>© <?= date('Y'); ?> AFTE Store</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php
$jsFile = __DIR__ . '/../assets/js/script.js';
?>

<script src="/assets/js/script.js?v=<?= file_exists($jsFile) ? filemtime($jsFile) : time(); ?>"></script>

<?php if (isset($_SESSION['user_id'])): ?>

    <?php if (empty($hideFloatingCart ?? null)): ?>
        <a href="cart.php" class="floating-cart">

            🛒

            <span><?= $cart_count ?? 0 ?></span>
        </a>
    <?php endif; ?>

<?php endif; ?>

</body>

</html>