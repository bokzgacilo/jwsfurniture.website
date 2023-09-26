<?php
  session_start();
  include('connection.php');

  $new_password = $_POST['password'];
  $uid = $_SESSION['client'];

  $update = $conn -> query("UPDATE user SET password='$new_password' WHERE uid='$uid'");

  if($update){
    header("location: ../user.php");
  }else {
    session_destroy();
  }

  $conn -> close();
?>