<?php
  include('connection.php');

  $reference_number = $_GET['reference_number'];

  $sql = $conn -> query("SELECT * FROM production WHERE reference_number='$reference_number'");
  $transaction = $sql -> fetch_assoc();
  $order_item = json_decode($transaction['orders'], true);

  $order_item_render = "";

  foreach ($order_item as $key => $value) {
    $get_item = $conn -> query("SELECT * FROM product WHERE id='".$value['id']."'");
    $get_item = $get_item -> fetch_assoc();

    $order_item_render .= "
      <div class='order p-4'>
        <div>
          <img src='".$get_item['product_photo']."' />
        </div>
        <div>
          <p class='is-size-5 has-text-weight-semibold'>".$get_item['name']."</p>
          <p class='is-size-7'>Quantity: ".$value['quantity']."</p>
        </div>
      </div>
    ";
  }

  $conditional_button = "";
  $client = $conn -> query("SELECT name, email FROM user WHERE uid='".$transaction['client']."'");
  $client = $client -> fetch_assoc();

  $address = $conn -> query("SELECT contact FROM address WHERE uid='".$transaction['client']."'");
$address = $address -> fetch_assoc();

  $amount = $conn -> query("SELECT amount FROM transactions WHERE reference_number='$reference_number'");
  $amount = $amount -> fetch_assoc();

  if($transaction['status'] == 'Order Placed'){
    $conditional_button = "
      <button id='".$reference_number."' onclick='accept_order(this.id)' class='button is-success w-100'>Accept Order</button>
    ";
  }

  if($transaction['status'] == 'ACCEPTED'){
    $conditional_button = "
    <button class='button is-success w-100' id='$reference_number' onclick='deliver_order(this.id)'>Deliver Order</button>
    ";
  }


  echo "
    <div class='card p-4 mb-4'>
      <p>".$transaction['status_description']."</p>
    </div>
    <div class='mb-4 mt-2'>
      $conditional_button
    </div>
    <div class='mb-4 mt-4'>
      <p class='is-size-5 has-text-weight-bold'>Client: ".$client['name']." </p>
      <p class='has-text-weight-medium'>Email: ".$client['email']." </p>
      <p class='has-text-weight-medium'>Contact Number: ".$address['contact']." </p>
      <p class='has-text-weight-medium'>Address: ".$transaction['address']." </p>
      <p class='has-text-weight-medium'>Order Date: ".date('F j, Y g:i a', strtotime($transaction['date_created']))."</p>
      <p class='has-text-weight-medium'>Amount: ₱ ".number_format($amount['amount'], 2, '.', ',')."</p>
    </div>
    <p class='mb-4 mt-2 is-size-4 has-text-weight-semibold'>Items</p>
    <div class='mt-2'>
      $order_item_render
    </div>
  ";

  $conn -> close();
?>