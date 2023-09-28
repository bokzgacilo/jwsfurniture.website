<?php
  session_start();
  //Import PHPMailer classes into the global namespace
  //These must be at the top of your script, not inside a function
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  //Load Composer's autoloader
  require '../../../vendor/autoload.php';
  include('../connection.php');


  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  $verificationNumber = rand(100000,999999);
  $email = $_POST['logEmail'];
  $password = $_POST['logPassword'];

  $sql = $conn -> query("SELECT * FROM user WHERE email='$email' AND password='$password'");
  if($sql -> num_rows != 0){
    $user = $sql -> fetch_assoc();

    try {
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail-> Username   = 'jwssupport@jwsfurniture.website';                     //SMTP username
      $mail->Password   = '1nd3p3nd3nT!';                               //SMTP password
      $mail->SMTPSecure = "ssl";         //Enable implicit TLS encryption
      $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom('jwssupport@jwsfurniture.website', 'Mailer');
      $mail->addAddress($email, $user['name']);     //Add a recipient

      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Login Verification';
      $mail->Body    = "This is your verification code in order to login: <strong>$verificationNumber</strong>";

      if($mail -> send()){

        $sql = $conn -> query("UPDATE user SET verification_code='$verificationNumber' WHERE email='$email'");
        $_SESSION['temp_email'] = $email;
        $displayStatus = "flex";
      }
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    // echo 1;
  }else {
    $displayStatus = "none";
    echo "
      <main style='display: grid; place-items: center; '>
        <p class='is-size-4 mt-4 mb-2'>Username and password is incorrect.</p>
        <a class='button is-link mt-4' href='../../'>Back to Homepage</a>
      </main>
    ";
  }

  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification Code</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="icon" type="image/x-icon" href="../../../assets/logo.png">
</head>
<body>
  <style>
    body {
      display: grid;
      place-items: center;
    }

    main {
      display: <?php echo $displayStatus; ?>;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 500px;
      padding-top: 10%;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 100%;
    }
  </style>
  <main>
    <p class="is-size-4 mb-4">A code was sent to your email: <strong><?php echo $_POST['logEmail']; ?></strong></p>
    <form action="verifyCode.php" method="post">
      <input required class="input" type="text" name="verificationCode" placeholder="XXXXXX"/>
      <button class="button is-link" type="submit">Verify Code</button>
    </form>
  </main>
</body>
</html>


