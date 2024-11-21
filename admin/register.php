<?php
session_start();
include('includes/dbconnection.php'); // Ensure this path is correct

if (isset($_POST['submit'])) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $contno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    
    // Check for duplicates (Email or Mobile Number)
    $stmt = mysqli_prepare($con, "SELECT Email FROM tblregusers WHERE Email=? OR MobileNumber=?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $contno);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<script>alert("This email or contact number is already associated with another account.")</script>';
    } else {
        // Insert data into tblregusers table
        $query = mysqli_prepare($con, "INSERT INTO tblregusers (FirstName, LastName, MobileNumber, Email, Password) VALUES (?, ?, ?, ?, ?)");
        
        if ($query) { // Check if the statement is prepared successfully
            mysqli_stmt_bind_param($query, "sssss", $fname, $lname, $contno, $email, $password);

            if (mysqli_stmt_execute($query)) {
                // Registration successful, redirect for verification
                $_SESSION['verification_email'] = $email; // Store email in session
                echo '<script>
                    alert(" A verification code has been sent to your email.");
                    window.location.href = "send_verification_code.php";
                </script>';
            } else {
                echo '<script>alert("Something went wrong. Please try again.")</script>';
            }

            mysqli_stmt_close($query);
        } else {
            echo '<script>alert("Failed to prepare the SQL statement.")</script>';
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>




<style>
   .success-dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #4CAF50;
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        z-index: 9999;
    }

    .success-message {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }
  </style>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Client Signup | CTU DANAO Parking System</title>
      <script src="js/signup.js"></script>

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
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
      <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
   <style>

@import url('https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Poppins:400,500&display=swap');
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      user-select: none;
    }
    .bg-img{
      background: url('images/ctuser.png');
      height: 100vh;
      background-size: cover;
      background-position: center;
      margin-top: -40px;
      overflow: hidden;
    }
    body{
      overflow: hidden;
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
  font-size: 15px;
  font-weight: 700;
  color: #222;
  display: none;
  cursor: pointer;
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
.submitbtn{
    border-radius: 9px;
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

#astyle{
    color: white;
}
#astyle:hover{
    color: blue;
}
.pull-left{
  color: white;
  font-family: 'Poppins',sans-serif;
}

.signup{
  font-size: 15px;
  color: white;
  font-family: 'Poppins',sans-serif;
}
.signup a{
  color: white;
  text-decoration: underline;
}
.signup a:hover{
  text-decoration: underline;
  color: blue;
}

input[type="text"]:hover, input[type="password"]:hover {
                background-color: aliceblue; 
                border: 2px solid #ffbe58; 
            }

#client:hover{
  background-color: #f7e791; /* Change the background color on hover */
            border: 2px solid #ffbe58; 
}

#client{
  height: 40px;
  border: none;
  background: transparent;
  font-family: 'Poppins',sans-serif;
  font-size: 16px;
}
.nextbtn{
      border: solid white;
    border-radius: 10px;
    padding: 10px;
    background-color: rgb(53, 97, 255);
        color: white;
        cursor: pointer;
        font-family: 'Montserrat',sans-serif;
    font-weight: bolder;
}

.nextbtn:hover{
    background-color: darkblue;
    border: solid blue;
}

.file-upload {
            display: none;
        }

        #userType option{
            color: black;
        }
        #registrationStatus option{
            color: black;
        }

        .form-group label{
            font-family: 'Montserrat',sans-serif;
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
    .fa{
      margin-top: 14px;
    }

/* Responsive adjustments */
@media (max-width: 768px) {
    .content {
        width: 80%; /* Adjust width for smaller screens */
        padding: 40px 24px; /* Reduce padding */
        border-radius: 15px; /* Adjust border-radius */
    }
}

@media (max-width: 500px) {
    .content {
        width: 90%; /* Further reduce width for very small screens */
        padding: 30px 20px; /* Further reduce padding */
        border-radius: 10px; /* Adjust border-radius for a smaller look */
        height: 550px;
        position: absolute;
    }
    #x{
      margin-left: 9.5em;
      margin-top: -1em;
      font-weight: bold;
      position: absolute;
    }
    .space{
      margin-top: 35px;
    }
}
    </style>
   
   
    </head>
    <body>
   <div style="text-align:center;margin-top:40px;">
      <div class="bg-img">
         <div class="content">
         <a href="admin/dashboard.php" id="x">
         <i class="fa-solid fa fa-xmark"></i></a>
         <a style="text-decoration:none;">
            <header>CREATE ACCOUNT</header> </a>

            <div class="login-form">
    <form method="post" action="" id="registrationForm" onsubmit="return checkpass();">
        <!-- Page 1 -->
        <div id="page1">
            <div class="form-group field space">
                <span class="fa bi bi-person-vcard-fill" style="font-size: 20px"></span>
                <input type="text" name="firstname" placeholder="Your First Name..." required class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-person-vcard" style="font-size: 20px"></span>
                <input type="text" name="lastname" placeholder="Your Last Name..." required class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-telephone-fill" style="font-size: 20px"></span>
                <input type="text" name="mobilenumber" maxlength="11" pattern="[0-9]{11}" placeholder="Mobile Number" required class="form-control">
            </div><br>
            <button type="button" onclick="nextPage('page2')" class="nextbtn" id="nextBtnPage1">
                Next <i class="bi bi-caret-right-square-fill"></i>
            </button>
        </div>

        <!-- Page 2 -->
        <div id="page2" style="display: none;">
            <div class="form-group field space">
                <span class="fa bi bi-person-fill" style="font-size: 20px"></span>
                <input type="email" name="email" placeholder="Email address" required class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-lock-fill" style="font-size: 20px"></span>
                <input type="password" name="password" id="password" placeholder="Enter password" required class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-shield-lock-fill" style="font-size: 20px"></span>
                <input type="password" name="repeatpassword" id="repeatpassword" placeholder="Repeat password" required class="form-control">
            </div>
            <div class="checkbox">
                <label class="pull-right">
                    <a href="forgot-password.php" id="astyle">Forgot Password?</a>
                </label>
                <label class="pull-left">
                    <a href="login.php" id="astyle">Sign in</a>
                </label><br>
            </div>
            <div>
                <input type="submit" name="submit" class="field submitbtn btn-success btn-flat m-b-30 m-t-30" id="submitBtn" value="REGISTER">
            </div><br>
            <button type="button" onclick="prevPage('page1')" class="nextbtn">
                <i class="bi bi-caret-left-square-fill"></i> Previous
            </button>
        </div>
    </form>
</div>

<script>
    let currentPage = 1;

    function nextPage(nextPageId) {
        const currentForm = document.getElementById(`page${currentPage}`);
        const nextForm = document.getElementById(nextPageId);

        if (nextForm) {
            currentForm.style.display = 'none';
            nextForm.style.display = 'block';
            currentPage++;
        }
    }

    function prevPage(prevPageId) {
        const currentForm = document.getElementById(`page${currentPage}`);
        const prevForm = document.getElementById(prevPageId);

        if (prevForm) {
            currentForm.style.display = 'none';
            prevForm.style.display = 'block';
            currentPage--;
        }
    }

    function checkpass() {
        const password = document.getElementById('password').value;
        const repeatPassword = document.getElementById('repeatpassword').value;

        if (password !== repeatPassword) {
            alert('Passwords do not match.');
            return false;
        }
        return true;
    }
</script>



</body>
</html>