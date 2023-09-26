<?php
  use Xendit\Xendit;
  session_start();
  include('api/connection.php');
  require '../vendor/autoload.php';

  $user = $conn -> query("SELECT * FROM user WHERE uid='".$_SESSION['client']."'");
  $user = $user -> fetch_assoc();

  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $reference_id = '';
  
  for ($i = 0; $i < 16; $i++) {
    $reference_id .= $characters[rand(0, strlen($characters) - 1)];
  }

  if(isset($_SESSION['maya_reference_number'])){
    $reference_id = $_SESSION['maya_reference_number'];
  }

  if(isset($_SESSION['gcash_reference_number'])){
    $reference_id = $_SESSION['gcash_reference_number'];
  }

  if(isset($_SESSION['cod_reference_number'])){
    $reference_id = $_SESSION['cod_reference_number'];
  }
  
  $user_orders = $user['orders'];
  $cart_order = $user['cart'];
  
  $order_array = [];
  $array_imploded = '';

  if($user_orders == 'none'){
    array_push($order_array, $reference_id);
    $array_imploded = json_encode($order_array);
  }else {
    $orders = json_decode($user_orders, true);
    array_push($orders, $reference_id);
    $array_imploded = json_encode($orders);
  }

  function make_changes_to_stock($reference_number) {
    $user_cart = $GLOBALS['cart_order'];

    $user_cart = json_decode($user_cart, true);

    foreach ($user_cart as $key => $value) {
      $item = $GLOBALS['conn'] -> query("SELECT * FROM product WHERE id='".$value['id']."'"); 
      $item = $item -> fetch_assoc();

      $stock = $item['stock'];
      $sold = $item['sold'];

      $new_stock = ($stock - $value['quantity']);
      $new_sold = ($sold + $value['quantity']);

      $GLOBALS['conn'] -> query("UPDATE product SET stock=$new_stock, sold=$new_sold WHERE id='".$value['id']."'");
    }
    
    $ga = $GLOBALS['conn'] -> query("SELECT * FROM address WHERE uid='".$_SESSION['client']."'");
    $ga = $ga -> fetch_assoc();
    $complete_address = $ga['block'].", ".$ga['street'].", ".$ga['barangay'].", ".$ga['city'].", ".$ga['province'];

    $GLOBALS['conn'] -> query("INSERT INTO production (reference_number, client, address, orders) VALUES(
      '$reference_number',
      '".$_SESSION['client']."',
      '$complete_address',
      '".$GLOBALS['cart_order']."'
    )");

    // Updating Transaction Status
    $GLOBALS['conn'] -> query("UPDATE user SET orders='". $GLOBALS['array_imploded']."' WHERE uid='".$_SESSION['client']."'");

    $GLOBALS['conn'] -> query("UPDATE user SET cart='none' WHERE uid='".$_SESSION['client']."'");
  }

  switch($_SESSION['payment_method']){
    case 'COD' :
        make_changes_to_stock($reference_id);
      break;
    case 'GCASH' : 
      if(!isset($_SESSION['payment_status'])){
        header("location: cart.php");
      }
      $client = new \GuzzleHttp\Client();

      $response = $client->request('GET', 'https://api.paymongo.com/v1/checkout_sessions/' . $_SESSION['transaction_id'], [
        'headers' => [
          'accept' => 'application/json',
          'authorization' => 'Basic c2tfdGVzdF9pYXloeE1wMXY3VFVBcU00ZnhZeUh0YVg6',
        ],
      ]);

      $response_json = json_decode($response -> getBody(), true);
    
      if($response_json['data']['attributes']['payments'][0]['attributes']['status'] == 'paid'){
        $reference_id = $_SESSION['gcash_reference_number'];
        $conn -> query("UPDATE transactions SET status='SUCCEEDED' WHERE reference_number='$reference_id'");
        make_changes_to_stock($reference_id);
      }

      break;
    case 'MAYA':
      $status = "SUCCEEDED";

      $sql = $conn -> query("INSERT INTO transactions(transaction_id,mode,reference_number, checkout_url, amount, orders, status) VALUES(
        '".$_SESSION['maya_checkout_id']."',
        'MAYA',
        '".$_SESSION['maya_reference_number']."',
        '".$_SESSION['maya_href']."',
        '".$_SESSION['maya_amount']."',
        '$cart_order',
        '$status'
      )");
      
      make_changes_to_stock($_SESSION['maya_reference_number']);

      break;
    case 'PAYPAL':
      require_once('api/paypal-config.php');

      if(array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)){
        $transaction = $gateway -> completePurchase(array(
          'payer_id' => $_GET['PayerID'],
          'transactionReference' => $_GET['paymentId']
        ));

        $response = $transaction -> send();
        
        if($response -> isSuccessful()){
          $arr_body = $response -> getData();

        //   print_r($arr_body);

          if($arr_body['state'] == 'approved'){
            $transaction_id = $arr_body['id'];
            $reference_number = $reference_id;
            $amount = $arr_body['transactions'][0]['amount']['total'];
            $status = "SUCCEEDED";
            $checkout_url = "none";
            
            $sql = $conn -> query("INSERT INTO transactions(transaction_id,mode, reference_number, checkout_url, amount, orders, status) VALUES(
              '$transaction_id',
              'PAYPAL'
              '$reference_number',
              '$checkout_url',
              '$amount',
              '$cart_order',
              '$status'
            )");

            make_changes_to_stock($reference_number);
          }
        }else {
          echo $response -> getMessage();
        }
      }else { 
        echo 'Transaction is declined';
      }
      break;
  }
?>

<!DOCTYPE html>
<html>
    <head>
      <!-- <title><?php echo $_SESSION['payment_method']; ?> - Payment Successful</title> -->
      <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
      <div style="display: grid; place-items: center;" class="mt-6">
        <figure class="image" style="width: 200px; height: 200px;">
          <img src="../assets/icon/transaction-successful.avif" />
        </figure>
        <p class="is-size-4 mb-4 has-text-weight-bold">Payment Successful</p>
        <div>
          <a class="button is-link mt-2" href="../client2/shop.php">Back to Shopping</a>
        </div>
      </div>
    </body>
  </html>

<?php
  unset($_SESSION['maya_checkout_id']);
  unset($_SESSION['maya_reference_number']);
  unset($_SESSION['gcash_reference_number']);
  unset($_SESSION['cod_reference_number']);
  unset($_SESSION['maya_amount']);
  unset($_SESSION['maya_href']);
?>