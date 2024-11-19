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
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />

    <title>Update Driver's License | CTU Danao Parking System</title>

    <style>
        body {
            background: whitesmoke;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Breadcrumbs styling */
        .breadcrumbs {
            padding: 15px;
        }
        .breadcrumbs h1 {
            color: #355dff;
        }
        .page-title ol {
            margin-bottom: 0;
        }

        /* Container styling */
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
            width: 400px;
            margin: 35px auto; 
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            color: #355dff;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid gray;
            border-radius: 7px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"]:hover,
        input[type="file"]:hover {
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
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #submit:hover {
            background-color: darkblue;
        }

        /* Error message styling */
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
    <!-- Right Panel -->

    <?php include_once('includes/header.php'); ?>

    <!-- Breadcrumbs Section -->
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Dashboard</h1>
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
    </div><br>

    <!-- Form Container Section -->
    <div class="container ">

        <h2>Update Driver's License</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="license_image">Select Driver's License Image:</label>
            <input type="file" id="license_image" name="license_image" accept="image/*" required><br><br>

            <button type="submit" id="submit">Submit</button>
        </form>
    </div>

</body>
</html>
