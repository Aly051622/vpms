<?php
session_start();
include '../DBconnection/dbconnection.php';

// Fetch unvalidated clients (validity = -2)
$queryUnvalidated = "
    SELECT r.email, r.validity, 
           r.cr_image, r.nv_image, r.or_image, r.profile_pictures 
    FROM tblregusers r
    WHERE r.validity = -2
";

$resultUnvalidated = mysqli_query($con, $queryUnvalidated);

// Store the unvalidated clients in an array
$unvalidatedClients = [];
if (mysqli_num_rows($resultUnvalidated) > 0) {
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
    <!-- Include your styles and scripts here -->
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
        <?php else: ?>
            <h1 class="text-center">Unvalidated Clients</h1>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th>Email</th>
                            <th>Validity</th>
                            <th>CR Image</th>
                            <th>NV Image</th>
                            <th>OR Image</th>
                            <th>Profile Picture</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($unvalidatedClients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars($client['validity']) ?></td>
                                <td><img src="uploads/validated/<?= htmlspecialchars($client['cr_image']) ?>" width="100"></td>
                                <td><img src="uploads/validated/<?= htmlspecialchars($client['nv_image']) ?>" width="100"></td>
                                <td><img src="uploads/validated/<?= htmlspecialchars($client['or_image']) ?>" width="100"></td>
                                <td><img src="../uploads/profile_uploads/<?= htmlspecialchars($client['profile_pictures']) ?>" width="100"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
