<?php
session_start();
include('../DBconnection/dbconnection.php');

// Get the current date
$currentDate = date('Y-m-d');

// Fetch invalidated clients (validity = 0) where expiration_date is less than the current date, without duplicates based on email
$queryInvalidated = "
    SELECT 
        r.Email AS email, 
        r.expiration_date 
    FROM 
        tblregusers r
    WHERE 
        r.validity = 0
        AND r.expiration_date < '$currentDate'
    GROUP BY 
        r.Email
";

$resultInvalidated = mysqli_query($con, $queryInvalidated);

if (!$resultInvalidated) {
    // Handle potential errors during query execution
    die("Error fetching invalidated clients: " . mysqli_error($con));
}

$invalidatedClients = mysqli_fetch_all($resultInvalidated, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($con);
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
    <style>
        body {
            background-color: whitesmoke;
            font-family: Arial, sans-serif;
        }

        h4, .alert-info {
            margin: 20px 0;
            text-align: center;
            color: #31708f;
            font-weight: bold;
        }

        .table {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            padding: 12px;
        }

        .breadcrumbs {
            background: whitesmoke;
            padding: 15px;
            border-radius: 10px;
        }

        .bg-primary {
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
                                <li><a href="invalidated.php">Invalidated</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (empty($invalidatedClients)): ?>
            <div class="alert alert-info">
                No invalidated clients found in the system.
            </div>
        <?php else: ?>
            <h4>Invalidated Clients</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th>Email</th>
                            <th>Expiration Date</th>
                            <th>Remaining Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invalidatedClients as $client): ?>
                            <?php
                            // Format expiration date
                            $expirationDate = new DateTime($client['expiration_date']);
                            $formattedExpirationDate = $expirationDate->format('F j, Y'); // Month Day, Year format
                            $currentDate = new DateTime();
                            $remainingDays = $currentDate->diff($expirationDate)->format('%r%a'); // Get remaining days
                            ?>
                            <tr>
                                <td><?php echo $client['email']; ?></td>
                                <td><?php echo $formattedExpirationDate; ?></td>
                                <td><?php echo $remainingDays; ?> days</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
 
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
