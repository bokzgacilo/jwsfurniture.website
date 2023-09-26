<?php
  include('connection.php');

  $sql = $conn -> query("SELECT * FROM product");

  while($row = $sql -> fetch_array()){
    echo "
    <div class='t-data'>
      <p class='col-4'>".$row['name']."</p>
      <p class='col-2'>".$row['stock']."</p>
      <p class='col-2'>".$row['sold']."</p>
      <p class='col-2'>".$row['released']."</p>
      <p class='col-2'>".$row['returned']."</p>
    </div>";
  }

  $conn -> close();
?>