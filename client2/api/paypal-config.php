<?php

  use Omnipay\Omnipay;

  $_SESSION['payment_method'] = "PAYPAL";

  define('CLIENT_ID', 'ATVpDNcYXa2GR6tH4s9_CYDbyakOsQsVK-Kpqfm1uaq8LbqNXHUOsLiPw1WKHfSiN3GE8mR6cvoCt3-Z');
  define('CLIENT_SECRET', 'ECjzH9GiV60kg-b3wVTTnqyXy1QBi7I2uphEwlwrUGPJrpv-DVETBJaq-zVNQwNSTc-anfJvyqVJQKOB');

  define('PAYPAL_RETURN_URL', 'http://jwsfurniture.website/client2/success.php'); 
  define('PAYPAL_CANCEL_URL', 'https://www.youtube.com'); 
  define('PAYPAL_CURRENCY', 'PHP'); 

  // Change not required 
  define('PAYPAL_URL', "https://sandbox.paypal.com");  

  $gateway = Omnipay::create('PayPal_Rest');
  $gateway -> setClientId(CLIENT_ID);
  $gateway -> setSecret(CLIENT_SECRET);
  $gateway -> setTestMode(true);
?>