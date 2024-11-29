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
        $users[] = $row; // Store each user's details in the array
    }
}

// Close the database connection
mysqli_close($con);

// Pass the data to the view
include 'views/users_table.php';
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
    <link rel="stylesheet" href="css/responsive.css">
    <title>All Users</title>
    <style>
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
    
      
   <?php include_once('includes/sidebar.php');?>
    <!-- Right Panel -->

   <?php include_once('includes/header.php');?>

    <h1>All Users</h1>

    
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
                    <td><img src="../uploads/<?= htmlspecialchars($user['or_image']) ?>" alt="OR Image"></td>
                    <td><img src="../uploads/<?= htmlspecialchars($user['cr_image']) ?>" alt="CR Image"></td>
                    <td><img src="../uploads/<?= htmlspecialchars($user['nv_image']) ?>" alt="NV Image"></td>
                    <td><img src="../uploads/<?= htmlspecialchars($user['profile_pictures']) ?>" alt="Profile Picture"></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</body>
</html>
