<?php
session_start();
include('includes/dbconnection.php'); 

// Fetch invalidated clients (validity = 0) without duplicates based on email
$queryInvalidated = "
    SELECT u.email, 
           MAX(u.expiration_date) AS expiration_date, 
           MAX(u.validity) AS validity, 
           MAX(r.cr_image) AS cr_image, 
           MAX(r.nv_image) AS nv_image, 
           MAX(r.or_image) AS or_image, 
           MAX(r.profile_pictures) AS profile_pictures 
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
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
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
            text-shadow: 4px 4px 0 yellow;
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
                        <th>Validity</th>
                        <th>CR Image</th>
                        <th>NV Image</th>
                        <th>OR Image</th>
                        <th>Profile Picture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($invalidatedClients)): ?>
                        <?php foreach ($invalidatedClients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                                <td><?= htmlspecialchars($client['validity']) ?></td>
                                <td><img src="uploads/validated/<?= htmlspecialchars($client['cr_image']) ?>" width="100" class="img-fluid"></td>
                                <td><img src="uploads/validated/<?= htmlspecialchars($client['nv_image']) ?>" width="100" class="img-fluid"></td>
                                <td><img src="uploads/validated/<?= htmlspecialchars($client['or_image']) ?>" width="100" class="img-fluid"></td>
                                <td><img src="../uploads/profile_uploads/<?= htmlspecialchars($client['profile_pictures']) ?>" width="100" class="img-fluid"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No data to display.</td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
