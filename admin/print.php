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
    <title>PRINT | CTU DANAO Parking System</title>

    <div class="container mt-5">
        <form method="GET" class="form-inline">
            <label for="from_date" class="mr-2">From:</label>
            <input type="date" name="from_date" class="form-control mr-3" required>

            <label for="to_date" class="mr-2">To:</label>
            <input type="date" name="to_date" class="form-control mr-3" required>

            <button type="submit" class="btn btn-primary mr-3">Filter</button>
            <button type="button" class="btn btn-success" onclick="window.location.href='print_all.php'">Print All</button>
        </form>
    </div>

    <?php
    // Query to fetch data based on date range filter
    $query = "SELECT * FROM tblvehicle";

    if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
        $from_date = $_GET['from_date'];
        $to_date = $_GET['to_date'];
        $query .= " WHERE DATE(InTime) BETWEEN '$from_date' AND '$to_date'";
    }

    $ret = mysqli_query($con, $query);
    $cnt = 1;

    while ($row = mysqli_fetch_array($ret)) {
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                margin: 0;
            }
            .container {
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                margin-right: 50vh;
                padding-top: 20px;
            }
            .center-content{
                width: 100%;
            }
            .center2-content{
                width: 100%;
            }
        
            .receipt-table {
                margin: 10px;
            }
            .receipt-table td, .receipt-table th {
                width: 20%;
            }
            
            #printbtn {
                padding: 5px;
                font-size: 35px;
                margin-top: -3px;
                position: absolute;
                justify-content: center;
                right: 30px;
                cursor: pointer;
            }
            #printbtn:hover {
                opacity: 0.7;
                color: orange;
                font-weight: bolder;
            }

            @media print {
                body {
                    margin: 0;
                    padding: 0;
                    font-size: 12px; 
                }
                .container {
                    display: block;
                    width: 100%; 
                    margin: 0 auto;
                }
                .receipt-table {
                    width: 100%; 
                    border-collapse: collapse;
                }
                .receipt-table th, .receipt-table td {
                    padding: 8px; 
                    font-size: 12px; 
                }
                .left-content, .right-content {
                    display: inline-block; 
                    width: auto; 
                    margin: 0;
                }
                #printbtn {
                    display: none; 
                }
                img {
                    width: auto; 
                    height: auto;
                }
            }
        
        </style>
    </head>
    <body>

        <div id="exampl" class="receipt-table">
            <div class="container">
            <div class="center-content">
                <img src="images/header.png">
            </div>

            </div>
            <table border="1" class="table table-bordered mg-b-0">
                <tr>
                    <th colspan="4" style="text-align: center; font-size:22px;">Vehicle Parking Receipt</th>
                    <div onclick="CallPrint()" id="printbtn">🖶</div>

                </tr>
                <tr>
                    <th>Vehicle Category</th>
                    <td><?php echo $row['VehicleCategory']; ?></td>
                    <th>Plate Number</th>
                    <td><?php echo $row['RegistrationNumber']; ?></td>
                </tr>
                <tr>
                    <th>Vehicle Make/Brand</th>
                    <td><?php echo $row['VehicleCompanyname']; ?></td>
                    <th>Color</th>
                    <td><?php echo $row['Color']; ?></td>
                    
                </tr>
                <tr>
                    <th>Model</th>
                    <td><?php echo $row['Model']; ?></td>
                    <th>Owner Name</th>
                    <td><?php echo $row['OwnerName']; ?></td>
                    
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo $row['OwnerContactNumber']; ?></td>
                    <th>Registration Date</th>
                    <td><?php echo $row['InTime']; ?></td>
                
                </tr>

                <?php if ($row['Status'] == "Out") { ?>
                    <tr>
                        <th>Out Time</th>
                        <td><?php echo $row['OutTime']; ?></td>
                        <th>Remark</th>
                        <td><?php echo $row['Remark']; ?></td>
                    </tr>
                <?php } ?>
            </table>
            
        <div class="center2-content">
                    <img src="images/footer.png">
                </div>
    </div>
        </div>

        <script>
         function CallPrint() {
    const prtContent = document.getElementById('exampl').innerHTML;
    const printButton = document.getElementById('printbtn');

    // Temporarily hide the print button
    printButton.style.display = 'none';

    // Open a new window for printing
    const WinPrint = window.open('', '', 'left=0,top=0,width=900,height=900,toolbar=0,scrollbars=0,status=0');
    
    // Write the content and include necessary styles
    WinPrint.document.write(`
        <html>
        <head>
            <title>Print View</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
            <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
            <link rel="stylesheet" href="assets/css/style.css">
            <style>
                @media print {
                    #printbtn {
                        display: none; /* Ensure print button is hidden */
                    }
                    body {
                        margin: 0;
                    }
                }
            </style>
        </head>
        <body>
            ${prtContent}
        </body>
        </html>
    `);

    // Close the document and trigger print dialog
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();

    // Restore the original visibility of the print button after printing
    printButton.style.display = 'block';
}


        </script>
    </body>
    </html>

<?php
    }
}
?>