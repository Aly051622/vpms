<?php
session_start();
include('../DBconnection/dbconnection.php');

// Fetch validated clients with expiration date and additional user data
$queryValidated = "
    SELECT u.email, u.expiration_date, u.validity, 
           r.cr_image, r.nv_image, r.or_image, r.profile_pictures
    FROM uploads u
    JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity > 0 AND u.expiration_date >= CURDATE()
";
$resultValidated = mysqli_query($con, $queryValidated);
$validatedClients = [];

if ($resultValidated && mysqli_num_rows($resultValidated) > 0) {
    while ($row = mysqli_fetch_assoc($resultValidated)) {
        $validatedClients[] = $row;
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
            margin-top: 7px;
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
            background: transparent;
            color: #31708f;
            border:none;
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
    <title>Validated | CTU Danao Parking System</title>
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
                            <li><a href="dashboard.php">User Credentials</a></li>
                            <li><a href="validation.php">Credentials</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if (empty($validatedClients)): ?>
        <div class="alert alert-info">
            No validated clients found in the system.
        </div>
    <?php endif; ?>

    <h4 class="text-center">Validated Clients</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th>Email</th>
                    <th>Expiration Date</th>
                    <th>Remaining Days</th>
                    <th>CR Image</th>
                    <th>NV Image</th>
                    <th>OR Image</th>
                    <th>Profile Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($validatedClients)): ?>
                    <?php foreach ($validatedClients as $client): ?>
                        <?php
                            $expirationDate = new DateTime($client['expiration_date']);
                            $currentDate = new DateTime();
                            $remainingDays = $currentDate->diff($expirationDate)->format('%r%a'); // Positive or negative days
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                            <td><?= $remainingDays ?> days remaining</td>
                            <td><img src="../uploads/validated/<?= htmlspecialchars($client['cr_image']) ?>" width="100"></td>
                            <td><img src="../uploads/validated/<?= htmlspecialchars($client['nv_image']) ?>" width="100"></td>
                            <td><img src="../uploads/validated/<?= htmlspecialchars($client['or_image']) ?>" width="100"></td>
                            <td><img src="../uploads/profile_uploads/<?= htmlspecialchars($client['profile_pictures']) ?>" width="100"></td>
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
<script src="assets/js/main.js"></script>
</body>
</html>
