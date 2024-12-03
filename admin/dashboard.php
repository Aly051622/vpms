<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>

<!doctype html>
<html class="no-js" lang="">
<head>
    <title>Admin Dashboard | CTU DANAO Parking System</title>
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />

    <style>
        body {
            height: 100vh;
            background-color: whitesmoke;
        }
        .card {
            font-weight: 700;
            color: #344e86;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 8px 3px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.1) 0px -4px 0px inset;
            height: 130px;
            width: 240px;
            margin-bottom: 50px;
        }
        .card:hover {
            box-shadow: orange 0px 0px 0px 3px;
        }
        .count {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-weight: bold;
            color: orange;
        }
        .content {
            padding: 40px;
        }
    </style>
</head>

<body>
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <div class="content">
        <div class="animated fadeIn">
            <div class="row">

                <!-- Validated Clients -->
                <div class="col-lg-3 col-md-6">
                    <?php
                    $queryValidated = mysqli_query($con, "
                        SELECT COUNT(*) AS validatedCount 
                        FROM uploads 
                        WHERE validity = 1 AND expiration_date >= CURDATE()
                    ");
                    $validatedCount = mysqli_fetch_assoc($queryValidated)['validatedCount'];
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-2">
                                    <i class="pe-7s-check"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text"><span class="count"><?php echo $validatedCount; ?></span></div>
                                        <div class="stat-heading">Validated Clients</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invalidated Clients -->
                <div class="col-lg-3 col-md-6">
                    <?php
                    $queryInvalidated = mysqli_query($con, "
                        SELECT COUNT(DISTINCT email) AS invalidatedCount 
                        FROM uploads 
                        WHERE validity = 0
                    ");
                    $invalidatedCount = mysqli_fetch_assoc($queryInvalidated)['invalidatedCount'];
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-3">
                                    <i class="pe-7s-close-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text"><span class="count"><?php echo $invalidatedCount; ?></span></div>
                                        <div class="stat-heading">Invalidated Clients</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unvalidated Clients -->
                <div class="col-lg-3 col-md-6">
                    <?php
                    $queryUnvalidated = mysqli_query($con, "
                        SELECT COUNT(DISTINCT email) AS unvalidatedCount 
                        FROM tblregusers 
                        WHERE validity IS NULL OR validity = 0
                    ");
                    $unvalidatedCount = mysqli_fetch_assoc($queryUnvalidated)['unvalidatedCount'];
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-4">
                                    <i class="pe-7s-info"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text"><span class="count"><?php echo $unvalidatedCount; ?></span></div>
                                        <div class="stat-heading">Unvalidated Clients</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
<?php } ?>
