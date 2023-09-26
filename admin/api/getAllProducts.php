<?php
  include('connection.php');

  $sql = $conn -> query("SELECT * FROM product");

  while($row = $sql -> fetch_array()){
    echo "
    <div class='card p-4 is-flex is-flex-direction-column' id='".$row['id']."'>";

    if($row['status'] == 'active'){
      echo "<button class='button is-success is-small mb-4' onclick='deactivate(this.id)' id='".$row['id']."'>Deactivate</button>";
    }else {
      echo "<button class='button is-danger is-small mb-4' onclick='activate(this.id)' id='".$row['id']."'>Activate</button>";
    }
      
    echo  "<img class='mb-4' src='".$row['product_photo']."' />
      <p class='mt-auto is-size-6 has-text-weight-bold'>".$row['name']."</p>
      <p class='is-size-6'>₱ ".number_format($row['price'], 2, '.', ',')."</p>
    </div>";
  }

  $conn -> close();
?>