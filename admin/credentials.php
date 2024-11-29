<?php
session_start();
include 'includes/dbconnection.php';

// Check if admin is logged in
if (empty($_SESSION['vpmsaid'])) {
    header('location:logout.php');
    exit;
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>All Users</title>
    <style>
        body {
            background: whitesmoke;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: auto;
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
</div>

<div class="container">
    <h3 class="text-center mb-5">All Users</h3>
    <?php if (!empty($users)): ?>
        <table class="table table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>OR Image</th>
                    <th>CR Image</th>
                    <th>NV Image</th>
                    <th>Profile Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['FirstName']) ?></td>
                        <td><?= htmlspecialchars($user['LastName']) ?></td>
                        <td><?= htmlspecialchars($user['Email']) ?></td>
                        <td><?= htmlspecialchars($user['MobileNumber']) ?></td>
                        <td>
                            <?php if (!empty($user['or_image'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($user['or_image']) ?>" alt="OR Image">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($user['cr_image'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($user['cr_image']) ?>" alt="CR Image">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($user['nv_image'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($user['nv_image']) ?>" alt="NV Image">
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
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No users found.</p>
    <?php endif; ?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
