<?php

$host = "localhost";
$user = "aftemyid_admin";
$pass = "nokADHI!.MY]@o[";
$db   = "aftemyid_afte_store";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>