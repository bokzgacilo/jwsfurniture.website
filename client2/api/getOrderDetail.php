<?php
  include('connection.php');

  $orderID = $_GET['id'];

  $sql = $conn -> query("SELECT * FROM transactions WHERE reference_number='$orderID'");
  $sql = $sql -> fetch_assoc();

  $order_item = json_decode($sql['orders'], true);

  foreach ($order_item as $item) {
    $select_item = $conn -> query("SELECT * FROM product WHERE id='".$item['id']."'");

    while($row = $select_item -> fetch_array()){
      echo "
        <div class='orders mb-4'>
          <div>
            <img src='".$row['product_photo']."' />
          </div>
          <div>
            <p class='is-size-5 has-text-weight-bold'>".$row['name']."</p>
            <p class='is-size-5'>₱ ".number_format($row['price'], 2, '.', ',')."</p>
            <p class='is-size-6'>Ordered: ".$item['quantity']."</p>
            <p class='is-size-6 has-text-weight-medium'>Total: ₱ ".number_format(($item['quantity']*$row['price']), 2, '.', ',')."</p>
          </div>
        </div>
      ";
    }
  }

  $conn -> close();
?>
