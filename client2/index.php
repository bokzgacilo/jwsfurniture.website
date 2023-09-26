<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JWS Furniture</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/index.web.css">
  <link rel="icon" type="image/x-icon" href="../assets/logo.png">
  <script src="../assets/sweetalert2@11.js"></script>
  <link rel="stylesheet" type="text/css" href="../assets/slick-1.8.1/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="../assets/slick-1.8.1/slick/slick-theme.css"/>
  <script src="js/jquery-3.6.4.min.js"></script>
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


    section {
      height: 91vh;
      scroll-margin-top: 12vh;
    }

    .home-pics > img {
      width: 300px;
      height: 150px;
      object-fit: cover;
    }

    header {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      padding: 0.5rem 1rem;
      background-color: #604932;
    }

    .new-product-description > div > * {
      color: #fff !important;
    }

    .carousel-image {
      width: 600px;
      height: auto;
      object-fit: cover;
      background-color: white;
    }
    /* Smaller screen */
    @media only screen and (max-width: 500px) {

    }
  </style>
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

  <main>
    <header>
      <div class="brand is-flex is-flex-direction-row is-align-items-center mr-auto">
        <figure class="image is-48x48 mr-4">
          <img class="is-rounded" src="../assets/logo.png">
        </figure>
        <a 
          href='index.php' 
          style="color: #fff;
          text-decoration: none;" 
          class='is-size-4 has-text-weight-bold'>JWS FURNITURES</a>
      </div>
      <div id='navigator' class="is-flex is-flex-direction-row is-align-items-center">
        <a class="mr-2 button" href="#about">Home</a>
        <a class="mr-2 button" href="#services">About Us</a>
        <form id="searchForm" class="p-2" method="get" action="shop.php">
          <input type="text" id="searchInput" name="q" placeholder="Search in JWS Furniture" >
          <button>
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
        <a class="mr-4 button is-link" href="shop.php">Go Shopping</a>
        <?php
          session_start();
          include('api/connection.php');

          if(isset($_SESSION['client'])){
            $getUser = $conn -> query("SELECT * FROM user WHERE uid='".$_SESSION['client']."'");
            $getUser = $getUser -> fetch_assoc();
          }

          if(isset($_SESSION['client'])){
            echo "
              <a data-bs-toggle='offcanvas' href='#loggedAccount' role='button' aria-controls='loggedAccount' title='My Account'>
                <figure class='image is-48x48'>
                  <img class='is-rounded' src='".$getUser['photo_url']."' />
                </figure>  
              </a>
            ";
          }else {
            echo "
              <a 
                style='color: #fff; 
                display: grid;
                place-items: center;
                background-color: gray; 
                width: 48px;
                height: 48px;
                
                ' data-bs-toggle='offcanvas' href='#account' role='button' aria-controls='account' title='Account'>
                <i class='fa-solid fa-user fa-xl'></i>
              </a>
            ";
          }
        ?>
      </div>
    </header>
    <div class="is-flex is-flex-direction-column">
      
      <section 
        style="
          color: #fff;
          background-color: #3d2d1e;" 
        class="is-flex is-flex-direction-column is-justify-content-center"
        >
        <p class="is-size-2 has-text-weight-bold">New Arrival</p>
        <div class="new-product-carousel w-75 p-4">
          <?php
            include('api/connection.php');

            $get_new_product = $conn -> query("SELECT * FROM product LIMIT 7 ");
            
            while($row = $get_new_product -> fetch_array()){
              $formattedAmount = number_format($row['price'], 2, '.', ',');
              $formattedAmountWithSymbol = 'PHP ' . $formattedAmount;

              echo "
                <div class='is-flex is-flex-direction-row is-align-items-center is-justify-content-center'>
                  <div 
                    style='color: #fff; width: 20%'
                  class='is-flex is-flex-direction-column p-4 mr-4'>
                    <p class='is-size-3 has-text-weight-bold mb-4'>".$row['name']."</p>
                    <div>
                    <span class='tag is-success mt-4 mb-4'>".$row['category']."</span>
                    
                    </div>
                    <div class='new-product-description is-size-5 mb-4'>".$row['description']."</div>
                    <p class='is-size-3 has-text-weight-bold mb-4 mt-6'>$formattedAmountWithSymbol</p>
                    <a href='product.php?id=".$row['id']."' class='button is-link'>Buy Now</a>
                  </div>
                  <div class='is-flex is-flex-direction-column ml-4'>
                    <img class='carousel-image' src='https://jwsfurniture.website/".$row['product_photo']."' />
                  </div>
                </div>
              ";
            }
            
          ?>
        </div>
      </section>
      <section id="about" class="is-flex is-flex-direction-column is-justify-content-center">
         <figure class="mt-6 image mr-4 is-128x128">
          <img src="../assets/logo.png" />
        </figure>
        <p class="is-size-5 has-text-weight-medium mb-4">We offer made to order ordinary and designs Panel Doors, jambs ,slots and Louvers</p>
        <p class="is-size-4 has-text-weight-bold mb-4">Opening Hours</p>
        <ul>
          <li>Monday: 08:00 - 19:00</li>
          <li>Tuesday: 08:00 - 19:00</li>
          <li>Wednesday: 08:00 - 19:00</li>
          <li>Thursday: 08:00 - 19:00</li>
          <li>Friday: 08:00 - 19:00</li>
          <li>Saturday: 08:00 - 19:00</li>
          <li>Sunday: 08:00 - 19:00</li>
        </ul>
      </section>
      <section id="services" class="is-flex is-flex-direction-column">
        <p class="mt-6 is-size-4 has-text-weight-bold mb-4">Services Offered</p>
        <div class="home-pics is-flex is-flex-direction-row is-align-items-center is-justify-content-center">
          <img src="home-pics/about (1).jpg" />
          <img src="home-pics/about (2).jpg" />
          <img src="home-pics/about (3).jpg" />
        </div>
        <div class="mb-4 home-pics is-flex is-flex-direction-row is-align-items-center is-justify-content-center">
          <img src="home-pics/about (4).jpg" />
          <img src="home-pics/about (5).jpg" />
          <img src="home-pics/about (6).jpg" />
        </div>
        <div class="content is-flex is-flex-direction-column is-align-items-center">
          <li class="is-size-4 has-text-weight-medium">Carpentry Services</li>
          <li class="is-size-4 has-text-weight-medium">Home Decorations</li>
          <li class="is-size-4 has-text-weight-medium">Building Material Store</li>
        </div>
      </section>
      <section id="location" class="is-flex is-flex-direction-column">
      <p class="mt-5 mb-6 is-size-4 has-text-weight-bold">Map Location</p>
        <div class="mapouter">
          <div class="gmap_canvas">
            <iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=500&amp;hl=en&amp;q=justin woodsash trading&amp;t=p&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
              <a href="https://connectionsgame.org/">Connections NYT</a>
            </div>
            <style>
              .mapouter{
                position: relative;
                text-align: right;
                width: 100%;
                height: inherit;
              }
              
              .gmap_canvas {
                overflow: hidden;
                background:none !important;
                width: 100%;
                height: inherit;
              }
              
              .gmap_iframe {
                height:inherit !important
              }
              </style>
          </div>
      </section>
    </div>
  </main>
  <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="loader.js"></script>
  <script>
      // For Login
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

      // function openAddressModal() {
      //   $('#addressModal').modal('show')
      // }

      if(location.search !== ''){
          $('#searchInput').val(localStorage.getItem('q'));
      }
  </script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.new-product-carousel').slick({
        autoplay: true
      })
    })

    
  </script>
</body>
</html>