<?php
session_start();
include('../DBconnection/dbconnection.php'); 

// Fetch invalidated clients (validity = 0) without duplicates based on email
$queryInvalidated = "
    SELECT u.email, 
           MAX(u.expiration_date) AS expiration_date
    FROM uploads u
    JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity = 0
    GROUP BY u.email
";

$resultInvalidated = mysqli_query($con, $queryInvalidated);
$invalidatedClients = [];

if ($resultInvalidated && mysqli_num_rows($resultInvalidated) > 0) {
    while ($row = mysqli_fetch_assoc($resultInvalidated)) {
        $invalidatedClients[] = $row;
    }
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            background-color: whitesmoke;
            font-family: Arial, sans-serif;
        }

        .alert-info {
            margin-bottom: 20px;
            padding: 1px;
            font-size: 1.1rem;
            border: none;
            background-color: transparent;
            color: #31708f;
            text-shadow: 0 4px 4px blue;
        }

        .table {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .img-fluid {
            border-radius: 5px;
        }

        .breadcrumbs {
            background: whitesmoke;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .bg-primary{
            color: white;
        }
    </style>
    <title>Invalidated | CTU Danao Parking System</title>
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
                                <li><a href="validation.php">Invalidated</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (empty($invalidatedClients)): ?>
            <div class="alert alert-info text-center">
                No invalidated clients found in the system.
            </div>
        <?php endif; ?>

        <h4 class="text-center">Invalidated Clients</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <th>Email</th>
                        <th>Expiration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($invalidatedClients)): ?>
                        <?php foreach ($invalidatedClients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No data to display.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
