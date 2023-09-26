<?php
  session_start();
  include('connection.php');
  $productID = $_POST['id'];

  $user = $conn -> query("SELECT cart FROM user WHERE uid='".$_SESSION['client']."'");
  $user = $user -> fetch_assoc();

  $userCart = $user['cart'];
  $userCart = json_decode($userCart, true);

  unset($userCart[$productID]);
  $updatedCart = json_encode($userCart);  
  // print_r($updatedCart);
  $update = $conn -> query("UPDATE user SET cart='$updatedCart' WHERE uid='".$_SESSION['client']."'");

  if($update){
    echo 1;
  }

  $conn -> close();
?>