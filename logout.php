<?php

session_start();

unset($_SESSION['admin_verified']);
session_unset();
session_destroy();

header("Location: login.php");
exit;