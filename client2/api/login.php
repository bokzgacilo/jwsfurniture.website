<?php
  session_start();

  $_SESSION['client'] = 33;

  header("location: ../index.php?message=Successfully Logged In");
?>