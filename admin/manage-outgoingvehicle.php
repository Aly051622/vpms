<?php
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');
if (strlen($_SESSION['vpmsaid']==0)) {
  header('location:logout.php');
  } else{

// For deleting    
if($_GET['del']){
$catid=$_GET['del'];
mysqli_query($con,"delete from tblvehicle where ID ='$catid'");
echo "<script>alert('Data Deleted');</script>";
echo "<script>window.location.href='manage-outgoingvehicle.php'</script>";
          }

  ?>
<!doctype html>

<html class="no-js" lang="">
<head>
   
<link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">


    <title>Manage Incoming Vehicle | CTU DANAO Parking System</title>
   

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
                  <th>S.NO</th>
            
                 
                    <th>Parking Number</th>
                    <th>Owner Name</th>
                    <th>Vehicle Reg Number</th>
                   
                          <th>Action</th>
                </tr>
                                        </tr>
                                        </thead>
                                        <?php
// Fetch all outgoing vehicles
$ret = mysqli_query($con, "SELECT * FROM tblvehicle WHERE Status='Out'");
$cnt = 1;
?>

<!-- Print button to print all records -->
<a href="print.php" style="cursor:pointer" target="_blank" id="printbtn" class="btn btn-warning">🖶 Print All</a>

<table class="table table-bordered mg-b-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Parking Number</th>
            <th>Owner Name</th>
            <th>Registration Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($ret)) { ?>
            <tr>
                <td><?php echo $cnt; ?></td>
                <td><?php echo $row['ParkingNumber']; ?></td>
                <td><?php echo $row['OwnerName']; ?></td>
                <td><?php echo $row['RegistrationNumber']; ?></td>
                <td>
                    <a href="view-outgoingvehicle-detail.php?viewid=<?php echo $row['ID']; ?>" id="viewbtn" class="btn btn-primary">🖹 View</a>
                    <a href="manage-outgoingvehicle.php?del=<?php echo $row['ID']; ?>" class="btn btn-danger" id="deletebtn" onClick="return confirm('Are you sure you want to delete?')">🗑 Delete</a>
                </td>
            </tr>
        <?php $cnt++; } ?>
    </tbody>
</table>

              </table>

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