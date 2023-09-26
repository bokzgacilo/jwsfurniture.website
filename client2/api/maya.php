<?php
  session_start();
  require_once('../../vendor/autoload.php');

  $_SESSION['payment_method'] = "MAYA";

  $client = new \GuzzleHttp\Client();

  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $reference_id = '';

  for ($i = 0; $i < 16; $i++) {
    $reference_id .= $characters[rand(0, strlen($characters) - 1)];
  }

  $options = $client -> request('POST', 'https://pg-sandbox.paymaya.com/checkout/v1/checkouts', [
    'body' => '{
      "totalAmount": {
        "value": '.$_POST['amount'].',
        "currency": "PHP"
        },
        "items":[
          {"totalAmount":{ 
            "value": '.$_POST['amount'].'},
            "name": "Sandbox Product"
          }
        ],
        "redirectUrl" : {
          "success" : "http://jwsfurniture.website/client2/success.php",
          "failure":"http://localhost/jws-furniture/client2/failed.php",
          "cancel":"http://localhost/jws-furniture/client2/cancelled.php"
        },
        "requestReferenceNumber": "'.$reference_id.'"
      }',
    'headers' => [
      'accept' => 'application/json',
      'authorization' => 'Basic cGstWjBPU3pMdkljT0kyVUl2RGhkVEdWVmZSU1NlaUdTdG5jZXF3VUU3bjBBaDo=',
      'content-type' => 'application/json',
    ],
  ]);

  $response = json_decode($options -> getBody() ,true);
  $_SESSION['maya_checkout_id'] = $response['checkoutId'];
  $_SESSION['maya_reference_number'] = $reference_id;
  $_SESSION['maya_amount'] = $_POST['amount'];
  $_SESSION['maya_href'] = $response['redirectUrl'];

  header("location:".$response['redirectUrl']);
  
  // print_r($response);

?>