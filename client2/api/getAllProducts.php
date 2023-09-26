<?php
  // sleep(3);

  include('connection.php');

  $sql = $conn -> query("SELECT * FROM product WHERE status='active'");

  if($sql -> num_rows != 0){
    while($row = $sql -> fetch_array()){
      echo "
        <div onclick='quickView(this.id)' class='is-flex is-flex-direction-column card p-4' id='".$row['id']."'>
          <figure class='image is-128x128 is-align-self-center mb-4'>
            <img src='".$row['product_photo']."'>
          </figure>
          <p class='is-size-6 has-text-weight-semibold has-text-ellipsis'>".$row['name']."</p>
          <div style='color: rgb(233, 132, 0);' class='is-size-5 has-text-weight-semibold mt-auto is-flex is-flex-direction-row is-align-items-center'>₱ ".number_format($row['price'], 2, '.', ',')." 
          <span class='ml-auto me-2 is-size-7' style='color: #000 !important;'>".$row['sold']." sold </span>
            <span class='is-size-7' style='color: #000 !important;'>".$row['stock']." stock </span></div>
        </div>
      ";
    }
  }else {
    echo 0;
  }
  
  $conn -> close();
?>