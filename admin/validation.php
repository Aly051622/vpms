<?php
session_start();
require_once('includes/dbconnection.php'); // Include your database connection

// Turn on error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize email and expiration date from the form
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $expiration_date_input = filter_var($_POST['expiration_date'], FILTER_SANITIZE_STRING);

    // Validate the expiration date format
    $expiration_date = DateTime::createFromFormat('Y-m-d', $expiration_date_input);
    $current_date = new DateTime();

    if (!$expiration_date) {
        $_SESSION['error_message'] = "Invalid date format. Please enter the date in YYYY-MM-DD format.";
        header('Location: validation.php');
        exit();
    }

    // Check if the email exists in the database
    $email_check_query = "SELECT * FROM tblregusers WHERE Email = ?";
    $stmt = $con->prepare($email_check_query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // No user found with the provided email
        $_SESSION['error_message'] = "No user found with the provided email.";
        $stmt->close();
        header('Location: validation.php');
        exit();
    }

    // Determine validity and update the database
    try {
        // Check if the expiration date is in the past (expired) or in the future (valid)
        if ($expiration_date < $current_date) {
            // License expired
            $update_query = "UPDATE tblregusers SET validity = 0, expiration_date = ? WHERE Email = ?";
            $stmt_update = $con->prepare($update_query);
            $stmt_update->bind_param('ss', $expiration_date_input, $email);
            $stmt_update->execute();

            if ($stmt_update->affected_rows > 0) {
                $_SESSION['success_message'] = "License status updated to expired.";
                // Debugging: Check the redirect URL and session variable
                header('Location: invalidated.php');
            } else {
                $_SESSION['error_message'] = "Failed to update the driver's license status.";
                header('Location: validation.php');
            }
        } else {
            // License valid
            $update_query = "UPDATE tblregusers SET validity = 1, expiration_date = ? WHERE Email = ?";
            $stmt_update = $con->prepare($update_query);
            $stmt_update->bind_param('ss', $expiration_date_input, $email);
            $stmt_update->execute();

            if ($stmt_update->affected_rows > 0) {
                $_SESSION['success_message'] = "Driver's license is valid.";
                header('Location: validated.php');
            } else {
                $_SESSION['error_message'] = "Failed to update the driver's license status.";
                header('Location: validation.php');
            }
        }

        $stmt_update->close();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: validation.php');
        exit();
    }
    $stmt->close();
    $con->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
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
            text-align: center;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid gray;
            border-radius: 7px;
            font-size: 14px;
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
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
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
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success'>{$_SESSION['success_message']}</div>";
            unset($_SESSION['success_message']);
        }
        ?>
        <form action="validation.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>

            <label for="expiration_date">Driver's License Expiration Date:</label>
            <input type="date" id="expiration_date" name="expiration_date" required><br>

            <button type="submit" id="submit">Submit</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
