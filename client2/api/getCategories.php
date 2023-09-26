<?php
  // sleep(1);

  include('connection.php');

  $sql = $conn -> query("SELECT DISTINCT category FROM product");
  while($row = $sql -> fetch_array()){
    $count = $conn -> query("SELECT * FROM product WHERE category='".$row['category']."'");

    echo "
      <a class='is-flex is-flex-direction-row is-align-items-center' name='".$row['category']."'>
        <figure class='image is-32x32 mr-2'>
          <img src='../assets/icon/".$row['category'].".png' />
        </figure>
        <p class='is-size-6 has-text-weight-medium'>".$row['category']."</p>
      </a>
    ";
  }

  $conn -> close();
?>