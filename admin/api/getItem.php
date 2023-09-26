<?php
  include('connection.php');

  $productID = $_GET['id'];

  $sql = $conn -> query("SELECT * FROM product WHERE id=$productID");

  while($row = $sql -> fetch_array()){
    echo "
      <img src='".$row['product_photo']."' />
      <h4>".$row['name']."</h4>
      <p>".$row['price']."</p>
      <p>".$row['category']."</p>
      ".$row['description']."
    ";
  }

  $conn -> close();
?>