<?php
session_start();
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print All | CTU DANAO Parking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
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
            .left-content {
                flex: 1;
                text-align: left;
                margin-left: 60px;
            }
            .left-image {
                margin-right: 50px;
                width: 100px;
                height: 100px;
            }
            .right-content {
                flex: 1;
                text-align: right;
                margin-right: -150px;
            }
            .right-image {
                margin-left: 80px;
                width: 150px;
                height: 150px;
            }
        
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
    <div class="container">
  <div class="left-content">
    <img src="images/ctu.png" alt="ctu logo" class="left-image" style="width: 130px; height: 130px;"><img src="images/cot.png" alt="cot logo" class="left-image" style="width: 90px; height: 90px;">
  </div>

  <div class="center-content">
    <p>
      Republic of the Philippines<br>
      CEBU TECHNOLOGICAL UNIVERSITY<br>
      DANAO CAMPUS<br>
      Sabang, Danao City Cebu 6004, Philippines<br>
      Website: <a href="http://www.ctu.edu.ph">http://www.ctu.edu.ph</a> Email: info-danao@ctu.edu.ph<br>
      Phone: (+6332) 354 3660 local 108 / +63 917 317 0329<br>
      OFFICE OF THE REGISTRAR<br>
      Institutional Code: 07033
    </p>
  </div>

  <div class="right-content">
    <img src="images/ph.png" alt="ph logo" class="right-image" style="width: 80px; height: 80px;"><img src="images/iso.png" alt="iso logo" class="right-image" style="width: 110px; height: 110px;">
  </div>
</div>

<body onload="printPage()">
<div class="heading-container">
  <div class="print-container">
  <h3 class="text-center">All Vehicle Records</h3>
        <table class="table table-bordered">
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
    </div>
</body>
</html>