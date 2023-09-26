<?php
  session_start();
  include('connection.php');

  $email = $_POST['logEmail'];
  $password = $_POST['logPassword'];

  $sql = $conn -> query("SELECT * FROM user WHERE email='$email' AND password='$password'");
  if($sql -> num_rows != 0){
    $user = $sql -> fetch_assoc();

    $_SESSION['client'] = $user['uid'];
    
    echo 1;
  }else {
    echo 0;
  }

  $conn -> close();
?>