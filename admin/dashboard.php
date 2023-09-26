<?php
  session_start();

  if(isset($_SESSION['logged'])){
?>

<!DOCTYPE html>
<html lang="en" style = 'overflow-y:hidden'>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
  <title>JWS Furniture - Admin</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/index.web.css">
  <link rel="stylesheet" href="../assets/rich-text-editor/src/richtext.min.css">

  <script src="js/jquery-3.6.4.min.js"></script>
  <script src="../assets/rich-text-editor/src/jquery.richtext.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../assets/sweetalert2@11.js"></script>
</head>
<body>
  <aside>
    <h4 class="has-text-weight-bold is-size-4 mb-4">Dashboard</h4>
    <a name="users">
      <i class="fa-solid fa-user"></i>
      <p>Users</p>
    </a>
    <a name="products">
      <i class="fa-solid fa-list"></i>
      <p>Products</p>
    </a>
    <a name="inventory">
      <i class="fa-solid fa-warehouse"></i>
      <p class="is-size-6">Inventory</p>
    </a>
    <a name="transaction">
      <i class="fa-solid fa-money-bill"></i>
      <p>Transaction and Sales</p>
    </a>
    <a name="order">
      <i class="fa-solid fa-box"></i>
      <p>Orders</p>
    </a>
    <a href="api/logout.php">
      <p>Logout</p>
    </a>
  </aside>

  <main id="root">
    <h2>Main</h2>
  </main>
  <script src="../assets/bootstrap/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function(){
      if(location.hash){
        var sitehash = location.hash.split("#");
        $(`[name='${sitehash[1]}']`).addClass('active')

        $('#root').load(`views/${sitehash[1]}.php`);

      }else {
        $("[name='products']").addClass('active')
        $('#root').load(`views/products.php`);
      }
      
      $('a').on("click", function(){
        $('a').removeClass('active');

        let view_name = $(this).attr('name');

        location.hash = view_name;
        $(`[name='${view_name}']`).addClass('active')
        $('#root').load(`views/${view_name}.php`);
      })
    })
  </script>
</body>
</html>

<?php
  }else {
    header('location: index.php');
  }
?>