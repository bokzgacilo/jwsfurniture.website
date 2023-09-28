<?php
  $conn = new mysqli(
    "localhost",
    "root",
    "",
    "u335750608_jws"
  );

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
  }
?>