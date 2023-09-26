<?php
  session_start();
  include('connection.php');
  if ($_FILES['avatar_input']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['avatar_input']['tmp_name'];
    $targetPath = 'assets/profile-pictures/';
    $targetFile = $targetPath . $_FILES['avatar_input']['name'];
    $extension = pathinfo($targetFile, PATHINFO_EXTENSION);
    $file = $targetPath.$_SESSION['client'].'.'.$extension;
  
    if (move_uploaded_file($tempFile, '../assets/profile-pictures/' . $_SESSION['client'].'.'.$extension)) {

      $update = $conn -> query("UPDATE user SET photo_url='$file' WHERE uid='".$_SESSION['client']."'");
      if($update){
        echo 1;
      }
    } else {

    }
  } else {
  }

  $conn -> close();
?>