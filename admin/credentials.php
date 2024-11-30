<?php
session_start();
include 'includes/dbconnection.php';

// Fetch all users' details
$queryAllUsers = "
    SELECT 
           FirstName, 
           LastName, 
           Email, 
           MobileNumber, 
           or_image, 
           cr_image, 
           nv_image, 
           profile_pictures
    FROM tblregusers
";

$resultAllUsers = mysqli_query($con, $queryAllUsers);

$users = [];
if ($resultAllUsers && mysqli_num_rows($resultAllUsers) > 0) {
    while ($row = mysqli_fetch_assoc($resultAllUsers)) {
        $users[] = $row; // Store user data in an array
    }
}

// Close the database connection
mysqli_close($con);

// Include the view
include 'users_view.php';
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
    <title>All Users</title>
    <style>
         body {
            background: whitesmoke;
            font-family: Arial, sans-serif;
            overflow: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            display: block;
            max-width: 100px;
            height: auto;
        }
    </style>
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
                            <li><a href="validation.php">Validated</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><br>

<div class="container">
    <h3 class="text-center mb-5">All Users</h3>
    <?php if (!empty($users)): ?>
        <table class="table-responsive table-striped">
            <tr class="bg-primary">
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>OR Image</th>
                <th>CR Image</th>
                <th>NV Image</th>
                <th>Profile Picture</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['FirstName']) ?></td>
                    <td><?= htmlspecialchars($user['LastName']) ?></td>
                    <td><?= htmlspecialchars($user['Email']) ?></td>
                    <td><?= htmlspecialchars($user['MobileNumber']) ?></td>
                    <td>
                        <?php if (!empty($user['or_image'])): ?>
                            <img src="../uploads/" alt="OR Image">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($user['cr_image'])): ?>
                            <img src="../uploads/" alt="CR Image">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($user['nv_image'])): ?>
                            <img src="../uploads/" alt="NV Image">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($user['profile_pictures'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($user['profile_pictures']) ?>" alt="Profile Picture">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
                        </div>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
