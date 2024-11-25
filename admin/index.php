
<?php
session_start();
include('../DBconnection/dbconnection.php');

if (isset($_POST['login'])) {
    $adminuser = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    // Secure prepared statement
    $stmt = $con->prepare("SELECT ID, AdminName FROM tbladmin WHERE UserName = ? AND Password = ?");
    $stmt->bind_param("ss", $adminuser, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['vpmsaid'] = $admin['ID']; // Admin ID
        $_SESSION['admin_name'] = $admin['AdminName']; // Optional: Admin name
        header('location: dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }

    $stmt->close();
}
$con->close();
?>




<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Admin Login | CTU DANAO Parking System</title>
      <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">

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
      background: url('images/ctuad.png');
      height: 100vh;
      background-size: cover;
      background-position: center;
    }
    .bg-img:after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      height: 100vh;
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
    transform: translate(-50%, -50%);
    background-color:#ff9933;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, 
    rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, 
    rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
  }

  .content:hover {
      opacity: 1;
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
      cursor: pointer;
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
      cursor: pointer;
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
      border-radius: 10px;
    }
    .field input[type="submit"]:hover{
      background-color:white;
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

    input[type="text"]:hover, input[type="password"]:hover {
                background-color: aliceblue; 
                border: 2px solid #ffbe58; 
            }

    #home {
      position: absolute;
        margin-left: 4.6em;
        margin-top: 20px;
        background-color: rgb(53, 97, 255);
        border-radius: 10px;
        cursor: pointer;
        border: solid;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    #home:hover{
        background-color: darkblue;
        border: solid blue;
        border-radius: 10px;
    }
    #loginbtn{
      background-color: rgb(53, 97, 255);        
      color: white;
      border: solid ;
        cursor:pointer;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    #loginbtn:hover {
      background-color: darkblue;
      border: solid blue;
    }

    /*bag o ni nga loading*/
    #loading-spinner {
        margin-top: 20px;
        display: none;
        color: white;
      }
      /* Responsive adjustments */
@media (max-width: 768px) {
    .content {
        width: 80%; /* Adjust width for smaller screens */
        padding: 40px 24px; /* Reduce padding */
        border-radius: 15px; /* Adjust border-radius */
    }
}

@media (max-width: 480px) {
    .content {
        width: 85%; /* Further reduce width for very small screens */
        padding: 30px 20px; /* Further reduce padding */
        border-radius: 20px; /* Adjust border-radius for a smaller look */
        height: 450px;
    }
    .bg-img:after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      height: 100vh;
      width: 100%;
      background: rgba(0,0,0,0.7);
    }
    #home{
      margin-left: 3.5em;
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
         <a style="text-decoration:none;">   
         <header>A D M I N &nbsp; LOGIN</header> </a>
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
               <a href="../welcome.php" class="btn btn-primary space" id="home">
                <span class="glyphicon glyphicon-home"></span> Home</a>
                <div class="space"></div>
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
