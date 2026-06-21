<?php

require '../config/database.php';

$username =
trim($_POST['username']);

$fullname =
trim($_POST['fullname']);

$email =
trim($_POST['email']);

$password =
$_POST['password'];

$check = mysqli_prepare(
    $conn,
    "SELECT id
    FROM users
    WHERE email=? OR username=?"
);

mysqli_stmt_bind_param(
    $check,
    "ss",
    $email,
    $username
);

mysqli_stmt_execute($check);

$result =
mysqli_stmt_get_result($check);

if(mysqli_num_rows($result) > 0){

    header(
        "Location: ../register.php?error=already_used"
    );

    exit;
}

$hash =
password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users
    (
        username,
        fullname,
        email,
        password
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?
    )"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $username,
    $fullname,
    $email,
    $hash
);

mysqli_stmt_execute($stmt);

header(
    "Location: ../register.php?success=registered"
);

exit;
?>
