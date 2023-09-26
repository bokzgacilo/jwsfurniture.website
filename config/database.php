<?php
  $conn = new mysqli(
    "109.106.254.1",
    "u335750608_adminjws",
    "1nd3p3nd3T",
    "u335750608_jws"
  );

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
  }
?>