<?php

session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = (int)($_POST['order_id'] ?? 0);

if (
    !isset($_FILES['proof']) ||
    $_FILES['proof']['error'] !== UPLOAD_ERR_OK
) {
    header(
        "Location: payment.php?id=".$order_id."&error=nofile"
    );
    exit;
}

$fileName = $_FILES['proof']['name'];
$fileSize = $_FILES['proof']['size'];

$allowedExt = [
    'jpg',
    'jpeg',
    'png',
    'webp'
];

$allowedMime = [
    'image/jpeg',
    'image/png',
    'image/webp'
];

$ext = strtolower(
    pathinfo(
        $fileName,
        PATHINFO_EXTENSION
    )
);

if (!in_array($ext, $allowedExt)) {

    header(
        "Location: payment.php?id=".$order_id."&error=filetype"
    );

    exit;
}

if ($fileSize > 1024 * 1024) {

    header(
        "Location: payment.php?id=".$order_id."&error=filesize"
    );

    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);

$mime = finfo_file(
    $finfo,
    $_FILES['proof']['tmp_name']
);

finfo_close($finfo);

if (!in_array($mime, $allowedMime)) {

    header(
        "Location: payment.php?id=".$order_id."&error=filetype"
    );

    exit;
}

if (
    getimagesize(
        $_FILES['proof']['tmp_name']
    ) === false
) {

    header(
        "Location: payment.php?id=".$order_id."&error=filetype"
    );

    exit;
}

$filename =
    time() . '_' .
    preg_replace(
        '/[^A-Za-z0-9_.-]/',
        '_',
        basename($fileName)
    );

move_uploaded_file(
    $_FILES['proof']['tmp_name'],
    'uploads/payment/' . $filename
);

mysqli_query(
    $conn,
    "UPDATE orders
    SET
        payment_proof='$filename',
        status='verification'
    WHERE id='$order_id'"
);

header(
    "Location: orders.php?success=payment_success"
);

exit;
?>
