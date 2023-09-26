<?php
  include('connection.php');

  $sql = $conn -> query("UPDATE product SET stock=".$_POST['stock']." WHERE id='".$_POST['pid']."'");

  if($sql){
    header('location: ../dashboard.php#inventory');
  }else {
    header('location: ../dashboard.php#inventory');
  }

  $conn -> close();
?>