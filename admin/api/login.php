<?php
  session_start();
  include('connection.php');

  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = $conn -> query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
  if($sql -> num_rows != 0){
    $_SESSION['logged'] = true;
    echo true;
  }else {
    echo 'not existing';
  }

  $conn -> close(); 
?>