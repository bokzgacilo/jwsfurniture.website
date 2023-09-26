<?php
    include_once "connection.php";

  $reference_number = $_POST['reference_number'];
  
  $status_description = "ORDER COMPLETED";

  $sql = $conn -> query("UPDATE production SET status='ORDER COMPLETED', status_description='$status_description' WHERE reference_number='$reference_number'");

  if($sql){
    echo 1;
    $conn -> query("UPDATE transactions SET status='ORDER COMPLETED' WHERE reference_number='$reference_number'");
  }else {
    echo 0;
  }

  $conn -> close();
?>