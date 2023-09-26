<?php
  session_start();
  include('connection.php');

  $transaction_id = $_SESSION['target_transaction'];

  $target = $_POST['target'];
  $review = $_POST['review'];

  $comment_object = [];

  $counter = 0;

  foreach ($target as $key => $value) {
    $get_item = $conn -> query("SELECT * FROM product WHERE id='".$target[$key]."'");
    $get_item = $get_item -> fetch_assoc();

    $old_item_review = [];

    $comment_object = array(
      'comment' => $review[$key],
      'name' => $_SESSION['client'],
      'date' => date('F j, Y, g:i a')
    );

    if($get_item['review'] == 'none' || $get_item['review'] == '[]'){
      array_push($old_item_review, $comment_object);
    }else {
      $old_item_review = json_decode($get_item['review'], true);
      array_push($old_item_review, $comment_object);
    }

    $comment_encoded = json_encode($old_item_review);

    $conn -> query("UPDATE product SET review='$comment_encoded' WHERE id='".$target[$key]."'");

    $counter++;
  }

  if(count($target) == $counter){
    $conn -> query("UPDATE transactions SET reviewed='true' WHERE reference_number='$transaction_id'");
    echo 1;
  }

  $conn -> close();

  unset($_SESSION['target_transaction']);
?>
