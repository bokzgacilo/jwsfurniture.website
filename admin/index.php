<?php
  session_start();

  if(!isset($_SESSION['logged'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login JWS Furniture Admin</title>
  <link rel="stylesheet" href="css/style.css" />
  <script src="js/jquery-3.6.4.min.js"></script>
</head>
<body>
  <style>
    body {
      display: grid;
      place-items: center;
    }

    form {
      margin-top: 5rem;
      width: 400px;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    form > img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      align-self: center;
    }
  </style>
    <form id="loginForm">
      <img src="../assets/logo.png"/>
      <p class="is-size-4 has-text-weight-bold mt-4">Admin Login</p>
      <input required class="input" name="username" type="text" placeholder="Username" />
      <input required class="input" name="password" type="password" placeholder="Password" />
      <button class="button is-link" type="submit">LOGIN</button>
    </form>
  </body>

  <script>
    $(document).ready(function(){
      $('#loginForm').submit(function(event){
        event.preventDefault();

        var data = {
          username: $("[name='username']").val(),
          password: $("[name='password']").val()
        }

        $.ajax({
          type: 'post',
          url: 'api/login.php',
          data: data,
          success: (response) => {
            console.log(response)

            if(response == 1){
              location.href = 'dashboard.php';
            }
            $('#loginForm')[0].reset();
          }
        })
      })
    })
  </script>
</html>

<?php
  }else {
    header('location: dashboard.php');
  }
?>