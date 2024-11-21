<?php
session_start();
date_default_timezone_set('Asia/Manila');
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid']==0)) {
    header('location:logout.php');
  } else {
  // For deleting
  if (isset($_GET['del']) && isset($_GET['source'])) {
      $id = intval($_GET['del']);
      $source = $_GET['source'];
  
      if ($source === 'QR') {
          // Delete from tblqr_login
          $deleteQuery = mysqli_query($con, "DELETE FROM tblqr_login WHERE ID = '$id'");
      } elseif ($source === 'Manual') {
          // Delete from tblmanual_login
          $deleteQuery = mysqli_query($con, "DELETE FROM tblmanual_login WHERE id = '$id'");
      } else {
          // Invalid source
          echo "<script>alert('Invalid source specified.');</script>";
          $deleteQuery = false;
      }
  
      if ($deleteQuery) {
          echo "<script>alert('Data Deleted');</script>";
          echo "<script>window.location.href='manage-incomingvehicle.php'</script>";
      } else {
          echo "<script>alert('Failed to delete data.');</script>";
      }
  }
  
  
  // Query to retrieve data from both tblqr_login and tblmanual_login, including TIMEIN
  $query = "
      SELECT 'QR' AS Source, tblqr_login.ID AS qrLoginID, tblqr_login.ParkingSlot, tblqr_login.TIMEIN,
             tblvehicle.OwnerName, tblqr_login.VehiclePlateNumber
      FROM tblqr_login
      INNER JOIN tblvehicle 
      ON tblqr_login.VehiclePlateNumber = tblvehicle.RegistrationNumber
      
      UNION
      
      SELECT 'Manual' AS Source, tblmanual_login.id AS LoginID, tblmanual_login.ParkingSlot, tblmanual_login.TimeIn,
             tblvehicle.OwnerName, tblmanual_login.RegistrationNumber AS VehiclePlateNumber
      FROM tblmanual_login
      INNER JOIN tblvehicle 
      ON tblmanual_login.RegistrationNumber = tblvehicle.RegistrationNumber
      ORDER BY TIMEIN DESC";
  
  $result = mysqli_query($con, $query);
  
  if (!$result) {
      // Log SQL error message if the query fails
      error_log("SQL Error in manage-incomingvehicle.php: " . mysqli_error($con), 3, "error_log.txt");
  }
  
  // Count the number of records
  $total_rows = mysqli_num_rows($result);
  ?>
  <!doctype html>
  
  <html class="no-js" lang="">
  <head>
     
      <title>CTU Danao- Manage Incoming Vehicle</title>
  
  
      <link rel="apple-touch-icon" href="images/ctu.png">
      <link rel="shortcut icon" href="images/ctu.png">
      <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
      <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
  
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
  .btn {
      padding: 5px;
      margin: 3px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: auto;
      cursor: url('https://img.icons8.com/ios-glyphs/28/drag-left.png') 14 14, auto;
  }
  
  #printbtn:hover,
  #viewbtn:hover {
      background: orange;
      color: black;
      transform: scale(1.1);
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); 
  }
  
  #deletebtn:hover {
      background: wheat;
      color: red;
      transform: scale(1.1);
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); 
  }
  
  #printbtn {
      background: yellowgreen;
      color: white;
  }
  
  
      body{ 
          background-color: #f9fcff;
          background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
           }
           .card, .card-header{
              box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, 
              rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, 
              rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
           }
           
      </style>
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
                                      <li><a href="manage-incomingvehicle.php">Manage Vehicle</a></li>
                                      <li class="active">Manage Incoming Vehicle</li>
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
                              <strong class="card-title">Manage Incoming Vehicle</strong>
                          </div>
                          <div class="card-body">
                               <table class="table">
                  <thead>
                                          <tr>
                                              <tr>
                                              <th>No.</th>
                                          <th>Parking Slot</th>
                                          <th>Owner Name</th>
                                          <th>Plate Number</th>
                                          <th>TIMEIN</th>
                                          <th>Action</th>
                                          
                  </tr>
                                          </tr>
                                          </thead>
                                          <?php
                                      $cnt = $total_rows;
                                      while ($row = mysqli_fetch_array($result)) { ?>
                                          <tr>
                                              <td><?php echo $cnt; ?></td>
                                              <td><?php echo $row['ParkingSlot']; ?></td>
                                              <td><?php echo $row['OwnerName']; ?></td>
                                              <td><?php echo $row['VehiclePlateNumber']; ?></td>
                                              <td><?php echo date('h:i A - Y-m-d', strtotime($row['TIMEIN'])); ?></td>
  
  
                                              <td>
                                              <a href="view-incomingvehicle-detail.php?viewid=<?php echo $row['qrLoginID']; ?>&source=<?php echo $row['source']; ?>" class="btn btn-primary" id="viewbtn">🖹 View</a>

                                                
                                                  <a href="manage-incomingvehicle.php?del=<?php echo $row['qrLoginID'] ?: $row['LoginID']; ?>&source=<?php echo $row['Source']; ?>" 
         class="btn btn-danger" 
         onClick="return confirm('Are you sure you want to delete?')" 
         id="deletebtn">🗑 Delete</a>
                                              </td>
                                          </tr>
                                      <?php
                                          $cnt--;
                                      } ?>
                </table>
                                      <!--<a href="print_all.php" style="cursor:pointer" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print All</a> -->
                      </div>
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