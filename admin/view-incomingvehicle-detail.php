<?php
session_start();
date_default_timezone_set('Asia/Manila');
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid']==0)) {
    header('location:logout.php');
  } else {
      $cid = mysqli_real_escape_string($con, $_GET['viewid']);
      $query = "
SELECT 
    ParkingSlot, 
    VehicleCategory, 
    VehicleCompanyname, 
    Model, 
    Color, 
    RegistrationNumber, 
    OwnerName, 
    OwnerContactNumber, 
    FormattedInTimeFromLogin, 
    FormattedOutTime, 
    Source 
FROM (
    SELECT 
        tblqr_login.ParkingSlot, 
        tblqr_login.TIMEIN AS FormattedInTime, 
        tblvehicle.VehicleCategory, 
        tblvehicle.VehicleCompanyname, 
        tblvehicle.Model, 
        tblvehicle.Color, 
        tblvehicle.RegistrationNumber, 
        tblvehicle.OwnerName, 
        tblvehicle.OwnerContactNumber, 
        DATE_FORMAT(tblqr_login.TIMEIN, '%h:%i %p %m-%d-%Y') AS FormattedInTimeFromLogin, 
        DATE_FORMAT(tblqr_logout.TIMEOUT, '%h:%i %p %m-%d-%Y') AS FormattedOutTime,
        'QR' AS Source 
    FROM 
        tblqr_login 
    INNER JOIN 
        tblvehicle ON tblqr_login.VehiclePlateNumber = tblvehicle.RegistrationNumber 
    LEFT JOIN 
        tblqr_logout ON tblqr_login.VehiclePlateNumber = tblqr_logout.VehiclePlateNumber 
    WHERE 
        tblqr_login.ID = '$cid'

    UNION ALL

    SELECT 
        tblmanual_login.ParkingSlot, 
        tblmanual_login.TimeIn AS FormattedInTime, 
        tblvehicle.VehicleCategory, 
        tblvehicle.VehicleCompanyname, 
        tblvehicle.Model, 
        tblvehicle.Color, 
        tblvehicle.RegistrationNumber, 
        tblvehicle.OwnerName, 
        tblvehicle.OwnerContactNumber, 
        DATE_FORMAT(tblmanual_login.TimeIn, '%h:%i %p %m-%d-%Y') AS FormattedInTimeFromLogin, 
        DATE_FORMAT(tblmanual_logout.TimeOut, '%h:%i %p %m-%d-%Y') AS FormattedOutTime,
        'Manual' AS Source 
    FROM 
        tblmanual_login 
    INNER JOIN 
        tblvehicle ON tblmanual_login.RegistrationNumber = tblvehicle.RegistrationNumber 
    LEFT JOIN 
        tblmanual_logout ON tblmanual_login.RegistrationNumber = tblmanual_logout.RegistrationNumber 
    WHERE 
        tblmanual_login.id = '$cid'
) AS CombinedResults";

$result = mysqli_query($con, $query);
if (!$result) {
    error_log("SQL Error in view-incomingvehicle-detail.php: " . mysqli_error($con), 3, "error_log.txt");
}

  ?>
  <!doctype html>
  
  <html class="no-js" lang="">
  <head>
     
      <title>VPMS - View Vehicle Detail</title>
     
  
      <link rel="apple-touch-icon" href="images/ctu.png">
      <link rel="shortcut icon" href="images/ctu.png">
  
  
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
  
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
  
  </head>
  <body>
      <!-- Left Panel -->
  
    <?php include_once('includes/sidebar.php');?>
  
      <!-- Left Panel -->
  
      <!-- Right Panel -->
  
       <?php include_once('includes/header.php');?>
  
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
                                      <li><a href="manage-incomingvehicle.php">View Vehicle</a></li>
                                      <li class="active">Incoming Vehicle</li>
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
                              <strong class="card-title">View Incoming Vehicle</strong>
                          </div>
                          <div class="card-body">
                              <?php if ($row = mysqli_fetch_array($result)) { ?>
                                  <table class="table table-bordered">
                                      <tr>
                                          <th>Parking Slot</th>
                                          <td><?php echo $row['ParkingSlot']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Vehicle Category</th>
                                          <td><?php echo $row['VehicleCategory']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Vehicle Company Name</th>
                                          <td><?php echo $row['VehicleCompanyname']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Model</th>
                                          <td><?php echo $row['Model']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Color</th>
                                          <td><?php echo $row['Color']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Registration Number</th>
                                          <td><?php echo $row['RegistrationNumber']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Owner Name</th>
                                          <td><?php echo $row['OwnerName']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>Owner Contact Number</th>
                                          <td><?php echo $row['OwnerContactNumber']; ?></td>
                                      </tr>
                                      <tr>
                                          <th>In Time</th>
                                          <td><?php echo $row['FormattedInTimeFromLogin']; ?></td>
                                      </tr>
                                      <tr>
                                            <th>Source</th>
                                            <td><?php echo $row['Source']; ?></td>
                                        </tr>
                                      <!-- Add additional fields as necessary, similar to VIEW--TRANSAC.PHP -->
                                  </table>
                              <?php } else { ?>
                                  <p>No data found.</p>
                              <?php } ?>
                      </div>
                  </div>
              </div>
          </div><!-- .animated -->
      </div><!-- .content -->
  
      <div class="clearfix"></div>
      <?php include_once('includes/footer.php');?>
  
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
  <?php }  ?>