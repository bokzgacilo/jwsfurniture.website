<?php
  session_start();
  include('connection.php');
  
  if(isset($_POST['email'])){
    $check_email = $conn -> query("SELECT * FROM user WHERE email='".$_POST['email']."'");
    
    if($check_email -> num_rows != 0){
      echo 3;
      exit();
    }else {
      $conn -> query("UPDATE user SET email='".$_POST['email']."' WHERE uid='".$_SESSION['client']."'");
    }
  }
  
  $update = $conn -> query("UPDATE address SET
          contact='".$_POST['contact']."',
          block='".$_POST['block']."',
          street='".$_POST['street']."',
          barangay='".$_POST['barangay']."',
          city='".$_POST['city']."',
          province='".$_POST['province']."' WHERE uid='".$_SESSION['client']."'");
  if($update){
    echo 1;
  }else {
    echo 2;
  }

  $conn -> close();
?>