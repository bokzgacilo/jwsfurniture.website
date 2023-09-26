<?php

  include('connection.php');

  $productID = $_GET['id'];

  $sql = $conn -> query("SELECT * FROM product WHERE id='$productID'");

  while($row = $sql -> fetch_array()){
    echo "
      <div class='qv-image w-50'>
        <img src='".$row['product_photo']."'>
      </div>
      <div class='qv-product w-25'>
        <h4>".$row['name']."</h4>
        <p>".$row['price']."</p>
        <p>Speficications and Description</p>
        <div class='product-description'>
          ".$row['description']."
        </div>
      </div>
      <div class='qv-orderform w-25'>
        <h4>Quantity</h4>
        <input id='item".$row['id']."' type='number' min='1' class='form-control' />
        <button onclick='addToCart(this.id)' id='".$row['id']."' class='btn btn-primary'>Add To Cart</button>
      </div>
    ";
  }

  $conn -> close();
?>