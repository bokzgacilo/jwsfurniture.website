<?php
  session_start();
  include('connection.php');

  unset($_SESSION['logged']);
  
  header("location: ../");

  $conn -> close(); 
?>