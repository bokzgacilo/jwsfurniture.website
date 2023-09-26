<?php
  include('api/connection.php');
  session_start();
  

  if(!isset($_SESSION['client'])){
    header("location: shop.php");
  }

  $userID = $_SESSION['client'];

  $sql = $conn -> query("SELECT * FROM user WHERE uid='$userID'");

  $getAddress = $conn -> query("SELECT * FROM address WHERE uid='$userID'");
  $getAddress = $getAddress -> fetch_assoc();
  
  $user = $conn -> query("SELECT * FROM user WHERE uid='$userID'");
  $user = $user -> fetch_assoc();

  $cartHTML = "";
  $cartJSON = "";
  $cartCount = 0;
  $totalPrice = 0;
  
  $button_enabler = "";

  if($user['email'] == "not set" || $getAddress['block'] == "None" || $getAddress['contact'] == "None" || $getAddress['barangay'] == "None" || $getAddress['city'] == "None" || $getAddress['province'] == "None"){
    $button_enabler = "disabled";
  }
  

  while($row = $sql -> fetch_array()){
    $cart = $row['cart'];

    $cartJSON = json_decode($cart, true);

    if($cart != 'none'){
      $cartCount = count($cartJSON);

      foreach ($cartJSON as $value => $item) {
        $productQuery = $conn -> query("SELECT * FROM product WHERE id='".$item['id']."'");
        $product = $productQuery -> fetch_assoc();

        $cartHTML .= "
          <div class='p-2 order'>
            <a class='remove-item' onclick='removeFromCart(this.id)' id='".$item['id']."'>Remove</a>
            <div class='opd'>
              <img src='".$product['product_photo']."' />
              <div class='is-flex is-flex-direction-column is-justify-content-center'>
                <p class='is-size-6 has-text-weight-semibold'>".$product['name']."</p>
              </div>
            </div>
            <div class='quantity text-center'>
              <a onclick='minusQuantity(this.id)' id='".$item['id']."'>
                <i class='fa-solid fa-minus' ></i>
              </a>
              <p class='is-size-5'>".$item['quantity']."</p>
              <a onclick='plusQuantity(this.id)' id='".$item['id']."'a>
                <i class='fa-solid fa-plus' ></i>
              </a>
            </div>
            <p class='is-size-6 text-center'>₱ ".number_format($product['price'], 2, '.', ',')."</p>
            <p class='is-size-6 text-center'>₱ ".number_format(($product['price'] * $item['quantity']), 2, '.', ',')."</p>
          </div>
        ";

        $totalPrice += $product['price'] * $item['quantity'];
      }
    }else if($cart == '[]' || $cart == 'none'){
      $cartHTML .= "<p>No item in your cart.</p>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Cart</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/index.web.css">
  <link rel="stylesheet" href="css/cart.web.css">
  <link rel="icon" type="image/x-icon" href="../assets/logo.png">
  <script src="js/jquery-3.6.4.min.js"></script>
  <script src="js/cart-ui.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AXASf7DPSCDhFHr2S_QlnAFaKlkG4vzcG07zUMT2LFeuCwzoqKC2tw48tP-UUhufhKogkbBegUiYODH1&currency=PHP&components=buttons"></script>
</head>
<body>
  <main>
    <header id="header-container">

    </header>

    <article>
      <div class="shopping-cart">
        <div class="sc-header">
          <p class="is-size-4 has-text-weight-bold">Shopping Cart</p>
          <p class="is-size-5 has-text-weight-semibold"><?php echo $cartCount; ?> Items</p>
        </div>
        <div class="cart">
          <div class="my-order">
            <?php echo $cartHTML; ?>
          </div>
        </div>
      </div>
      <div class="order-summary p-4 is-flex is-flex-direction-column">
        <div class="card p-4 mb-4">
          <div class="mb-4 is-flex is-flex-direction-row is-align-items-center is-justify-content-space-between">
            <p class="is-size-5 has-text-weight-semibold">Address</p>
            <a href="user.php" class="button is-small">
              <span class="icon is-small">
                <i class="fa-solid fa-user-pen"></i>
              </span>
              <span>Edit Profile</span>
            </a>
          </div>
          <p class="is-size-6 has-text-weight-bold"><?php echo $row['name']; ?></p>
          <p class="is-size-6"><?php echo $getAddress['contact']; ?></p>
          <p class="is-size-6"><?php echo $getAddress['block'] . ', ' . $getAddress['street'] . ', ' . $getAddress['barangay']; ?></p>
          <p class="is-size-6"><?php echo $getAddress['city'] . ', ' . $getAddress['province']; ?></p>
        </div>
        <div class="is-flex is-flex-direction-row is-align-items-center is-justify-content-space-between">
          <p class="is-size-5 has-text-weight-medium">Total Cost</p>
          <p class="is-size-4 has-text-weight-bold">₱ <?php echo number_format($totalPrice, 2, '.', ','); ?></p>
        </div>
        <button class="mt-4 button is-success is-large js-modal-trigger" data-target="modal-js-example"
          <?php
            if($cartCount == 0 || $button_enabler == "disabled"){
              echo "disabled";
            }
          ?>
        >
          <span class="icon is-small">
            <i class="fa-solid fa-cash-register"></i>
          </span>
          <span>Proceed to Checkout</span>
        </button>
      </div>
    </article>
  </main>

  <div id="modal-js-example" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content w-50">
      <div class="box">
        <h4 class="is-size-4 has-text-weight-bold">Select Payment Method</h4>
        <div class="mt-4 is-flex is-flex-direction-row is-flex-wrap-wrap">
          <div class="card w-25">
            <div class="card-content">
              <form action="api/cod.php" method="post" class="is-flex is-flex-direction-column">
                <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>" />
                <input type="hidden" name="description" value="Testing Payment" />
                <img src="../assets/icon/cod.png" />
                <button type="submit" class="w-100 button">COD</button>
              </form>
            </div>
          </div>
          <div class="card w-25">
            <div class="card-content">
              <form id="GCashForm" action="api/xendit.php" method="post" class="content">
                <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>" />
                <input type="hidden" name="description" value="Testing Payment" />
                <img src="../assets/icon/gcash-fixed.png" />
                <button type="submit" class="w-100 button">GCash</button>
              </form>
            </div>
          </div>
          <div class="card w-25">
            <div class="card-content">
              <form action="api/paypal-charge.php" method="post" class="content">
                <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>" />
                <input type="hidden" name="description" value="Testing Payment" />
                <img src="../assets/icon/paypal-fixed.png" />
                <button type="submit" class="w-100 button">PayPal</button>
              </form>
            </div>
          </div>
          <div class="card w-25">
            <div class="card-content">
              <form action="api/maya.php" method="post" class="content">
                <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>" />
                <input type="hidden" name="description" value="Testing Payment" />
                <img src="../assets/icon/maya-fixed.png" />
                <button type="submit" class="w-100 button">Maya</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/cart.js"></script>
  <script src="loader.js"></script>
</body>
</html>
<?php
  }
?>