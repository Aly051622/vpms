<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid']==0)) {
  header('location:logout.php');
  } else{
// For deleting
if(isset($_GET['del'])) {
    $catid = intval($_GET['del']); // Use intval to ensure it's an integer
    $result = mysqli_query($con, "DELETE FROM tblvehicle WHERE ID = '$catid'");

    if($result) {
        echo "<script>alert('Data Deleted');</script>";
        echo "<script>window.location.href='manage-reg.php'</script>";
    } else {
        echo "<script>alert('Deletion failed.');</script>";
    }
}



  ?>
<!doctype html>

<html class="no-js" lang="">
<head>
   
    <title>CTU Danao | Manage Vehicle</title>


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
        background-color: whitesmoke;
         }
         .card, .card-header{
            box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
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
                                <h1>Manage Registered Client Vehicles</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="dashboard.php">Dashboard</a></li>
                                    <li><a href="manage-incomingvehicle.php">Manage Vehicle</a></li>
                                    <li class="active">Manage Vehicle</li>
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
                            <strong class="card-title">Manage Registered Vehicle</strong>
                        </div>
                        <div class="card-body">
                             <table class="table">
                <thead>
                                        <tr>
                                            <tr>
                  <th>No.</th>
            
                 
           
                    <th>Owner Name</th>
                    <th>Vehicle Plate Number</th>
                   
                          <th>Action</th>
                </tr>
                                        </tr>
                                        </thead>
               <?php
$ret=mysqli_query($con,"select *from   tblvehicle where Status=''");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
?>
              
                <tr>
                  <td><?php echo $cnt;?></td>
            
                 
               
                  <td><?php  echo $row['OwnerName'];?></td>
                  <td><?php  echo $row['RegistrationNumber'];?></td>
                  
                  <td>
    <a href="view-register.php?viewid=<?php echo $row['ID']; ?>" class="btn btn-primary" id="viewbtn">🖹 View</a> 
    <a href="print.php?vid=<?php echo $row['ID']; ?>" style="cursor:pointer" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print</a>
    <a href="manage-reg.php?del=<?php echo $row['ID']; ?>" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete?')" id="deletebtn">🗑 Delete</a>

</td>

                </tr>
                <?php 
$cnt=$cnt+1;
}?>
              </table>

                    </div>
                </div>
            </div>

   

        </div>
    </div><!-- .animated -->
</div><!-- .content -->

<div class="clearfix"></div>

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