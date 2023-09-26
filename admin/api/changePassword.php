<?php
  include('connection.php');

  $password = $_POST['password'];
  $uid = $_POST['uid'];

  $sql = $conn -> query("UPDATE user SET password='$password' WHERE uid='$uid'");

  if($sql){
    header('location: ../dashboard.php#users');
  }else {
    header('location: ../dashboard.php#users');
  }

  $conn -> close();
?>