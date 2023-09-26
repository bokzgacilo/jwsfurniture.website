<?php
  session_start();
  include('connection.php');

  $email = $_POST['regEmail'];
  $fullname = $_POST['regFullname'];
  $password = $_POST['regPassword'];
  $re_password = $_POST['reregPassword'];
  $digit5 = rand(10000, 99999);

  $uid = uniqid($digit5);

  if($password == $re_password){
    $checkEmail = $conn -> query("SELECT * FROM user WHERE email='$email'");
    if($checkEmail -> num_rows == 0){
      $insert = $conn -> query("
        INSERT INTO user(name, email, password, uid) VALUES(
          '$fullname',
          '$email',
          '$password',
          '$uid'
        )
      ");

      if($insert){
        $addAddress = $conn -> query("INSERT INTO address (uid) VALUES ('$uid')");
        echo 1; // Registered Successfully 
        $_SESSION['client'] = $uid;
      }
    }else {
      echo 2; // Email is already registered
    }
  }else {
    echo 5; // Password mismatched
  }

  $conn -> close();
?>