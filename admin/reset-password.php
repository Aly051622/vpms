<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
error_reporting(0);

if(isset($_POST['submit']))
  {
    $contactno=$_SESSION['contactno'];
    $email=$_SESSION['email'];
    $password=md5($_POST['newpassword']);

        $query=mysqli_query($con,"update tbladmin set Password='$password'  where  Email='$email' && MobileNumber='$contactno' ");
   if($query)
   {
echo "<script>alert('Password successfully changed');</script>";
session_destroy();
   }
  
  }
  ?>
<!doctype html>
 <html class="no-js" lang="">
<head>
<meta charset="utf-8">
    <title>Reset Page | CTU DANAO Parking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
<script type="text/javascript">
function checkpass()
{
if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{
alert('New Password and Confirm Password field does not match');
document.changepassword.confirmpassword.focus();
return false;
}
return true;
} 

</script>

<style>
      body{
        font-weight: bold;
      background: url('images/ctuser.png');
      height: 100vh;
      background-size: cover;
      background-position: center;
      opacity: 0.9;
    }
    .bg-dark:after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(0,0,0,0.7);
      z-index: -5;
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
         .card, .card-header, {
            border-radius: 20px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
         }
        
    input[type="text"]:hover,
    input[type="password"]:hover {
        background-color: aliceblue;
        border: 2px solid orange;
    }
         .btn{  
        border-radius: 7px;    
        background-color: rgb(53, 97, 255);        
        color: white;
         border: solid ;
        cursor:pointer;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .btn:hover{
        background-color: darkblue;
        border: solid blue;
    }
    
    #x{
      margin-top:-2em;
      margin-left: 9em;
      color: white;
      font-weight: bold;
      text-shadow: 0px 6px 10px rgb(62, 57, 57);
      position: absolute;
    }
    #x:hover{
      color: red;
      text-decoration: none;
    }
    .pull-right{
        color:white;
        font-weight: bold;
    }
    .pull-right:hover{
        color: blue;
        text-decoration: underline;
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
        height: 400px;
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
<body class="bg-dark">
    <div class="content">
        <div> <a href="forgot-password.php" id="x">
                X</a>
        </div>
               <a class="space" style="text-decoration:none;">
                       <h2 style="color: white;">R E S E T <br> PASSWORD</h2>
                    </a>
                <div>
                    <form action="" method="post" name="changepassword" onsubmit="return checkpass();">
                         <p style="font-size:16px; color:red" align="center"> <?php if($msg){
    echo $msg;
  }  ?> </p>
                       <div class="form-group field space">
                       <span  class="fa bi bi-lock-fill"></span>
                           <input type="password" class="form-control" name="newpassword" placeholder="New Password" required="true">
                        </div>
                        <div class="form-group field space">
                        <span class="fa bi bi-shield-lock-fill"></span>
                            <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password" required="true">
                        </div>
                        <div class="checkbox">
                            
                            <label class="pull-right">
                                <a href="index.php" style="color: white;">Signin</a>
                            </label><br>

                        </div>
                        <div class="pull-left space">
                        <button type="submit" name="submit" class="btn btn-success btn-flat m-b-30 m-t-30" style="color: white; font-weight: bold;"><i class="fa fa-refresh"> Reset</i></button>
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
