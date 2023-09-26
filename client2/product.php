<?php
  session_start();
  include('api/connection.php');
  if (isset($_GET['id'])) {
    $productID = $_GET['id'];
    $reviewHTML = "";

    if(!isset($_SESSION['client'])){

    }else {
      $user = $conn -> query("SELECT * FROM user WHERE uid='".$_SESSION['client']."'");
      $user = $user -> fetch_assoc();

      $user_cart = $user['cart'];

      if($user_cart != "none"){
        $user_cart = json_decode($user_cart, true);
        if(array_key_exists($productID, $user_cart)){
          $product_value = $user_cart[$productID]['quantity'];
        }else {
          $product_value = 1;
        }
      }else {
        $product_value = 1;
      }
    }

    $sql = $conn -> query("SELECT * FROM product WHERE id='$productID'");

    if($sql -> num_rows != 0){
      $product = $sql -> fetch_assoc();

      if($product['review'] != 'none'){
        $reviews = json_decode($product['review'], true);

      }else {
        $reviews = 'none';
      }


      ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title><?php echo $product['name']; ?></title>
          <link rel="stylesheet" href="css/style.css" />
          <link rel="stylesheet" href="css/index.web.css" />
          <!-- <link rel="stylesheet" href="css/shop.web.css" /> -->
          <link rel="stylesheet" href="css/product.web.css" />
          <link rel="icon" type="image/x-icon" href="../assets/logo.png">
          <script src="js/jquery-3.6.4.min.js"></script>
          <script src="js/form.js"></script>
          <script src="../assets/sweetalert2@11.js"></script>
        </head>
        <body>
          <main>
            <header id="header-container">

            </header>
            <article>
              <div class="product-image">
                <div class="mb-4">
                  <a class="button is-link" href="shop.php">Continue Shopping</a>
                </div>
                <img src="https://jwsfurniture.website/<?php echo $product['product_photo']; ?>" />
              </div>
              <div class="product-meta">
                <div class="card mb-4 p-4 is-flex is-flex-direction-column">
                  <p class="is-size-5 mb-4"><?php echo $product['name']; ?></p>
                  <p class="is-size-3 has-text-weight-bold mb-5">₱ <?php echo number_format($product['price'], 2); ?></p>
                  <?php
                    if(!isset($_SESSION['client'])){
                    ?>
                    <div>
                      <span class="tag is-danger is-large">Please login if you want to add to your cart.</span>
                    </div>
                    <?php
                      }else {
                    ?>
                    <form id="orderForm" class="mb-5 is-flex is-flex-direction-row is-align-items-center is-flex-wrap-wrap">
                      <p class="is-size-6">Buy: </p>
                      <div class="add-to-cart mr-4">
                        <button type="button" class='decrease-button' onclick="decreaseInputValue()" disabled>-</button>
                        <input  name="quantity" type="number" max='<?php echo $product['stock']; ?>' class="input" value="<?php echo $product_value; ?>" disabled/>
                        <button type="button" class='increase-button' onclick="increaseInputValue()">+</button>
                      </div>
                      <input name="product_id" type="hidden" value="<?php echo $product['id']; ?>" />
                      <button type="submit" class="button is-link">
                        <span class="icon is-small">
                          <i class="fa-solid fa-cart-plus me-2"></i>
                        </span>
                        <span>Add to Cart</span>
                      </button>
                    </form>
                    <div class="mb-4 is-flex is-flex-direction-row is-align-items-center">
                      <div class="icon-text mr-5">
                        <span class="icon has-text-info me-3">
                          <i class="fas fa-box fa-xl"></i>
                        </span>
                        <span>Stock: <?php echo $product['stock']; ?></span>
                      </div>
                      <div class="icon-text">
                        <span class="icon has-text-info me-3">
                          <i class="fas fa-sack-dollar fa-xl"></i>
                        </span>
                        <span>Sold: <?php echo $product['sold']; ?></span>
                      </div>
                    </div>
                    <?php
                    }
                  ?>
                  <p class="mb-4 is-size-5 has-text-weight-bold">Product Description: </p>
                  <div>
                    <?php echo $product['description']; ?>
                  </div>
                  </div>   
              </div>
              <div class="review-form">
                <p class="is-size-4 has-text-weight-bold">Ratings and Review</p>
                <div class="review-container">
                  <?php
                    if($reviews == 'none'){
                      echo "<p class='is-size-6'>No available review to show.</p>";
                    }else {
                      foreach ($reviews as $key => $value) {
                        $avatar = $conn -> query("SELECT * FROM user WHERE uid='".$value['name']."'");
                        $avatar = $avatar -> fetch_assoc();

                        echo "
                          <div class='review'>
                            <div class='review-avatar'>
                              <img  src='".$avatar['photo_url']."' />
                            </div>
                            <div class='review-meta'>
                              <div class='meta-info'>
                                <p class='has-text-weight-bold is-size-6'>".$avatar['name']."</p>
                                <p class='is-size-7'>".date("F j, Y", strtotime($value['date']))."</p>
                              </div>
                              <p>
                                ".$value['comment']."
                              </p>
                            </div>
                          </div>
                        ";
                      }
                    }
                  ?>
                </div>
              </div>
            </article>
          </main>

          <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
          <script src="loader.js"></script>
          <script src="js/product.js">

          </script>
        </body>
        </html>
      <?php
    }else {
      header('location: shop.php');
    }
  } else {
    echo "No Selected Product";
  }
?>
