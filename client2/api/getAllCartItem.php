<?php
  include('connection.php');
  session_start();

  $userID = $_SESSION['client'];

  $sql = $conn -> query("SELECT * FROM user WHERE id=$userID");
  while($row = $sql -> fetch_array()){
    echo $row['cart'];
  }

  $conn -> close(); 
?>