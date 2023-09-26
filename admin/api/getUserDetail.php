<?php
  include('connection.php');

  $uid = $_GET['uid'];

  $sql = $conn -> query("SELECT * FROM user WHERE uid='$uid'");
  $address = $conn -> query("SELECT * FROM address WHERE uid='$uid'");
  $user = $sql -> fetch_assoc();
  $address = $address -> fetch_assoc();
  
  $photo_url = '';

  if (filter_var($user['photo_url'], FILTER_VALIDATE_URL) !== false) {
    $photo_url = $user['photo_url'];
  } else {
    $photo_url = 'https://jwsfurniture.website/client2/' . $user['photo_url'];
  }

  echo "
    <figure class='image is-128x128x mb-4'>
      <img width='128' height='128' src='$photo_url' />
    </figure>
    <p class='is-size-4 has-text-weight-bold'>".$user['name']."</p>
    <p class='is-size-6'>".$user['email']."</p>
    <p class='is-size-5 mt-4'>Change Password</p>
    <form action='api/changePassword.php' method='post' class='is-flex is-flex-direction-row'>
      <input type='hidden' value='".$user['uid']."' name='uid' />
      <input class='input is-small' type='text' value='".$user['password']."' name='password' />
      <button class='button is-success is-small'>Change</button>
    </form>


    <p class='is-size-5 mt-4'>Address Line</p>
    <p class='is-size-6'>".$address['contact']."</p>
    <p class='is-size-6'>".$address['block']." , ".$address['street']."</p>
    <p class='is-size-6'>".$address['barangay']." , ".$address['city']."</p>
    <p class='is-size-6'>".$address['province']."</p>
  ";

  $conn -> close();
?>