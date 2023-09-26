<?php
  session_start();
  include('connection.php');
  
  $productID = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // echo $quantity;
  $clientID = $_SESSION['client'];
  $user = $conn -> query("SELECT cart FROM user WHERE uid='$clientID'");
  $user = $user -> fetch_assoc();

  $userCart = json_decode($user['cart'], true);

  $ToCart = (array)[
    "id" => $productID,
    "quantity" => $quantity
  ];

  $userCart[$productID] = $ToCart;

  $newCart = json_encode($userCart);
  

  $sql =  $conn -> query("UPDATE user SET cart='$newCart' WHERE uid='$clientID'");
  
  if($sql){
    echo 1;
  }
?>