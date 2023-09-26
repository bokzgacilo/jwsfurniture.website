<?php
  session_start();
  $_SESSION['payment_method'] = "COD";
  
  include('connection.php');
  include('env.php');

  $amount = $_POST['amount'];
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $reference_id = '';
  
  for ($i = 0; $i < 16; $i++) {
    $reference_id .= $characters[rand(0, strlen($characters) - 1)];
  }

  $_SESSION['cod_reference_number'] = $reference_id;
  
  $userUid = $_SESSION['client'];
  $user = $conn -> query("SELECT * FROM user WHERE uid='$userUid'");
  $user = $user -> fetch_assoc();
  $address = $conn -> query("SELECT * FROM address WHERE uid='$userUid'");
  $address = $address -> fetch_assoc();
  $complete_address = $address['block'].", ".$address['street'].", ".$address['barangay'].", ".$address['city'].", ".$address['province'];

  $add_to_transaction = $conn -> query("INSERT INTO transactions(transaction_id,mode,reference_number,checkout_url,amount,orders,status) VALUES (
        '".uniqid('CODPAY_')."',
        '".$_SESSION['payment_method']."',
        '$reference_id',
        'Not applicable (COD Transaction)',
        '$amount',
        '".$user['cart']."',
        'PENDING'
      )");
  
  if($add_to_transaction){
    header("location:". $success_url_live);
  }else {
    echo "Creating transaction failed";
  }

  $conn -> close();
?>