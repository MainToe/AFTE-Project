<?php

session_start();
require '../config/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$pin = trim($_POST['pin'] ?? '');

$user = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT admin_pin, role
         FROM users
         WHERE id='$user_id'
         LIMIT 1"
    )
);

if(!$user){
    header("Location: ../login.php");
    exit;
}

if($user['role'] != 'admin'){
    include '../includes/access_denied.php';
    exit;
}

if($pin === $user['admin_pin']){

    $_SESSION['admin_verified'] = true;

    header("Location: dashboard.php");
    exit;
}

header("Location: ../includes/access_denied.php?error=wrong_pin");
exit;