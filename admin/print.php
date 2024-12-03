<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid']) == 0) {
    header('location:logout.php');
} else {
    // Ensure `ID` is passed in the query string
    if (isset($_GET['vid'])) {
        $id = intval($_GET['vid']); // Get the ID securely
        $query = "SELECT * FROM tblvehicle WHERE ID = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_array($result)) { // Check if data is found
?>
<!DOCTYPE html>
<html>
<head>
    <!-- (Your original head content remains unchanged) -->
</head>
<body>
    <div class="container mt-5" id="head">
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
    $query = "SELECT * FROM tblvehicle WHERE ID = ?"; // Apply ID filter here as well
    if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
        $from_date = mysqli_real_escape_string($con, $_GET['from_date']);
        $to_date = mysqli_real_escape_string($con, $_GET['to_date']);
        $query .= " AND DATE(InTime) BETWEEN ? AND ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'iss', $id, $from_date, $to_date);
    } else {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
    ?>
        <div id="exampl" class="receipt-table">
            <div class="header-content">
                <img src="images/header.png" alt="header" class="center">
            </div>
            <div class="container"></div>
            <table border="1" class="table table-bordered mg-b-0">
                <tr>
                    <th colspan="4" style="text-align: center; font-size:22px;">Vehicle Parking Receipt</th>
                    <div onclick="CallPrint()" id="printbtn">🖶</div>
                </tr>
                <tr>
                    <th>Vehicle Company Name</th>
                    <td><?php echo htmlspecialchars($row['VehicleCompanyname']); ?></td>
                    <th>Registration Number</th>
                    <td><?php echo htmlspecialchars($row['RegistrationNumber']); ?></td>
                </tr>
                <tr>
                    <th>Owner Name</th>
                    <td><?php echo htmlspecialchars($row['OwnerName']); ?></td>
                    <th>Owner Contact Number</th>
                    <td><?php echo htmlspecialchars($row['OwnerContactNumber']); ?></td>
                </tr>
                <tr>
                    <th>In Time</th>
                    <td>
                        <?php 
                        $datetime = new DateTime($row['InTime']);
                        echo $datetime->format('m/d/Y h:i A'); 
                        ?>
                    </td>
                    <th>Status</th>
                    <td><?php echo $row['Status'] == "Out" ? "Outgoing Vehicle" : "Incoming Vehicle"; ?></td>
                </tr>
                <?php if ($row['Status'] == "Out") { ?>
                    <tr>
                        <th>Out Time</th>
                        <td><?php echo htmlspecialchars($row['OutTime']); ?></td>
                        <th>Remark</th>
                        <td><?php echo htmlspecialchars($row['Remark']); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <div class="footer-content">
                <img src="images/footer.png" alt="footer" class="center">
            </div>
        </div>
    <?php
        }
    } else {
        echo "<p>No data available for the selected filter.</p>";
    }
    ?>

    <script>
        function CallPrint() {
            const prtContent = document.querySelector('.receipt-table').innerHTML;
            const WinPrint = window.open('', '', 'left=0,top=0,width=900,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(`
                <html>
                <head>
                    <title>Print View</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
                    <link rel="stylesheet" href="assets/css/style.css">
                    <style>
                        @media print {
                            #printbtn {
                                display: none; 
                            }
                            body {
                                margin: 0;
                            }
                            #head {
                                display: none;
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
                    </style>
                </head>
                <body>
                    ${prtContent}
                </body>
                </html>
            `);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
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
