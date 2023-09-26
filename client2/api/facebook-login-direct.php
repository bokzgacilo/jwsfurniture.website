<?php 
  session_start();
  include('connection.php');

  $userID = $_GET['id'];
  $sql = $conn -> query("SELECT * FROM user WHERE id='$userID'");
  $row = $sql -> fetch_assoc();

  if($row){
    $_SESSION['client'] = $row['uid'];
    
    header('location: ../index.php?message=Logged in Successfully');
  }
  
  $conn -> close();
?>