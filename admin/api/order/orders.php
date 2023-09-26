<?php
  include('../connection.php');

  $status = "Order Placed";

  $select = $conn -> query("SELECT * FROM production WHERE status='$status'");
  
  if($select -> num_rows == 0){
    echo "
      <p>No Orders</p>
    ";
  }else {
    while($row = $select -> fetch_array()){
      $item = json_decode($row['orders'], true);
    //   $item_count = count($item);
  
      echo "
        <div class='card mb-4 p-4'>
          <div>
            <a href='#order' id='".$row['reference_number']."' onclick='open_offcanvas(this.id)'>ID: ".$row['reference_number']."</a>
            <p class='is-size-7'>CLIENT ID: ".$row['client']."</p>
          </div>
          <div style='margin-left: auto;'>
            <button id='".$row['reference_number']."' onclick='accept_order(this.id)' class='button is-success is-small'>Accept Order</button>
          </div>
        </div>
      ";
    }
  }

  $conn -> close();
?>