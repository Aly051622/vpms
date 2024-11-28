<?php
session_start();
include('includes/dbconnection.php');

// Fetch unvalidated clients
$queryUnvalidated = "SELECT email FROM uploads WHERE validity = 0 OR expiration_date < CURDATE()";
$resultUnvalidated = mysqli_query($con, $queryUnvalidated);
$unvalidatedClients = [];

if ($resultUnvalidated && mysqli_num_rows($resultUnvalidated) > 0) {
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
            text-shadow: 4px 4px 0 yellow;
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

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
