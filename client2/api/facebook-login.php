<?php
  //initialize facebook sdk
  require 'facebook/vendor/autoload.php';
  include('connection.php');

  session_start();

  $fb = new Facebook\Facebook([
    'app_id' => '778477127194753', // Replace {app-id} with your app id
    'app_secret' => '4923a5b47ed7927795dda43b95d82dbd', // Replace {app_secret} with your app secret
    'default_graph_version' => 'v2.5',
  ]);

  $helper = $fb -> getRedirectLoginHelper();
  $permissions = ['email']; // optional

  try {
    if (isset($_SESSION['facebook_access_token'])) {
      $accessToken = $_SESSION['facebook_access_token'];
    } else {
      $accessToken = $helper->getAccessToken();
    }
  } catch(Facebook\Exceptions\facebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e -> getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
      $fb -> setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
      // getting short-lived access token
      $_SESSION['facebook_access_token'] = (string) $accessToken;
      // OAuth 2.0 client handler
      $oAuth2Client = $fb->getOAuth2Client();
      // Exchanges a short-lived access token for a long-lived one
      $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
      $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
      // setting default access token to be used in script
      $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }

    try {
      $profile_request = $fb -> get('/me?fields=name,first_name,last_name,email');
      $requestPicture = $fb -> get('/me/picture?redirect=false&height=200'); //getting user picture
      $picture = $requestPicture -> getGraphUser();
      $profile = $profile_request -> getGraphUser();
      $fbid = $profile -> getProperty('id');           // To Get Facebook ID
      $fbfullname = $profile -> getProperty('name');   // To Get Facebook full name
      
      $checkuid = $conn -> query("SELECT * FROM user WHERE uid='$fbid'");

      if($checkuid -> num_rows == 0){
        $insert = $conn -> query("
          INSERT INTO user(name, email, password, uid, photo_url) VALUES(
            '$fbfullname',
            'not set',
            '12345',
            '$fbid',
            '".$picture['url']."'
          )
        ");

        if($insert){
          $addAddress = $conn -> query("INSERT INTO address (uid) VALUES ('$fbid')");
          
          $_SESSION['client'] = $fbid;
          header("location: ../shop.php");
        }
      }else {
        $_SESSION['client'] = $fbid;
        header("location: ../shop.php");
      }

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      session_destroy();
      // redirecting user back to app login page
      header("Location: ../");

      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
  } else {
    // replace  website URL same as added in the developers.Facebook.com/apps e.g. if you used http instead of https and used            
    $loginUrl = $helper -> getLoginUrl('https://jwsfurniture.website/client2/api/facebook-login.php', $permissions);
    header("location: $loginUrl");
  }
?>