<?php

session_start();

require 'config/database.php';

$user_id = $_SESSION['user_id'];

$music_id = $_POST['music_id'];

$check = mysqli_query(

$conn,

"SELECT *
 FROM music_favorite
 WHERE user_id='$user_id'
 AND music_id='$music_id'"

);

if(mysqli_num_rows($check)>0){

    mysqli_query(

    $conn,

    "DELETE FROM music_favorite
     WHERE user_id='$user_id'
     AND music_id='$music_id'"

    );

}else{

    mysqli_query(

    $conn,

    "INSERT INTO music_favorite
     (user_id,music_id)
     VALUES
     ('$user_id','$music_id')"

    );

}

echo json_encode([
    "success" => true
]);