<?php

session_start();

require '../config/database.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE email=? LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if(!$user){
   header(
    "Location: ../login.php?error=email_not_found"
);
exit;
}

if(!password_verify(
    $password,
    $user['password']
)){
    header(
    "Location: ../login.php?error=wrong_password"
);
exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['fullname'] = $user['fullname'];
$_SESSION['role'] = $user['role'];

if($user['role'] === 'admin'){
    header("Location: ../index.php");
}else{
    header("Location: ../index.php");
}

exit;