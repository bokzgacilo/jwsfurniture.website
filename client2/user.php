<?php
  session_start();
  include('api/connection.php');

  if(!isset($_SESSION['client'])){
    header("location: shop.php");
  }

  $user = $conn -> query("SELECT * FROM user WHERE uid='".$_SESSION['client']."'");
  $user = $user -> fetch_assoc();

  $address = $conn -> query("SELECT * FROM address WHERE uid='".$user['uid']."'");
  $address = $address -> fetch_assoc();

  $userOrder = [];

  if($user['orders'] != 'none'){
    $userOrder = json_decode($user['orders'], true);
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $user['name']; ?> - Profile</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/user.web.css">
  <link rel="icon" type="image/x-icon" href="../assets/logo.png">
  <script src="js/jquery-3.6.4.min.js"></script>
  <script src="../assets/sweetalert2@11.js"></script>
  <script src="js/user-ui.js"></script>
</head>
<body>
  <div id="order-viewer" class="modal">
    <div class="modal-background"></div>

    <div class="modal-content">
      <div class="box">
        <div id='order-body'>

        </div>
      </div>
    </div>

    <button class="modal-close is-large" aria-label="close"></button>
  </div>

  <div id="password-modal" class="modal">
    <div class="modal-background"></div>

    <div class="modal-content">
      <div class="box">
        <p class='is-size-4 has-text-weight-bold mb-4'>Change Password</p>
        <form action='api/changepassword.php' method='post'>
          <input name='password' type='text' class='input w-100 mb-4' value='<?php echo $user['password']; ?>'/>
          <button type='submit' class='button is-primary'>Change Password</button>
        </form>
      </div>
    </div>

    <button class="modal-close is-large" aria-label="close"></button>
  </div>

  <main>
    <div class="profile-header">
      <a class="button is-link me-2" href="shop.php">Back to Shopping</a>
        <a class="button me-2" href="cart.php">My Cart</a>
      <a class="button js-modal-trigger" data-target="password-modal">Change Password</a>
    </div>
    <div class="basic card">
      <form id="avatarForm" enctype="multipart/form-data" class="avatar">
        <img id="avatar-preview" src="<?php echo $user['photo_url']; ?>" />
        <div class="file">
          <label class="file-label w-100">
            <input class="file-input" accept="image/*" type="file" name="avatar_input">
            <span class="file-cta w-100">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Choose a file…
              </span>
            </span>
          </label>
        </div>
      <button id="change-avatar-button" type="submit" class="button is-success" disabled>Change Avatar</button>

      </form>
      <div class="basic-meta">
        <h4 class="is-size-3 has-text-weight-semibold" ><?php echo $user['name'];?></h4>
        <p><?php echo $user['email'];?></p>
        <p><?php echo $address['contact'];?></p>

        <div class="mt-4 title-address">
          <p class="is-size-4 has-text-weight-bold">Delivery Address</p>
          <a class="button is-small js-modal-trigger" data-target="modal-js-example">Edit</a>
        </div>
        <p><?php echo $address['block'];?></p>
        <p><?php echo $address['street'];?></p>
        <p><?php echo $address['barangay'];?></p>
        <p><?php echo $address['city'];?></p>
        <p><?php echo $address['province'];?></p>
      </div>
    </div>
    <p class="is-size-4 has-text-weight-bold">Orders <?php echo count($userOrder); ?></p>
    <div class="order-table">
      <?php
        if($user['orders'] != "none"){
          foreach ($userOrder as $order) {
            $select_order = $conn -> query("SELECT * FROM transactions WHERE reference_number='$order'");
            $show_status = $conn -> query("SELECT * FROM production WHERE client='".$user['uid']."' AND reference_number='$order'");
            $show_status = $show_status -> fetch_assoc();

            while($row = $select_order -> fetch_array()){
              $itemCount = count(json_decode($row['orders'], true));

              echo "
                <div class='order card pt-2 pb-2 pl-4 pr-4 mb-2'>
                  <div>
                    <a class='is-size-6 has-text-weight-medium order-id' id='$order'>$order</a>
                    <p class='is-size-7'>".date("F j, Y, g:i a", strtotime($row['date_created']))."</p>
                  </div>
                  <div>
                    <p>₱ ".number_format($row['amount'], 2, '.', ',')." <span class='is-size-7'>$itemCount item/s </span></p>
                  </div>
                  <div>
                    <p class='is-size-7'>".$show_status['status_description']."</p>";

                    if($row['reviewed'] == 'true'){
                      echo "
                        <span class='tag is-medium'>Reviewed</span>
                      ";
                    }else {
                      echo "
                        <a href='review_page.php?transaction_id=".$order."' class='button is-link is-small'>Submit Review</a>
                      ";
                    }

                  echo "</div>
                </div>
              ";
            }
          }
        }
      ?>
    </div>
  </main>

  <div id="modal-js-example" class="modal">
  <div class="modal-background"></div>
  <div class="modal-content">
      <div class="box" id="address-modal-content">
        <p class="is-size-4">Edit Address</p>
        <form id="addressForm">
          <p class="is-size-6">Email</p>
          <?php
            if($user['email'] != 'not set'){
              echo "
                <input type='text' class='input' value='".$user['email']."' readonly />
              ";
            }else {
              echo "
                <input type='text' name='email' class='input' value='".$user['email']."' />
              ";
            }
          ?>

          <p class="is-size-6">Contact Number</p>
          <input type="text" name="contact" class="input" value="<?php echo $address['contact']; ?>"/>
          <p class="is-size-6">House Number/ Block/ Lot</p>
          <input type="text" name="block" class="input" value="<?php echo $address['block']; ?>"/>
          <p class="is-size-6">Street</p>
          <input type="text" name="street" class="input" value="<?php echo $address['street']; ?>"/>
          <p class="is-size-6">Barangay</p>
          <input type="text" name="barangay" class="input" value="<?php echo $address['barangay']; ?>"/>
          <p class="is-size-6">City</p>
          <input type="text" name="city" class="input" value="<?php echo $address['city']; ?>"/>
          <p class="is-size-6">Province</p>
          <input type="text" name="province" class="input" value="<?php echo $address['province']; ?>"/>
          <button class="button is-success" type="submit">Update Address</button>
        </form>
      </div>
    </div>

    <button class="modal-close is-large" aria-label="close"></button>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/user.js"></script>
</body>
</html>
