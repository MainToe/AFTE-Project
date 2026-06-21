<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$id = (int)$_SESSION['user_id'];

$fullname = mysqli_real_escape_string(
    $conn,
    trim($_POST['fullname'] ?? '')
);

$phone = mysqli_real_escape_string(
    $conn,
    trim($_POST['phone'] ?? '')
);

$age_input = trim($_POST['age'] ?? '');

if ($age_input === '') {
    $age = "NULL";
} else {

    $age = (int)$age_input;

    if ($age < 10 || $age > 99) {

        header(
            "Location: edit_profile.php?error=age"
        );

        exit;
    }
}

$province = mysqli_real_escape_string(
    $conn,
    $_POST['province'] ?? ''
);

$city = mysqli_real_escape_string(
    $conn,
    $_POST['city'] ?? ''
);

$district = mysqli_real_escape_string(
    $conn,
    $_POST['district'] ?? ''
);

$village = mysqli_real_escape_string(
    $conn,
    $_POST['village'] ?? ''
);

$street = mysqli_real_escape_string(
    $conn,
    $_POST['street'] ?? ''
);

$block = mysqli_real_escape_string(
    $conn,
    $_POST['block'] ?? ''
);

$latitude = mysqli_real_escape_string(
    $conn,
    $_POST['latitude'] ?? ''
);

$longitude = mysqli_real_escape_string(
    $conn,
    $_POST['longitude'] ?? ''
);

if(!empty($_FILES['avatar']['name'])){

    $allowedExt = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    $ext = strtolower(
        pathinfo(
            $_FILES['avatar']['name'],
            PATHINFO_EXTENSION
        )
    );

    if(!in_array($ext,$allowedExt)){

        header(
            "Location:edit_profile.php?error=filetype"
        );

        exit;
    }

    if($_FILES['avatar']['size'] > 1024 * 1024){

        header(
            "Location:edit_profile.php?error=filesize"
        );

        exit;
    }

    $avatar =
        time().'_'.
        basename($_FILES['avatar']['name']);

    move_uploaded_file(
        $_FILES['avatar']['tmp_name'],
        'uploads/avatar/'.$avatar
    );

    mysqli_query(
        $conn,
        "UPDATE users
        SET avatar='$avatar'
        WHERE id='$id'"
    );
}

mysqli_query(
    $conn,
    "UPDATE users SET

    fullname='$fullname',
    phone='$phone',
    age=$age,

    province='$province',
    city='$city',
    district='$district',
    village='$village',
    street='$street',
    block='$block',

    latitude='$latitude',
    longitude='$longitude'

    WHERE id='$id'"
);

$_SESSION['fullname'] = $fullname;

header("Location: profile.php?success=1");
exit;

?>
