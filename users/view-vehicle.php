<?php
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');
if (strlen($_SESSION['vpmsuid']==0)) {
  header('location:logout.php');
  } else{



  ?>
<!doctype html>

<html class="no-js" lang="">
<head>
   
    <title>CTU- Danao Parking System - View Vehicle Parking Details</title>
    
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
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
<style>
    #printbtn:hover,
#viewbtn:hover {
    background: orange;
    color: black;
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); 
}


#printbtn {
    background: yellowgreen;
    color: white;
}

/* Styling for the download button */
.download-icon {
    display: inline-block;
    padding: 7px 7px; /* Add some padding */
    text-decoration: none; /* Remove underline */
    border-radius: 5px; /* Rounded corners */
    font-size: 18px; /* Adjust font size */
    font-weight: bold; /* Bold text */
    transition: background-color 0.3s ease; /* Smooth transition */
}

/* Hover effect for the download button */
.download-icon:hover {
    background-color: orange; /* Darker green on hover */
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
                                    <li><a href="view-vehicle.php">View Vehicle Parking Details</a></li>
                                    <li class="active">View Vehicle Parking Details</li>
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
                            <strong class="card-title">View Vehicle Parking Details</strong>
                        </div>
                        <div class="card-body">
                        <?php
                            $ownerno = $_SESSION['vpmsumn'];
                            $ret = mysqli_query($con, "SELECT RegistrationNumber, Model, VehicleCompanyname, Color, ImagePath, QRCodePath, ID as vehid FROM tblvehicle WHERE OwnerContactNumber='$ownerno'");

                            while ($row = mysqli_fetch_array($ret)) {
                                $imagePath = $row['ImagePath'];
                                $qrCodePath = !empty($row['QRCodePath']) && strpos($row['QRCodePath'], 'qrcodes/') === false 
                                    ? '../admin/qrcodes/' . $row['QRCodePath'] 
                                    : '../admin/' . $row['QRCodePath'];
                                $fullImagePath = __DIR__ . '/' . $imagePath; // Full path on the server
                            ?>
                                <div class="d-flex align-items-center border rounded p-3 mb-3">
                                    <div class="flex-shrink-0 mr-3">
                                        <?php if (!empty($imagePath) && file_exists($fullImagePath)) { ?>
                                            <img src="<?php echo $imagePath; ?>" alt="Vehicle Image" style="width: 170px; height: 100px;" />
                                        <?php } else { ?>
                                            <div class="image-placeholder">Image not found</div>
                                        <?php } ?>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Plate Number:</strong> <?php echo $row['RegistrationNumber']; ?></p>
                                                <p><strong>Make/Brand:</strong> <?php echo $row['VehicleCompanyname']; ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Model:</strong> <?php echo $row['Model']; ?></p>
                                                <p><strong>Color:</strong> <?php echo $row['Color']; ?></p>
                                            </div>
                                            <!-- QR CODE IMG -->
                                            <div class="col-md-3">
                                                <?php if (!empty($row['QRCodePath']) && file_exists($qrCodePath)) { ?>
                                                    <p style="margin: 0;"><strong>Download QR Code</strong></p>
                                                    <img src="<?php echo htmlspecialchars($qrCodePath); ?>" alt="User's QR Code" style="width:100px;height:100px;" class="img-fluid" />
                                                    <a href="<?php echo htmlspecialchars($qrCodePath); ?>" download="<?php echo basename(htmlspecialchars($row['QRCodePath'])); ?>.png" class="download-icon">
                                                        <i class="fa fa-download" aria-hidden="true"></i> <span class="sr-only">Download QR Code</span>
                                                    </a>
                                                <?php } else { ?>
                                                    <p>QR Code image not found</p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="mt-2">
                                            <a href="view--detail.php?viewid=<?php echo $row['vehid']; ?>" class="btn btn-primary mr-2" id="viewbtn">🖹 View</a>
                                            <a href="print.php?vid=<?php echo $row['vehid']; ?>" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print</a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
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
<script src="../admin/assets/js/main.js"></script>


</body>
</html>
<?php }  ?>