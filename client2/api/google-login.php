<?php
  session_start();
  require_once '../../vendor/autoload.php';
  include('connection.php');
  $client = new Google\Client();
  $client -> setAuthConfig('google-client.json');
  
  // DEVELOPMENT
//   $client -> setRedirectUri('http://localhost/jws-furniture/client2/api/google-login.php');
  // LIVE
   $client -> setRedirectUri('https://jwsfurniture.website/client2/api/google-login.php');


  $client -> addScope('profile');
  $client -> addScope('email');

  if(isset($_GET['code'])) {
    $token = $client -> fetchAccessTokenWithAuthCode($_GET['code']);

    $google_service = new Google_Service_Oauth2($client);

    $userData = $google_service -> userinfo -> get();
    
    $uid = $userData['id'];
    $email = $userData['email'];
    $name = $userData['name'];
    $picture = $userData['picture'];
    $password = '12345';

    $checkUser = $conn -> query("SELECT * FROM user WHERE uid='$uid'");
    $_SESSION['authenticated'] = true;

    if($checkUser -> num_rows == 0){
      $sql = "INSERT INTO user(name, uid, password, photo_url, email) VALUE(
        '$name',
        '$uid',
        '$password',
        '$picture',
        '$email'
      )";

      $result = $conn -> query($sql);

      if($result){
        $addAddress = $conn -> query("INSERT INTO address (uid) VALUES ('$uid')");

        $_SESSION['client'] = $uid;
      }else {
        echo 0;
      }
    }else {
      $_SESSION['client'] = $uid;
    }
  }else {
    header("location:" . $client -> createAuthUrl());
  }

  $conn -> close();
?>

<?php
  if(isset($_SESSION['authenticated'])){
    unset($_SESSION['authenticated']);
    header("location: ../shop.php");
  }
?>

