<?php
session_start();
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <title>Print All | CTU DANAO Parking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <style>
         @media print {
            .heading-container{
                visibility: visible;
            }
            .print-container, .print-container * {
                visibility: visible;
            }
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .header-content, .footer-content {
                width: 100%; 
                height: auto; 
                margin-left: auto; 
                margin-right: auto;
                display: flex; 
                justify-content: center;
                align-items: center; 
                position: relative; 
            }
        }
        .heading-container {
                display: flex;
                justify-content: center;
                margin-bottom: 1.5rem; /* Adjust as needed */
        }       

        .text-center {
                text-align: center;
        }
        .container {
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                margin-right: 50vh;
                padding-top: 20px;
            }
            
            .header-content, .footer-content {
                width: 100%; 
                height: auto; 
                margin-left: auto; 
                margin-right: auto;
                display: flex; 
                justify-content: center;
                align-items: center; 
                position: relative; 
            }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>

<body onload="printPage()">
    <div class="heading-container">
        <div class="print-container">
                    <div class="header-content">
                        <img src="images/header.png" alt="header" class="center">
                    </div>
                <h3 class="text-center">All Vehicle Records</h3>
            <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Parking Number</th>
                            <th>Vehicle Category</th>
                            <th>Company</th>
                            <th>Owner</th>
                            <th>Contact</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Status</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM tblvehicle";
                        $result = mysqli_query($con, $query);

                        while ($row = mysqli_fetch_array($result)) {
                            $status = ($row['Status'] == "Out") ? "Outgoing Vehicle" : "Incoming Vehicle";
                            $outTime = ($row['Status'] == "Out") ? $row['OutTime'] : "N/A";
                            $remark = ($row['Status'] == "Out") ? $row['Remark'] : "N/A";
                        ?>
                            <tr>
                                <td><?php echo $row['ParkingNumber']; ?></td>
                                <td><?php echo $row['VehicleCategory']; ?></td>
                                <td><?php echo $row['VehicleCompanyname']; ?></td>
                                <td><?php echo $row['OwnerName']; ?></td>
                                <td><?php echo $row['OwnerContactNumber']; ?></td>
                                <td><?php echo $row['InTime']; ?></td>
                                <td><?php echo $outTime; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $remark; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
            </table>
                <div class="footer-content">
                        <img src="images/footer.png" alt="footer" class="center">
                </div>
        </div>
    </div>
</body>
</html>