<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Ensure `ID` is passed in the query string
    if (isset($_GET['vid'])) {
        $id = intval($_GET['vid']); // Get the ID securely
        $query = "SELECT * FROM tblvehicle WHERE ID = '$id'"; // Fetch only the data for the specified ID
        $ret = mysqli_query($con, $query);

        if ($row = mysqli_fetch_array($ret)) { // Check if data is found
?>
            <!DOCTYPE html>
            <html>
            <head>
                <link rel="apple-touch-icon" href="../images/aa.png">
                <link rel="shortcut icon" href="../images/aa.png">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
                <title>PRINT | CTU DANAO Parking System</title>
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
                    .receipt-table {
                        margin: 10px;
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
                        #printbtn {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container mt-5">
                    <div class="receipt-table">
                        <table border="1" class="table table-bordered mg-b-0">
                            <tr>
                                <th colspan="4" style="text-align: center; font-size:22px;">Vehicle Parking Receipt</th>
                                <div onclick="CallPrint()" id="printbtn">🖶</div>
                            </tr>
                            <tr>
                                <th>Vehicle Company Name</th>
                                <td><?php echo $row['VehicleCompanyname']; ?></td>
                                <th>Registration Number</th>
                                <td><?php echo $row['RegistrationNumber']; ?></td>
                            </tr>
                            <tr>
                                <th>Owner Name</th>
                                <td><?php echo $row['OwnerName']; ?></td>
                                <th>Vehicle Plate Number</th>
                                <td><?php echo $row['VehiclePlateNumber']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <script>
                    function CallPrint() {
                        window.print();
                    }
                </script>
            </body>
            </html>
<?php
        } else {
            echo "<script>alert('No data found for the selected ID.'); window.close();</script>";
        }
    } else {
        echo "<script>alert('Invalid access.'); window.close();</script>";
    }
}
?>
