<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$user_id = $_SESSION['user_id'];

$search =
    isset($_GET['search'])
    ? mysqli_real_escape_string(
        $conn,
        $_GET['search']
    )
    : '';

$musicQuery = mysqli_query(

    $conn,

    "SELECT music_album.*,

IF(music_favorite.id IS NULL,0,1)
AS is_favorite

FROM music_album

LEFT JOIN music_favorite

ON music_album.id =
music_favorite.music_id

AND music_favorite.user_id =
'$user_id'

WHERE

title LIKE '%$search%'
OR artist LIKE '%$search%'

ORDER BY music_album.id DESC"

);


$historyMusic = mysqli_query(

    $conn,

    "SELECT music_album.*,
1 as is_favorite

FROM music_favorite

JOIN music_album
ON music_favorite.music_id =
music_album.id

WHERE music_favorite.user_id =
'$user_id'

ORDER BY music_favorite.id DESC"

);

include 'includes/header.php';
include 'includes/navbar.php';

?>

<div class="music-container">

    <div class="music-top-header">

        <h1>🎵 Album Music</h1>

        <button id="themeToggle" class="theme-toggle">

            🌙

        </button>

    </div>

    <form method="GET" class="music-search">

        <input type="text" name="search" placeholder="Cari lagu atau artist..."
            value="<?= htmlspecialchars($search) ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>

        </button>

    </form>

    <h2>🎧 Semua Lagu</h2>

    <div class="music-nav music-nav-main">

        <button class="music-prev">

            <i class="fa-solid fa-chevron-left"></i>

        </button>

        <button class="music-next">

            <i class="fa-solid fa-chevron-right"></i>

        </button>

    </div>

    <?php if (mysqli_num_rows($musicQuery) > 0): ?>

        <div class="music-slider">

            <?php while ($music = mysqli_fetch_assoc($musicQuery)): ?>

                <?php
                $flagImage =
                    $music['category'] == 'jepang'
                    ? 'assets/bendera/jp.png'
                    : 'assets/bendera/id.png';
                ?>

                <div class="music-card">

                    <img src="uploads/music/cover/<?= !empty($music['cover']) ? htmlspecialchars($music['cover']) : 'default-music.jpg' ?>"
                        alt="Cover" class="music-cover">

                    <div class="music-title-row">

                        <img src="<?= $flagImage ?>" class="music-flag">

                        <div class="music-marquee">

                            <span>
                                <?= htmlspecialchars($music['title']) ?>
                            </span>

                        </div>

                    </div>

                    <p>
                        <?= htmlspecialchars($music['artist']) ?>
                    </p>

                    <div class="music-actions">

                        <button class="play-music-btn" data-music-id="<?= $music['id'] ?>"
                            data-index="<?= $music['id'] ?>"
                            data-favorite="<?= $music['is_favorite'] ?>" data-title="<?= htmlspecialchars($music['title']) ?>"
                            data-artist="<?= htmlspecialchars($music['artist']) ?>"
                            data-cover="uploads/music/cover/<?= htmlspecialchars($music['cover']) ?>"
                            data-audio="uploads/music/audio/<?= htmlspecialchars($music['music_file']) ?>">

                            <i class="fa-solid fa-play"></i>

                        </button>

                        <button class="favorite-btn <?= $music['is_favorite'] ? 'active' : '' ?>" data-id="<?= $music['id'] ?>">

                            <i class="fa-solid fa-heart"></i>

                        </button>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty-state">

            <i class="fa-solid fa-music"></i>

            <h3>Musik Tidak Ditemukan</h3>

            <p>
                Lagu atau artist yang dicari tidak tersedia.
            </p>

        </div>

    <?php endif; ?>

<h2>❤️ Favorite Music</h2>

<div class="music-nav music-nav-favorite">

    <button class="music-prev">

        <i class="fa-solid fa-chevron-left"></i>

    </button>

    <button class="music-next">

        <i class="fa-solid fa-chevron-right"></i>

    </button>

</div>

<div class="music-slider">

    <?php while ($music = mysqli_fetch_assoc($historyMusic)): ?>

        <?php

        $flagImage =
            $music['category'] == 'jepang'
            ? 'assets/bendera/jp.png'
            : 'assets/bendera/id.png';

        ?>

        <div class="music-card">

            <img src="uploads/music/cover/<?= !empty($music['cover']) ? htmlspecialchars($music['cover']) : 'default-music.jpg' ?>"
                alt="Cover" class="music-cover">

            <div class="music-title-wrap">

                <div class="music-title-row">

                    <img src="<?= $flagImage ?>" class="music-flag" alt="flag">

                    <div class="music-marquee">

                        <span>

                            <?= htmlspecialchars($music['title']) ?>

                        </span>

                    </div>

                </div>

            </div>

            <p>
                <?= htmlspecialchars($music['artist']) ?>
            </p>

            <div class="music-actions">

                <button class="play-music-btn" data-music-id="<?= $music['id'] ?>"
                            data-index="<?= $music['id'] ?>" data-favorite="<?= $music['is_favorite'] ?>"
                    data-title="<?= htmlspecialchars($music['title']) ?>"
                    data-artist="<?= htmlspecialchars($music['artist']) ?>"
                    data-cover="uploads/music/cover/<?= htmlspecialchars($music['cover']) ?>"
                    data-audio="uploads/music/audio/<?= htmlspecialchars($music['music_file']) ?>">

                    <i class="fa-solid fa-play"></i>

                </button>

                <button class="favorite-btn <?= $music['is_favorite'] ? 'active' : '' ?>" data-id="<?= $music['id'] ?>">

                    <i class="fa-solid fa-heart"></i>

                </button>

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

        <button id="spFavoriteBtn" data-id="" data-favorite="0">


            <i class="fa-regular fa-heart"></i>

        </button>

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

<?php include 'includes/footer.php'; ?>