<!DOCTYPE html>
<?php
  session_start();
  include('api/connection.php');

  $order_id = $_GET['transaction_id'];
  $_SESSION['target_transaction'] = $order_id;
?>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Review</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="../assets/logo.png">
    <script src="js/jquery-3.6.4.min.js"></script>
    <script src="js/form.js"></script>
    <script src="../assets/sweetalert2@11.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="is-flex is-flex-direction-row mt-4 is-flex-wrap-wrap">
        <a href="shop.php" class="button is-link mr-2">Continue Shopping</a>
        <a href="cart.php" class="button mr-2">My Cart</a>
        <a href="user.php" class="button">My Profile</a>
      </div>
      <p class='is-size-4 has-text-weight-bold mb-4 mt-4'>Product Review</p>
      <form id='review_form' class="is-flex is-flex-direction-column">
        <?php
          $sql = $conn -> query("SELECT * FROM transactions WHERE reference_number='$order_id'");
          $sql = $sql -> fetch_assoc();

          $order_item = json_decode($sql['orders'], true);

          foreach ($order_item as $item) {
            $select_item = $conn -> query("SELECT * FROM product WHERE id='".$item['id']."'");

            while($row = $select_item -> fetch_array()){
              echo "
                <div style='align-content: stretch;' class='card p-4 mb-4 is-flex is-flex-direction-row is-flex-wrap-wrap'>
                  <figure class='image is-128x128'>
                    <img src='".$row['product_photo']."' />
                  </figure>
                  <div class='ml-4 is-flex is-flex-direction-column'>
                    <p class='is-size-5 has-text-weight-bold'>".$row['name']."</p>
                    <p class='is-size-5'>₱ ".number_format($row['price'], 2, '.', ',')."</p>
                    <p class='is-size-6'>Ordered: ".$item['quantity']."</p>
                    <p class='is-size-6 has-text-weight-medium'>Total: ₱ ".number_format(($item['quantity']*$row['price']), 2, '.', ',')."</p>
                  </div>
                  <div >
                    <input type='hidden' name='target[]' value='".$item['id']."' />
                    <textarea required type='text' name='review[]' class='textarea' rows='5' placeholder='Post something'></textarea>
                  </div>
                </div>
              ";
            }
          }
        ?>
        <div>
          <button class='button is-success' type="submit" >Submit Review</button>
        </div>
      </form>
    </div>

    <script>
      $('#review_form').on('submit', function(event){
        event.preventDefault();

        var formdata = $(this).serialize();

        $.ajax({
          type: 'post',
          url: 'api/postReview.php',
          data: formdata,
          success: response => {
            if(response == 1){
              location.href = 'user.php';
            }
          }
        })

      })
    </script>
  </body>
</html>
