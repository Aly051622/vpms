<?php
session_start();
require '../vendor/autoload.php';
require 'includes/dbconnection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationCode($email) {
    $newCode = rand(100000, 999999);
    $_SESSION['verification_code'] = $newCode;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'developershalcyon@gmail.com';
        $mail->Password = 'uhdv sagp oljc smwm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('developershalcyon@gmail.com', 'Parking Verification');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "<p>Your verification code is: <strong>$newCode</strong></p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error sending email: " . $mail->ErrorInfo);
        return false;
    }
}

function saveAccount($email) {
    global $con;
    $query = "UPDATE tblregusers SET status='active' WHERE Email=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    return $stmt->execute();
}

if (isset($_GET['resend'])) {
    if (isset($_SESSION['verification_email'])) {
        $email = $_SESSION['verification_email'];
        if (sendVerificationCode($email)) {
            echo json_encode(['success' => true, 'message' => 'A new code has been sent to your email.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send the code. Please try again.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email address not found.']);
    }
    exit;
}

if (isset($_POST['submit'])) {
    $userInputCode = $_POST['verification_code'];

    if (!isset($_SESSION['verification_code'])) {
        echo json_encode(['success' => false, 'message' => 'Verification code not found.']);
        exit;
    }

    if ($_SESSION['verification_code'] == $userInputCode) {
        if (saveAccount($_SESSION['verification_email'])) {
            unset($_SESSION['verification_code'], $_SESSION['verification_email'], $_SESSION['attempts']);
            echo json_encode(['success' => true, 'message' => 'Account verified. Redirecting...']);
            header('Location: dashboard.php');
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the account.']);
        }
    } else {
        $_SESSION['attempts'] = $_SESSION['attempts'] ?? 0;
        $_SESSION['attempts']++;

        if ($_SESSION['attempts'] >= 3) {
            echo json_encode(['success' => false, 'message' => 'Maximum attempts reached. Please sign up again.']);
            unset($_SESSION['attempts']);
            header('Location: signup.php');
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect code. Please try again.']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Verify Account | CTU DANAO Parking System</title>
      <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head>

<style>

/* General Styling */
    .bg-img{
      background: url('images/ctuser.png');
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
    .content header{
      color: white;
      font-size: 33px;
      font-weight: bold;
      margin: 0 0 35px 0;
      font-family: 'Montserrat',sans-serif;
    }
form {
    border-radius: 20px;
      position: absolute;
      height: 370px;
      top: 50%;
      left: 50%;
      z-index: 999;
      text-align: center;
      padding: 40px 32px;
      width: 350px;
      transform: translate(-50%, -50%);
      background-color:#ff9933;
      box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 2px solid #ddd;
    border-radius: 7px;
}

input[type="text"]:hover {
                background-color: whitesmoke;
                border: 2px solid lightblue;
            }

input[type="submit"] {
    border-radius: 9px;
      background-color: rgb(53, 97, 255);        
      color: white;
      border: solid ;
        cursor:pointer;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
}
input[type="submit"]:hover{
      background-color: darkblue;
      border: solid blue;

    }
h3{
    font-weight: bold;
}
.pull-left{
    margin-top: 1em;
    color: white;
}
#resendButton{
    color: white;
}
#resendButton:hover{
    color: blue;
}
.field{
      position: relative;
      height: 40px;
      width: 100%;
      display: flex;
      background: rgba(255,255,255,0.94);
      border-radius: 7px;
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
      color: black;
      font-size: 14px;
      font-family: 'Poppins',sans-serif;
    }
    .space{
      margin-top: 16px;
    }
p {
    text-align: center;
    margin-top: -10px;
    color: white;
    font-weight: bold;
}

a{
      color: white;
      text-decoration: none;
      font-family: 'Poppins',sans-serif;
    }
a{
      color: blue;
      text-decoration: underline;
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

/* Responsive Design */
@media (max-width: 768px) {
    form {
        width: 80%;
    }
}

@media (max-width: 500px) {
    form {
        width: 90%;
        padding: 15px;
    }
    input[type="text"], input[type="submit"] {
        padding: 8px;
    }
}

@media (max-width: 468px) {
    form {
        width: 95%;
    }
    p {
        font-size: 14px;
    }
    input[type="text"], input[type="submit"] {
        padding: 6px;
    }
}

    </style>
<body>
<div class="bg-img">
<div class="content">
               <a href="index.php" id="x">
               <i class=" fa fa-xmark"></i></a>
      
    <form method="POST" action="">
        <header><h3> SEND VERIFICATION</h3></header>
        <br>
        <p>An OTP has been sent to your Gmail account. Please check your inbox</p>
        <input type="text" name="verification_code" required placeholder="Enter verification code">
        <input type="submit" name="submit" value="VERIFY" class="field space btn-success">
            <p class="pull-left">Didn’t receive a code? 
             <a href="#" id="resendButton">Resend</a>
            </p>
    </form>
</div>
</div>
</body>
</html>


