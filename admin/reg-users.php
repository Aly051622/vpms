<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // For deleting    
    if ($_GET['del']) {
        $catid = $_GET['del'];
        mysqli_query($con, "DELETE FROM tblregusers WHERE ID ='$catid'");
        echo "<script>alert('Data Deleted');</script>";
        echo "<script>window.location.href='reg-users.php'</script>";
    }
?>
<!doctype html>

<html class="no-js" lang="">
<head>
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <title>Client Information | CTU DANAO Parking System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
        #deletebtn:hover {
            background: wheat;
            color: red;
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .clearfix { 
            background-color: #f9fcff;
            background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
        }
        .card, .card-header {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        }
        .btn {
            cursor: url('https://img.icons8.com/ios-glyphs/28/drag-left.png') 14 14, auto;
        }
        .profile-pic {
            width: 50px; /* Adjust the size */
            height: 50px; /* Adjust the size */
            border-radius: 50%; /* Makes the image circular */
            object-fit: cover; /* Ensures the image covers the element */
            transition: transform 0.2s; /* Smooth zoom effect */
            cursor: pointer; /* Indicates that it's zoomable */
        }
        .profile-pic:hover {
            transform: scale(1.5); /* Zoom in effect on hover */
            z-index: 10; /* Ensure it appears above other elements */
        }
    </style>
</head>
<body>
    <!-- Left Panel -->
    <?php include_once('includes/sidebar.php'); ?>
    <!-- Left Panel -->

    <!-- Right Panel -->
    <?php include_once('includes/header.php'); ?>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Dashboard</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="reg-users.php">Registered Users</a></li>
                                <li class="active">Registered Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Registered Users</strong>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Profile Picture</th> <!-- Added Profile Picture Column -->
                                        <th>Owner Name</th>
                                        <th>Contact Number</th>
                                        <th>Vehicle Reg. Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblregusers");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td>
                                            <?php 
                                            // Check if profile picture exists, else show default avatar
                                            $profilePicture = $row['profile_pictures'] ? "../uploads/profile_uploads/" . $row['profile_pictures'] : "images/images.png"; 
                                            ?>
                                            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-pic" onClick="window.open('<?php echo $profilePicture; ?>', '_blank');">
                                        </td> <!-- Display Profile Picture -->
                                        <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['MobileNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['LicenseNumber']); ?></td>
                                        <td>
                                            <a href="reg-users.php?del=<?php echo $row['ID']; ?>" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete?')" id="deletebtn">🗑 Delete</a>
                                        </td>
                                    </tr>
                                <?php 
                                    $cnt++;
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>

    <?php include_once('includes/footer.php'); ?>
</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
<?php } ?>
