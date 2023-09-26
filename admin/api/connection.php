<?php
  $conn = new mysqli(
    "localhost",
    "u194725231_jws_user",
    "x3]E0/dS",
    "u194725231_jws"
  );
  
  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
  }
?>