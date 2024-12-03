<?php
session_start();
include 'includes/dbconnection.php';

// Display any error messages
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
}

// Display important details if available
if (isset($_SESSION['email']) && isset($_SESSION['license_date'])) {
    $email = htmlspecialchars($_SESSION['email']); // Escape output for security
    $license_date = htmlspecialchars($_SESSION['license_date']); // Escape output for security

    echo "<h2>Submitted Details</h2>";
    echo "<p>Email: $email</p>";
    echo "<p>Driver's License Expiration Date: $license_date</p>";
}

// Display extracted text from Tesseract
if (isset($_SESSION['extracted_text'])) {
    echo "<h3>Extracted Text from Image:</h3>";
    echo "<pre>" . htmlspecialchars($_SESSION['extracted_text']) . "</pre>"; // Display extracted text
    unset($_SESSION['extracted_text']); // Clear the session variable
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Update Driver's License | CTU Danao Parking System</title>
    <style>
        body {
            background: whitesmoke;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .breadcrumbs {
            padding: 15px;
        }
        .breadcrumbs h1 {
            color: black;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;            
            width: 500px;
            margin: 20px auto; 
        }
        h2 {
            font-family: 'Poppins', sans-serif;
            color: black;
            font-size: 30px;
            margin-left: 12em;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid gray;
            border-radius: 7px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"]:hover,
        input[type="date"]:hover {
            background-color: whitesmoke;
            box-shadow: rgba(3, 102, 214, 0.3) 0px 0px 0px 3px;
        }
        #submit {
            width: 100%;
            padding: 10px;
            border-radius: 9px;
            background-color: rgb(53, 97, 255);
            color: white;
            font-weight: bold;
            border: 2px solid white;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #submit:hover {
            background-color: darkblue;
            border: 2px solid darkblue;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Driver's License Validation</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="validation.php">Validation</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-5">Update Driver's License</h2>
    <div class="container">
        <form action="upload.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>

            <label for="license_date">Enter Expiration Date:</label>
            <input type="date" id="license_date" name="license_date" required><br>

            <button type="submit" id="submit">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
