<?php
  session_start();
  $_SESSION['payment_method'] = "GCASH";
  include('connection.php');
  include('env.php');

  require_once('../../vendor/autoload.php');

  $client = new \GuzzleHttp\Client();
  $uniqid = uniqid();

  $amount = $_POST['amount'];
  
  $user = $conn -> query("SELECT * FROM user WHERE uid='".$_SESSION['client']."'");
  $address = $conn -> query("SELECT * FROM address WHERE uid='".$_SESSION['client']."'");

  $user = $user -> fetch_assoc();
  $address = $address -> fetch_assoc();

  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $reference_id = '';

  for ($i = 0; $i < 16; $i++) {
    $reference_id .= $characters[rand(0, strlen($characters) - 1)];
  }

  $phone = $address['contact'];
  $email = $user['email'];
  $name = $user['name'];
  $description = $user['cart'];
  $_SESSION['gcash_reference_number'] = $reference_id;
echo $email;
  $response = $client -> request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
    'body' => '{"data":
      {
        "attributes":{
          "billing":{
            "email":"'.$email.'",
            "name":"'.$name.'",
            "phone":"'.$phone.'"
          },
          "send_email_receipt":true,
          "show_description":true,
          "show_line_items":true,
          "cancel_url":"https://jwsfurniture.website/client2/cancelled.php",
          "description":"Payment checkout for transaction: '.$reference_id.'",
          "line_items":[{
            "currency":"PHP",
            "amount": '.$amount.'00,
            "name":"Sample Payment",
            "quantity":1}],
          "payment_method_types":["gcash"],
          "reference_number": "'.$reference_id.'",
          "success_url":"'.$success_url_live.'"
        }
      }
    }',
    'headers' => [
      'Content-Type' => 'application/json',
      'accept' => 'application/json',
      'authorization' => 'Basic c2tfdGVzdF9pYXloeE1wMXY3VFVBcU00ZnhZeUh0YVg6',
    ],
  ]);
  $response_json = json_decode($response -> getBody(), true);
  if($response_json){
    
    $checkoutURL = $response_json['data']['attributes']['checkout_url'];

    $add_to_transaction = $conn -> query("INSERT INTO transactions(transaction_id,mode,reference_number,checkout_url,amount,orders,status) VALUES (
      '".$response_json['data']['id']."',
      'GCASH',
      '$reference_id',
      '$checkoutURL',
      '$amount',
      '".$user['cart']."',
      '".$response_json['data']['attributes']['payment_intent']['attributes']['status']."'
    )");
    
    if($add_to_transaction){
      $_SESSION['payment_status'] = $response_json['data']['attributes']['payment_intent']['attributes']['status'];
      $_SESSION['transaction_id'] = $response_json['data']['id'];

      header("location: $checkoutURL");
    }else {
      echo "Creating transaction failed";
    }
  }
  $conn -> close();
?>