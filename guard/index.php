<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('../DBconnection/dbconnection.php');

if (isset($_POST['login'])) {
    $guarduser = $_POST['username'];
    // Hash the password using SHA2 (256-bit)
    $hashed_password = hash('sha256', $_POST['password']);
    
    // Query to check the username and hashed password
    $query = mysqli_query($conn, "SELECT ID, UserName FROM tblguard WHERE UserName='$guarduser' AND Password='$hashed_password'");
    $ret = mysqli_fetch_array($query);

    if ($ret) {
        // Set session for guard ID
        $_SESSION['guardid'] = $ret['ID'];

        // Redirect based on the username
        if ($guarduser == 'inguard') {
            header('Location: monitor.php');
        } elseif ($guarduser == 'outguard') {
            header('Location: monitor2.php');
        } else {
            echo "<script>alert('Invalid Guard Username.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Details.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Security Login | CTU DANAO Parking System</title>
      <link rel="apple-touch-icon" href="../images/ctu.png">
    <link rel="shortcut icon" href="../images/ctu.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Poppins:400,500&display=swap');
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      user-select: none;
    }
    .bg-img{
      background: url('../guard/images/sud.jpg');
      height: 100vh;
      background-size: cover;
      background-position: center;
    }
    .bg-img:after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(0,0,0,0.7);
    }
    .content {
    border-radius: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 999;
    text-align: center;
    padding: 60px 32px;
    width: 370px;
    opacity: 0.7;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.04);
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
  }

  .content:hover {
      opacity: 1;
      background-image: linear-gradient(316deg, #f94327 0%, #ff7d14 74%);
      box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px,
          rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
          rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
  }

  .content header {
      color: white;
      font-size: 33px;
      font-weight: 600;
      margin: 0 0 35px 0;
      font-family: 'Montserrat', sans-serif;
  }
    .field{
      position: relative;
      height: 45px;
      width: 100%;
      display: flex;
      background: rgba(255,255,255,0.94);
      border-radius: 10px;
    }
    .field span{
      color: black;
      width: 40px;
      line-height: 45px;
    }
    .field input{
      height: 100%;
      width: 100%;
      background: transparent;
      border: none;
      outline: none;
      color: #222;
      font-size: 16px;
      font-family: 'Poppins',sans-serif;
    }
    .space{
      margin-top: 16px;
    }
    .show{
      position: absolute;
      right: 13px;
      font-size: 13px;
      font-weight: 700;
      color: #222;
      display: none;
      font-family: 'Montserrat',sans-serif;
    }
    .pass-key:valid ~ .show{
      display: block;
    }
    .pass{
      text-align: left;
      margin: 10px 0;
    }
    .pass a{
      color: white;
      text-decoration: none;
      font-family: 'Poppins',sans-serif;
    }
    .pass:hover a{
      text-decoration: underline;
    }
    .field input[type="submit"]{
      background: orange;
      border: 1px solid #2691d9;
      color: white;
      font-size: 18px;
      letter-spacing: 1px;
      font-weight: 600;
      font-family: 'Montserrat',sans-serif;
      border-radius: 10px;
    }
    .field input[type="submit"]:hover{
      background-color: #1b8b00;
      background-image: linear-gradient(314deg, #1b8b00 0%, #a2d240 74%);
    }
    .login{
      color: white;
      margin: 20px 0;
      font-family: 'Poppins',sans-serif;
    }
    .signup{
      font-size: 15px;
      color: white;
      font-family: 'Poppins',sans-serif;
    }
    .signup a{
      color: #3498db;
      text-decoration: none;
    }
    .signup a:hover{
      text-decoration: underline;
    }

    header{
        border-bottom: 2px solid ;    
    }

    input[type="text"]:hover, input[type="password"]:hover {
                background-color: #f7e791; 
                border: 2px solid #ffbe58; 
            }

    #home {
        margin: 2vw 0 0 15vw; /* Adjusted margin for responsiveness */
        background-color: red;
        border-radius: 10px;
    }
    #home:hover{
        background-color: #1b8b00;
        background-image: linear-gradient(314deg, #1b8b00 0%, #a2d240 74%);
        color: black;
        border-radius: 10px;
        box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, 
        rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, 
        rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
    }
    #loginbtn{
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        color: white;
    }
    #loginbtn:hover {
      background-color: #1b8b00;
      background-image: linear-gradient(314deg, #1b8b00 0%, #a2d240 74%);
      color: white;
      box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, 
      rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, 
      rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
    }

    /*bag o ni nga loading*/
    #loading-spinner {
        display: none;
        color: orange;
      }

      /* Adjust styles for devices with a maximum width of 600px */
