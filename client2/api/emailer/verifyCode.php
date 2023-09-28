<?php
  session_start();
  include('../connection.php');
  $verificationCode = $_POST['verificationCode'];
  
  $temp_email = $_SESSION['temp_email'];

  $sql = $conn -> query("SELECT * FROM user WHERE email='$temp_email'");
  $user = $sql -> fetch_assoc();

  $status = "not logged";

  if($verificationCode == $user['verification_code']){
    $_SESSION['client'] = $user['uid'];
    $status = "logged";
  }else {
    header("location: ../../");
    exit();

    session_destroy();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logged In Successfully</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="icon" type="image/x-icon" href="../../../assets/logo.png">
</head>
<body>
  <style>
    main {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding-top: 10%;
    }
  </style>
  <main>
    <p class="is-size-3 has-text-weight-bold">Logged In Successfully</p>
    <a class="button is-link is-large mt-4" href="../../shop.php">Go Shopping!</a>
  </main>
</body>
</html>
