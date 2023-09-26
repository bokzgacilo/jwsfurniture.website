<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop - JWS Furnitures</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/shop.web.css">
  <link rel="stylesheet" href="css/index.web.css">
  <link rel="icon" type="image/x-icon" href="../assets/logo.png">
  <script src="js/jquery-3.6.4.min.js"></script>
  <script src="../assets/sweetalert2@11.js"></script>
</head>
<body>
<div id='message-us' class="p-4">
    <a href='https://www.messenger.com/t/100092144012686' target="_blank" class="button is-link">Message Us</a>
  </div>
  <style>
    #message-us {
      z-index: 5;
      display: flex;
      position: fixed;
      bottom: 0;
      right: 0;
    }
  </style>
  <main>
    <header id="header-container">

    </header>
    <div class="category-list">

    </div>
    <article class="p-4">
      <div class="product-container">
        <div id="product-list" >
          
        </div>
      </div>
    </article>
  </main>

  <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/shop.js"></script>
  <script src="loader.js"></script>
</body>
</html>