@media only screen and (max-width: 300px) {
  .content {
        width: 90%; /* Adjusted width for smaller screens */
        padding: 40px 20px; /* Adjusted padding for smaller screens */
        margin: 2vw 0 0 15vw; /* Adjusted margin for responsiveness */
    }

    .content header {
        font-size: 24px; /* Adjusted font size for smaller screens */
        margin-bottom: 20px; /* Adjusted margin for smaller screens */
    }

    .field span {
        width: 8vw;
        line-height: 8vw;
    }

    .field input {
        font-size: 6vw;
    }

    .space {
        margin-top: 4vw;
    }

    .show {
        right: 2vw;
        font-size: 6vw;
    }

    .pass {
        margin: 2vw 0;
    }

    .login {
        margin: 4vw 0;
        color: black;
    }

    .signup {
        font-size: 5vw;
    }

    #home {
        margin: 4vw 0 0 8vw;
    }

    input[type="text"]:hover,
    input[type="password"]:hover {
        background-color: #f7e791;
        border: 2px solid #ffbe58;
    }
    #home {
        margin: 2vw 0 0 8vw; /* Adjusted margin for smaller screens */
    }
}
    </style>
   <script>
  document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form');
    const loadingSpinner = document.getElementById('loading-spinner');

    loginForm.addEventListener('submit', function() {
      // Show the loading spinner when the form is submitted
      loadingSpinner.style.display = 'inline-block';
    });
  });
</script>
   
   
    </head>
   <body>
      <div class="bg-img">
         <div class="content">
         <a href="../welcome.php" style="text-decoration:none;">   
         <header>S E C U R I T Y &nbsp; LOGIN</header> </a>
    <form method="post">
               <div class="field">
                  <span class="fa fa-user"></span>
                  <input class="form-control" type="text" placeholder="Username" required="true" name="username">
               </div>
               <div class="field space">
                <span class="fa fa-lock"></span>
                <input type="password" id="password" class="form-control" name="password" placeholder="Password" required="true">
                <span class="show" id="show-password"><i class="fas fa-eye-slash"></i></span>
  </div>
               <div class="pass">
               <a href="forgot-password.php">Forgot Password?</a>
               </div>
               <div class="field">
                  <input type="submit"name="login" value="LOGIN" id="loginbtn">
               </div>
               <div id="loading-spinner" class="fa fa-spinner fa-spin fa-3x"></div>
               <a href="../welcome.php" class="btn btn-primary" id="home">
                <span class="glyphicon glyphicon-home"></span> Home</a>
   
              </form>
              <div class="loading-spinner" id="loading-spinner" style="display: none;"></div>
<div class="success-message" id="success-message" style="display: none;">Login successful!</div>
         </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
      const passField = document.querySelector('#password');
const showBtn = document.querySelector('#show-password');

showBtn.addEventListener('click', function() {
  if (passField.type === "password") {
    passField.type = "text";
    showBtn.innerHTML = '<i class="fas fa-eye"></i>'; 
    showBtn.style.color = "red";
  } else {
    passField.type = "password";
    showBtn.innerHTML = '<i class="fas fa-eye-slash"></i>'; 
    showBtn.style.color = "black"; 
  }
});

const loginForm = document.querySelector('#login-form');
  const loadingSpinner = document.querySelector('#loading-spinner');
  const successMessage = document.querySelector('#success-message');

  loginForm.addEventListener('submit', function (e) {
    e.preventDefault();

    // Display the loading spinner for 5 seconds
    loadingSpinner.style.display = 'block';
    setTimeout(function () {
      loadingSpinner.style.display = 'none';

      // Hide the form and show the success message
      loginForm.style.display = 'none';
      successMessage.style.display = 'block';
    }, 2000);

    // Simulate a successful login - you can replace this with your actual login code
    setTimeout(function () {
  successMessage.innerHTML = 'Login successful! Redirecting...';
  successMessage.style.color = 'white'; // Add this line to set text color to white


      // Redirect to the dashboard after 5 seconds
      setTimeout(function () {
        window.location.href = 'dashboard.php';
      }, 2000);
    }, 2000);
  });
</script>

   </body>
</html>