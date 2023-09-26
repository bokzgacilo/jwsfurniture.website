<?php
  include('connection.php');

  $sql = $conn -> query("SELECT * FROM product WHERE id='".$_GET['id']."'");
  $product = $sql -> fetch_assoc();

  echo "
    <p class='is-size-4 has-text-weight-bold mb-4'>Update Stock</p>
    <form action='api/changeStock.php' method='post' class='is-flex is-flex-direction-column'>
      <input type='hidden' name='pid' value='".$_GET['id']."' />
      <input type='number' name='stock' value='".$product['stock']."' class='input mb-4' />
      <button class='button is-success' type='submit'>Update Stock</button>
    </form>
  ";

  $conn -> close();
?>