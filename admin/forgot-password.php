<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit']))
  {
    $contactno=$_POST['contactno'];
    $email=$_POST['email'];

        $query=mysqli_query($con,"select ID from tbladmin where  Email='$email' and MobileNumber='$contactno' ");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $_SESSION['contactno']=$contactno;
      $_SESSION['email']=$email;
     header('location:reset-password.php');
    }
    else{
  
      echo "<script>alert('Invalid Details. Please try again.');</script>";
    }
  }
  ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Admin Forgot Password | CTU DANAO Parking System</title>
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
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
      height: 100%;
      width: 100%;
      background: rgba(0,0,0,0.7);
    }
    .content {
      background-color:#ff9933;
      border-radius: 20px;
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 999;
      text-align: center;
      padding: 60px 32px;
      width: 370px;
      transform: translate(-50%, -50%);
    }

    .content:hover {
      opacity: 1;      
      box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
      rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
      rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .content header{
      color: white;
      font-size: 33px;
      font-weight: 600;
      margin: 0 0 35px 0;
      font-family: 'Montserrat',sans-serif;
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
    #x{
      margin-top:-2em;
      margin-left: 10em;
      color: white;
      font-weight: bold;
      text-shadow: 0px 6px 10px rgb(62, 57, 57);
      position: absolute;
    }
    #x:hover{
      color: red;
      text-decoration: none;
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
        color: black;
        cursor: pointer;
      border: 1px solid #2691d9;
      color: white;
      font-size: 18px;
      letter-spacing: 1px;
      font-weight: 600;
      font-family: 'Montserrat',sans-serif;
      border-radius: 10px;
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

   
    input[type="text"]:hover,
    input[type="password"]:hover {
        background-color: aliceblue;
        border: 2px solid orange;
    }
    
    #submitbtn{
      background-color: rgb(53, 97, 255);        
      color: white;
      border: solid ;
        cursor:pointer;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    #submitbtn:hover {
      background-color: darkblue;
      border: solid blue;
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
        border-radius: 10px; /* Adjust border-radius for a smaller look */
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
    #x{
      margin-left: 8.5em;
      margin-top: -1.2em;
      position: absolute;
    }
    .space{
      margin-top: 35px;
    }
}
    
    </style>
   
   
    </head>
   <body>
      <div class="bg-img">
         <div class="content">
         <div>
               <a href="index.php" id="x">
                X</a>
               </div>
         <a class="space" style="text-decoration:none;">
            <header >FORGOT PASSWORD</header> </a>
    <form method="post">
               <div class="field">
                  <span class="fa fa-user"></span>
                  <input type="text" class="form-control" name="email" placeholder="Email" autofocus required="true">
               </div>
               <div class="field space">
                  <span class="fa fa-lock"></span>
                  <input type="text" class="form-control" name="contactno" placeholder="Mobile Number" required="true">
               </div><br>
               <div class="field space">
                  <input type="submit"name="submit" name="submit" value="SUBMIT" id="submitbtn" style="text-align: center;">
               </div>
    </form>
  
         </div>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

   </body>
</html>
