<?php
  include('../connection.php');

  $status = "IN DELIVERY";

  $select = $conn -> query("SELECT * FROM production WHERE status='$status'");
  
  while($row = $select -> fetch_array()){
    echo "
      <div class='card mb-4 p-4'>
        <div>
          <a href='#order' id='".$row['reference_number']."' onclick='open_offcanvas(this.id)'>ID: ".$row['reference_number']."</a>
          <p class='is-size-7'>CLIENT ID: ".$row['client']."</p>
        </div>
        <div style='margin-left: auto;'>
        <button class='button is-small is-success' id='".$row['reference_number']."' onclick='complete_delivery(this.id)'>Complete Delivery</button>
        </div>
      </div>
    ";
  }

  $conn -> close();
?>