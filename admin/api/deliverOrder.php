<?php
  include('connection.php');

  $reference_number = $_POST['reference_number'];
  
  $status_description = "Your order is on the way to you";

  $sql = $conn -> query("UPDATE production SET status='IN DELIVERY', status_description='$status_description' WHERE reference_number='$reference_number'");

  if($sql){
    echo 1;
  }else {
    echo 0;
  }

  $conn -> close();
?>