<?php
  session_start();
  include('../api/connection.php');


  if(isset($_SESSION['client'])){
    $getUser = $conn -> query("SELECT * FROM user WHERE uid='".$_SESSION['client']."'");
    $getUser = $getUser -> fetch_assoc();
  }
?>

<style>
  header {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    padding: 0.5rem 1rem;
    background-color: #604932;
  }
  /* Smaller screen */
  @media only screen and (max-width: 500px) {
    .brand {
      order: 1;
    }

    #searchForm{
      order: 3;
      width: 100%;
    }

    .action {
      order: 2;
    }
  }
  
  /* Bigger screen */
  @media only screen and (min-width: 500px) {
    .brand {
      margin-right: 4rem;
    }
  }
</style>

<div class="offcanvas offcanvas-end" tabindex="-1" id="register" aria-labelledby="registerLabel">
  <div class="offcanvas-header">
    <p class="title" id="registerLabel"></p>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="registerForm">
      <h4 class="title is-4 mb-2">Register</h4>
      <input type="text" class="input" name="regFullname" placeholder="Fullname (Lastname, Firstname)" />
      <input type="email" class="input" name="regEmail" placeholder="Email" />
      <input type="password" name="regPassword" class="input" placeholder="Password"/>
      <p class="password-error"></p>
      <input type="password" name="reregPassword" class="input" placeholder="Re-enter Password" />
      <p class="password-error"></p>
      <button class="button is-success w-100">Register</button>
    </form>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="account" aria-labelledby="accountLabel">
  <div class="offcanvas-header">
    <h5 class="title" id="accountLabel"></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="account-offcanvas-body">
    <form class="loginform">
      <h4 class="title is-4 mb-2">Login</h4>
      <input type="email" name="logEmail" placeholder="juandelacruz@gmail.com" class="input" />
      <input type="password" name="logPassword" placeholder="***********" class="input" />
      <button type="submit" class="button is-link w-100">Login</button>
      <p class="sub-title" id="error-login"></p>
      <a class="button" data-bs-toggle='offcanvas' href='#register' role='button' aria-controls='register'>Register</a>
      <button class="button is-ghost w-100">Forgot Password?</button>
    </form>
    <h6 class="mt-4 title is-size-5">Or log in as</h6>
    <a class="signas" href="api/facebook-login.php">
      <img src="../assets/icon/facebook.png" />
      Facebook
    </a>
    <a class="signas" href="api/google-login.php">
      <img src="../assets/icon/google.png" />
      Google
    </a>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="loggedAccount" aria-labelledby="loggedAccountLabel">
  <div class="offcanvas-header">
    <p class="is-size-5 has-text-weight-medium"><?php echo $getUser['name']; ?></p>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <a href="shop.php" class="button is-link mb-2">
      <span class="icon">
        <i class="fa-solid fa-bag-shopping"></i>
      </span>
      <span>Go Shopping</span>
    </a>
    <a href="cart.php" class="button mb-2">
      <span class="icon">
        <i class="fa-solid fa-cart-shopping"></i>
      </span>
      <span>My Cart</span>
    </a>
    <a href="user.php" class="button mb-2">
      <span class="icon">
        <i class="fa-solid fa-user"></i>
      </span>
      <span>My Profile</span>
    </a>
    <a class="button" href="api/logout.php">
      <span class="icon">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </span>
      <span>Logout</span>
    </a>
  </div>
</div>

<div class="brand is-flex is-flex-direction-row is-align-items-center">
  <figure class="image is-48x48 mr-4" id='brand-image'>
    <img class="is-rounded" src="../assets/logo.png">
  </figure>
  <a 
    href='shop.php' 
    style="color: #fff;
    text-decoration: none;" 
    class='is-size-4 has-text-weight-bold'>JWS FURNITURES</a>
</div>
<form id="searchForm" class="p-2">
  <input type="text" id="searchInput" name="q" placeholder="Search in JWS Furniture" >
  <button>
    <i class="fa-solid fa-magnifying-glass"></i>
  </button>
