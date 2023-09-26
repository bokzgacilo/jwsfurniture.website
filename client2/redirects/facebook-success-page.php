<?php
  include('../api/connection.php');
  $userID = $_GET['id'];  

  $sql = $conn -> query("SELECT * FROM user WHERE id='$userID'");
  if($sql -> num_rows != 0){
    $row = $sql -> fetch_assoc();

    var_dump($row);
  }else {
    echo 'user not found.';
  }
  
  $conn -> close();
?>