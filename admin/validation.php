<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
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
        /* Add your styles here */
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
    <h2 class="mb-5 text-center">Update Driver's License</h2>
    <div class="container">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>
            <label for="license_image">Select Driver's License Image:</label>
            <input type="file" id="license_image" name="license_image" accept="image/*" required><br>
            <button type="submit" id="submit">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
