<?php
  require '../../vendor/autoload.php';
  session_start();
  $_SESSION['payment_method'] = 'PAYPAL';

  require_once('paypal-config.php');

  try {
    $response = $gateway -> purchase(array(
      'amount' => $_POST['amount'],
      'currency' => PAYPAL_CURRENCY,
      'returnUrl' => PAYPAL_RETURN_URL,
      'cancelUrl' => PAYPAL_CANCEL_URL
    )) -> send();

    if($response -> isRedirect()){
    //   print_r($response);

    //   sleep(2);

      $response -> redirect();
    }else {
      echo $response -> getMessage();
    }

  }catch(Exception $e) {
    echo $e -> getMessage();
  }
?>