</form>
<div class="action ml-auto">
  <?php
    if(isset($_SESSION['client'])){
      echo "
        <a data-bs-toggle='offcanvas' href='#loggedAccount' role='button' aria-controls='loggedAccount' title='My Account'>
          <img src='".$getUser['photo_url']."' />
        </a>
      ";
    }else {
      echo "
        <a data-bs-toggle='offcanvas' href='#account' role='button' aria-controls='account' title='Account'>
          <i class='fa-solid fa-user'></i>
        </a>
      ";
    }
  ?>
</div>

<script>

$(document).on('click', '#brand-image', function(){
    location.href = 'https://jwsfurniture.website/client2/'
})
var params = new URL(document.location).searchParams;
  // For Login
$(document).on('submit', '#searchForm', function(event){
    event.preventDefault();
    console.log(currentURL)
    var searchword = $('#searchInput').val();
    inputParams.set('q', searchword);
    
    if(params.get('category') !== null){
         $.ajax({
            type: 'get',
            url: 'api/getAllProductsByCategory.php',
            data: {
              q: searchword,
              category: params.get('category')
            },
            beforeSend: () => {
              $('.product-container').html("<span class='mt-4 loader'></span>")
            },
            success: (response) => {
              if(response == 0){
                $('.product-container').load('component/product-not-found.html')
              }else {
                $('.product-container').html(`<div id='product-list'>${response}</div>`)
              }
            }
          })
    }else {
        $.ajax({
          type: 'get',
          url: 'api/search.php',
          data: {
            q: searchword
          },
          beforeSend: () => {
            $('.product-container').html("<span class='mt-4 loader'></span>")
          },
          success: (response) => {
            if(response == 0){
              $('.product-container').load('component/product-not-found.html')
            }else {
              $('.product-container').html(`<div id='product-list'>${response}</div>`)
            }
          }
        })
    }
    

    const path = window.location.href.split('?')[0];
    const newURL = `${path}?${inputParams}`;

    history.pushState({}, '', newURL);
  })
  
  $(document).on('submit', '.loginform', function(event){
    event.preventDefault();
    var serializedData = $(this).serialize();
    var statusCode = 0;
    $.ajax({
      type: 'post',
      url: 'api/loginUsingForm.php',
      data: serializedData,
      beforeSend: () => {

      },
      success: (response) => {
        statusCode = parseInt(response);
      },
      complete: () => {
        switch(statusCode){
          case 1:
            Swal.fire({
              title: 'Login Successfully',
              text: "Thank you for logging in",
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Proceed and reload the page.'
            }).then((result) => {
              location.reload();
            })
            break;
          case 0:
            $('#error-login').text('Invalid email or password. Please try again.')
            break;
        }
      }
    })
  })

  // For Register
  $(document).on('submit', '#registerForm', function(event){
    event.preventDefault();
    var serializedData = $(this).serialize();
    var statusCode = 0;
    $.ajax({
      type: 'post',
      url: 'api/register.php',
      data: serializedData,
      beforeSend: () => {

      },
      success: (response) => {
        statusCode = parseInt(response);
      },
      complete: () => {
        switch(statusCode){
          case 1:
            Swal.fire({
              title: 'Registered Successfully',
              text: "Thank you for registering.",
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Proceed and reload the page.'
            }).then((result) => {
              location.reload();
            })
            break;
          case 2:
            Swal.fire({
              title: 'Email is already registered',
              text: "The email you tried to registered with is already on the database. Please contact the administrator to verify your concern.",
              icon: 'error',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Try Again'
            }).then((result) => {
              location.reload();
            })
            break;
          case 5:
            $("input[name='regPassword']").addClass('is-danger');
            $("input[name='reregPassword']").addClass('is-danger');
            $('.password-error').text('Password not matching.')
            break;
        }
      }
    })
  })
  
  if(params.get('q') !== null){
    $('#searchInput').val(params.get('q'));
  }
</script>
