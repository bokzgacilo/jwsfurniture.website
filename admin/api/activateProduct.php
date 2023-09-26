<?php
  include('connection.php');

  $productID = $_POST['product_id'];

  $sql = $conn -> query("UPDATE product SET status='active' WHERE id='$productID'");

  if($sql){
    echo 1;
  }else {
    echo 0;
  }

  $conn -> close();
?>