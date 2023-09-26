<?php 
  error_reporting(0);
  require '../../vendor/autoload.php';
  
	$fb = new Facebook\Facebook([
    'app_id' => '778477127194753',
    'app_secret' => '4923a5b47ed7927795dda43b95d82dbd',
    'default_graph_version' => 'v2.10',
	]);

  include('connection.php');

	$helper = $fb -> getRedirectLoginHelper();
  $accessToken = $helper -> getAccessToken();

  $responseUser = $fb -> get('/me?fields=id,name,link', $accessToken);
  $responseImage = $fb->get('/me/picture?redirect=false&type=large', $accessToken);
  $pictureData = $responseImage -> getDecodedBody();
  $pictureUrl = $pictureData['data']['url'];

  $user = $responseUser -> getGraphUser();

  $token = $accessToken -> getValue();
  $uid = $user -> getId();
  $name = $user['name'];

  $checkUser = $conn -> query("SELECT * FROM user WHERE uid='$uid'");

  if($checkUser -> num_rows == 0){
    // echo 'new user';
    $sql = "INSERT INTO user(name, uid, photo_url, email) VALUE(
      '$name',
      '$uid',
      '$pictureUrl',
      'Email is private.'
    )";

    $result = $conn -> query($sql);

    if($result){
      echo 1;
    }else {
      echo 0;
    }
  }else {
    // echo 'existing';
    echo 2;
  }
  
  header("location: ../facebook-login-page.php?id=$uid");

  $conn -> close();
?>