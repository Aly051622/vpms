<?php
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
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
            .left-content {
                flex: 1;
                text-align: left;
                margin-left: 20px;
            }
            .left-image {
                margin-right: 5px;
                width: 100px;
                height: 100px;
            }
            .right-image {
                margin-left: 100px;
                width: 150px;
                height: 150px;
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
                position: absolute;
                margin-top: 250px;
                justify-content: center;
                left: 610px;
                cursor: url('https://img.icons8.com/ios-glyphs/28/drag-left.png') 14 14, auto;
            }
            #printbtn:hover {
                opacity: 0.7;
                color: orange;
                font-weight: bolder;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="left-content">
                <img src="images/ctu.png" alt="ctu logo" class="left-image">
                <img src="images/asean.png" alt="asean logo" class="left-image" style="width: 130px; height: 130px;">
            </div>

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

            <img src="images/iso.png" alt="iso logo" class="right-image">
        </div>

        <div id="exampl" class="receipt-table">
            <table border="1" class="table table-bordered mg-b-0">
                <tr>
                    <th colspan="4" style="text-align: center; font-size:22px;">Vehicle Parking Receipt</th>
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

              
            </table>
        </div>

        <div style="cursor:pointer" onclick="CallPrint()" id="printbtn">🖶</div>

        <script>
            function CallPrint() {
                var prtContent = document.getElementById("exampl");
                var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
            }
        </script>
    </body>
    </html>

<?php
    }
}
?>
