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

include '../includes/header.php';

$total_music = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM music_album"
    )
);

$total_indo = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id
        FROM music_album
        WHERE category='indonesia'"
    )
);

$total_japan = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id
        FROM music_album
        WHERE category='jepang'"
    )
);

$allMusic = mysqli_query(
    $conn,
    "SELECT *
    FROM music_album
    ORDER BY id DESC"
);

?>

<div class="music-page">

    <div class="music-header">

        <h1>Kelola Music AFTE</h1>

        <a href="add_music.php" class="admin-add-btn">

            <i class="fa-solid fa-plus"></i>
            Tambah Music

        </a>

    </div>

    <div class="music-stats">

        <div class="music-stat-card">

            <h3><?= $total_music ?></h3>

            <span>Total Music</span>

        </div>

        <div class="music-stat-card">

            <h3><?= $total_indo ?></h3>

            <span>Indonesia</span>

        </div>

        <div class="music-stat-card">

            <h3><?= $total_japan ?></h3>

            <span>Jepang</span>

        </div>

    </div>

    <h2 class="music-section-title">

        🎵 Semua Lagu

    </h2>

    <div class="music-slider">

        <?php while ($music = mysqli_fetch_assoc($allMusic)): ?>

            <?php

            $flagImage =
                $music['category'] == 'jepang'
                ? '../assets/bendera/jp.png'
                : '../assets/bendera/id.png';

            ?>

            <div class="music-card">

                <img src="../uploads/music/cover/<?= !empty($music['cover'])
                    ? htmlspecialchars($music['cover'])
                    : 'default-music.jpg' ?>" class="music-cover">

                <div class="music-title-row">

                    <img src="<?= $flagImage ?>" class="music-flag" alt="flag">

                    <div class="music-marquee">

                        <span>

                            <?= htmlspecialchars($music['title']) ?>

                        </span>

                    </div>

                </div>

                <p>

                    <?= htmlspecialchars($music['artist']) ?>

                </p>

                <?php if (!empty($music['music_file'])): ?>

                    <div class="music-actions">

                        <button class="play-music-btn" data-index="<?= $music['id'] ?>"
                            data-favorite="<?= $music['is_favorite'] ?>" data-title="<?= htmlspecialchars($music['title']) ?>"
                            data-artist="<?= htmlspecialchars($music['artist']) ?>"
                            data-cover="../uploads/music/cover/<?= htmlspecialchars($music['cover']) ?>"
                            data-audio="../uploads/music/audio/<?= htmlspecialchars($music['music_file']) ?>">

                            <i class="fa-solid fa-play"></i>

                        </button>

                    </div>

                <?php endif; ?>


                <div class="music-actions">

                    <a href="edit_music.php?id=<?= $music['id'] ?>" class="admin-edit-btn">

                        <i class="fa-solid fa-pen"></i>
                        Edit

                    </a>

                    <a href="delete_music.php?id=<?= $music['id'] ?>" class="admin-delete-btn"
                        onclick="return confirm('Hapus lagu ini?')">

                        <i class="fa-solid fa-trash"></i>
                        Hapus

                    </a>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

</div>

<!-- Spotify Player -->

<div id="spotifyPlayer" class="spotify-player">

    <div class="sp-top">

        <img id="spCover" class="sp-cover">

        <div class="sp-info">

            <div class="sp-title">
                <span id="spTitle"></span>
            </div>

            <div id="spArtist" class="sp-artist"></div>

        </div>

    </div>

    <div class="sp-controls">

        <button id="prevBtn">
            <i class="fa-solid fa-backward-step"></i>
        </button>

        <button id="playPauseBtn">
            <i class="fa-solid fa-play"></i>
        </button>

        <button id="nextBtn">
            <i class="fa-solid fa-forward-step"></i>
        </button>

    </div>

    <div class="sp-progress-wrap">

        <span id="currentTime">0:00</span>

        <input type="range" id="progressBar" value="0">

        <span id="duration">0:00</span>

    </div>

    <div class="sp-bottom">

        <select id="speedControl">

            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected>1x</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="2">2x</option>

        </select>

        <button id="downloadBtn">
            <i class="fa-solid fa-download"></i>
        </button>

        <div class="sp-volume">

            <i class="fa-solid fa-volume-high"></i>

            <input type="range" id="volumeControl" min="0" max="1" step="0.1" value="1">

        </div>

    </div>

</div>

<audio id="globalAudio"></audio>

<?php
$hideFloatingCart = true;
?>

<?php include '../includes/footer.php'; ?>