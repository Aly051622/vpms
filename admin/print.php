<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
        <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
        <link rel="stylesheet" href="assets/css/style.css">
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
                .receipt-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .receipt-table th, .receipt-table td {
                    padding: 8px;
                    font-size: 12px;
                }
                #printbtn {
                    display: none;
                }
            }
        </style>
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
            <div id="exampl" class="receipt-table">
                    <div class="header-content">
                       <!-- <img src="images/header.png" alt="header" class="center"> -->
                    </div>
                <div class="container">   
                </div>
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
                            <th>Owner Contact Number</th>
                            <td><?php echo $row['OwnerContactNumber']; ?></td>
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
                                <td><?php echo $row['OutTime']; ?></td>
                                <th>Remark</th>
                                <td><?php echo $row['Remark']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <div class="footer-content">
                    <!--<img src="images/footer.png" alt="footer" class="center"> -->
                </div>
            </div>
        <?php
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
                           #head{
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
}
?>
