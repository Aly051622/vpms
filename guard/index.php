<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../DBconnection/dbconnection.php');

if (isset($_POST['login'])) {
    $guarduser = $_POST['username'];
    $hashed_password = hash('sha512', $_POST['password']);
    
    // Query to check the username and hashed password using mysqli
    $query = mysqli_query($con, "SELECT ID, UserName FROM tblguard WHERE UserName='$guarduser' AND Password='$hashed_password'");
    $ret = mysqli_fetch_array($query);
    
    if ($ret) {
        // Set session for guard ID
        $_SESSION['guardid'] = $ret['ID'];

        // Redirect based on the username
        if ($guarduser == 'inguard') {
            header('Location: monitor.php');
            exit();
        } elseif ($guarduser == 'outguard') {
            header('Location: monitor2.php');
            exit();
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
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Security Login | CTU DANAO Parking System</title>
      <link rel="apple-touch-icon" href="../images/ctu.png">
      <link rel="shortcut icon" href="../images/ctu.png">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
         /* Your existing styles remain unchanged */
      </style>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const loginForm = document.querySelector('form');
            const loadingSpinner = document.querySelector('#loading-spinner');
            const successMessage = document.querySelector('#success-message');

            loginForm.addEventListener('submit', function() {
                loadingSpinner.style.display = 'inline-block';
            });
        });
      </script>
   </head>
   <body>
      <div class="bg-img">
         <div class="content">
            <a style="text-decoration:none;">
               <header>S E C U R I T Y &nbsp; LOGIN</header>
            </a>
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
               <div class="field btn-success">
                  <input type="submit" name="login" value="LOGIN" id="loginbtn">
               </div>
               <div id="loading-spinner" class="fa fa-spinner fa-spin fa-3x"></div>
               <a href="../welcome.php" class="btn btn-primary" id="home">
                  <span class="glyphicon glyphicon-home"></span> Home
               </a>
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
   </body>
</html>
