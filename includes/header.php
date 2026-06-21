<!DOCTYPE html>
<html lang="id">

<head>

    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">

    <title>AFTE</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <?php
    $cssFile = __DIR__ . '/../assets/css/style.css';
    ?>

    <link rel="stylesheet" href="/assets/css/style.css?v=<?= file_exists($cssFile) ? filemtime($cssFile) : time(); ?>">

</head>

<body></body>