<?php
session_start();
include('includes/dbconnection.php');

// Fetch unvalidated clients (validity = -2)
$queryUnvalidated = "
    SELECT r.email 
    FROM tblregusers r
    WHERE r.validity = -2
";

$resultUnvalidated = mysqli_query($con, $queryUnvalidated);

if (mysqli_num_rows($resultUnvalidated) > 0) {
    $unvalidatedClients = [];
    while ($row = mysqli_fetch_assoc($resultUnvalidated)) {
        $unvalidatedClients[] = $row;
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
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
            background: whitesmoke;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        h1 {
            text-align: center;
            margin-top: 10px;
            color: #1e3c72;
            font-weight: bold;
        }

        p {
            text-align: center;
            font-size: 1.1em;
            color: #555;
        }
         .bg-primary{
            color: white;
        }
        .alert-info {
            margin: 20px auto;
            padding: 1px;
            font-size: 1.1em;
            border-radius: 10px;
            background: transparent;
            color: #31708f;
            border: none;
            width: 90%;
            text-align: center;
            text-shadow: 0 4px 4px blue;
        }

        .table {
            margin: 20px auto;
            width: 90%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            padding: 10px;
        }
        .bg-primary{
            color: white;
        }
        .breadcrumbs {
            background: #f5f5f5;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .breadcrumb a {
            color: #1e3c72;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1024px) {
            .table {
                font-size: 14px;
            }

            .breadcrumbs .page-title {
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5em;
            }

            .breadcrumbs {
                padding: 10px 15px;
            }

            .table {
                font-size: 12px;
            }
        }

        @media (max-width: 500px) {
            .breadcrumbs .page-title {
                text-align: center;
                font-size: 1em;
            }

            .table {
                font-size: 11px;
            }
        }
    </style>
    <title>Unvalidated | CTU Danao Parking System</title>
</head>
<body>
<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div class="breadcrumbs mb-5">
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
                            <li><a href="validation.php">Unvalidated</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if (empty($unvalidatedClients)): ?>
        <div class="alert alert-info">
            No unvalidated clients found in the system.
        </div>
    <?php endif; ?>

    <h1 class="text-center">Unvalidated Clients</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($unvalidatedClients)): ?>
                    <?php foreach ($unvalidatedClients as $client): ?>
                        <tr>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="1" class="text-center">No data to display.</td>
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
