<?php
session_start();
if (!isset($_SESSION['guardid'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

date_default_timezone_set('Asia/Manila');

// Include the database connection file
include('../DBconnection/dbconnection.php');

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sqlDelete = "DELETE FROM tblqr_logout WHERE ID = '$id'";
    if ($con->query($sqlDelete) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrData'])) {
    $qrData = $_POST['qrData'];
    $dataLines = explode("\n", $qrData);
    $vehicleType = str_replace('Vehicle Type: ', '', $dataLines[0]);
    $vehiclePlateNumber = str_replace('Plate Number: ', '', $dataLines[1]);
    $name = str_replace('Name: ', '', $dataLines[2]);
    $mobilenum = str_replace('Contact Number: ', '', $dataLines[3]);

    // Check if the vehicle has a recent login entry
    $sqlFindLogin = "SELECT ParkingSlot FROM tblqr_login WHERE VehiclePlateNumber = '$vehiclePlateNumber' AND Name = '$name' ORDER BY TIMEIN DESC LIMIT 1";
    $resultLogin = $con->query($sqlFindLogin);

    if ($resultLogin->num_rows > 0) {
        $rowLogin = $resultLogin->fetch_assoc();
        $occupiedSlots = explode(', ', $rowLogin['ParkingSlot']);

        // Proceed with logout regardless of existing logout entries
        $timeOut = date("Y-m-d H:i:s ");
        $sqlInsert = "INSERT INTO tblqr_logout (Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEOUT)
                      VALUES ('$name', '$mobilenum', '$vehicleType', '$vehiclePlateNumber', '{$rowLogin['ParkingSlot']}', '$timeOut')";

if ($con->query($sqlInsert) === TRUE) {
    foreach ($occupiedSlots as $slot) {
        $updateSlot = "UPDATE tblparkingslots SET Status = 'Vacant' WHERE SlotNumber = '$slot'";
        $con->query($updateSlot);
    }
    $_SESSION['success'] = 'Vehicle logged out successfully.';
} else {
    $_SESSION['error'] = 'Error: ' . $con->error;
}
} else {
$_SESSION['error'] = 'No login record found for this vehicle. Please log in first before logging out.';
}

    header('Location: qrlogout.php');
    exit();
}

// Query the current day logout records
$sql = "SELECT ID, Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEOUT FROM tblqr_logout WHERE DATE(TIMEOUT) = CURDATE() ORDER BY TIMEOUT DESC";
$result = $con->query($sql);
$con->close();
?>

<html class="no-js" lang="">
<head>
    <script type="text/javascript" src="js/adapter.min.js"></script>
    <script type="text/javascript" src="js/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="guard.css">

    <title>QR Code Logout Scanner | CTU DANAO Parking System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /*qrbutton add css*/
    .dropbtns{
            color: white;
            padding: 8px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background-color: orange;
            border-radius: 9px;
            font-weight: bold;
            border: solid;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .navbar-item .dropbtns:hover{
            background-color: white;
            color: orange;
            border: solid orange;
            border-radius: 9px;
        }
        
        /*navbar add css*/
        .navbar{
            background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            }
    @media (max-width: 480px){
    .container{
        padding-top:10px;
        margin-top:-8px;
    }
    .navbar-brand{
        margin-left: 10px;
    }
    .navbar-toggler{
        margin-top: -33px;
        margin-left: 11em;
    }
}
</style>

<nav class="navbar">
    <div class="navbar-brand"><a href="monitor2.php">Parking Slot Manager</a></div>
    <div class="navbar-toggler" onclick="toggleMenu()">&#9776;</div>
    <div class="navbar-menu" id="navbarMenu">
         <!-- QR Logout Button -->
         <a href="qrlogout.php" class="navbar-item dropbtns"><i class="bi bi-car-front-fill"></i> QR Log-out</a>
      

      <!-- Manual Input Button -->
      <a href="malogout.php" class="navbar-item dropbtns"><i class="bi bi-display-fill"></i> Manual Log-out</a>

      <a href="logout.php" class="navbar-item dropbtns"><i class="bi bi-car-front"></i> Logout</a>
       
    </div>
</nav>

    <style>
        body {
            color: black;
            background-color: whitesmoke;
            height: 100vh;
        }
        .no-js {
            background-color: #f9fcff;
            background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
        }
        .container {
            padding: 20px;
        }
        .scanner-container, .table-container {
            margin-top: 20px;
        }
        video {
            width: 500px; /* Reduced size */
            height: 300px; /* Square scanner */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto; /* Centered */
        }
        table {
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            border-radius: 5px;
        }
        .scanner-label {
            font-weight: bold; 
            color: orange; 
            font-size: 20px; 
            text-align: center; 
            margin-top: 10px;
        }
        .alert {
            transition: opacity 0.5s ease;
        }
        
        .btn-danger{
                border: solid darkred;
                border-radius: 10px;
                padding: 10px;
                background-color: red;
                color: white;
                cursor: pointer;
                font-family: 'Montserrat',sans-serif;
                font-weight: bolder;
        }
        .btn-danger:hover{
            background-color: darkblue;
            border: solid blue;
        }
        #switchCameraBtn {
            margin-top: 10px;
            cursor: pointer; /* Change cursor to pointer */
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        #switchCameraBtn:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>

<!-- Responsive Navigation Bar -->
<?php include_once('includes/headerout.php');?>

<div class="container" style="background: transparent;">
    <div class="row">
        <!-- Scanner Section -->
        <div class="col-md-12 scanner-container" style=" margin-top: 5em;">
            <video id="preview"></video>
            <div id="scanner-status" style="text-align: center; font-weight: bold; color: orange; margin-top: 10px;"></div>
            <button id="switchCameraBtn" class="btn btn-primary">Switch Camera</button> <!-- Add button here -->
            <div class="scanner-label">LOG-OUT QR SCAN <i class="fas fa-qrcode"></i></div>

            <?php
                if (isset($_SESSION['error'])) {
                    echo "
                    <div class='alert alert-danger mt-2'>
                        <h4>Error!</h4>
                        " . $_SESSION['error'] . "
                    </div>
                    ";
                    unset($_SESSION['error']);
                }

                if (isset($_SESSION['success'])) {
                    echo "
                    <div class='alert alert-primary mt-2 alert-dismissible fade show' role='alert' id='successAlert'>
                        <h4>Success!</h4>
                        " . $_SESSION['success'] . "
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    ";
                    unset($_SESSION['success']);
                }
                ?>
        </div>

        <!-- Table Section -->
        <div class="col-md-12 table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>No.</td>
                        <td>Name</td>
                        <td>Contact Number</td>
                        <td>Vehicle Type</td>
                        <td>Vehicle Plate Number</td>
                        <td>Parking Slot</td>
                        <td>TIMEOUT</td>
                        <td>Action</td> <!-- New column for delete action -->
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $formattedTimeout = date("h:i:s A m-d-Y", strtotime($row['TIMEOUT']));
                        echo "
                        <tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['ContactNumber']}</td>
                            <td>{$row['VehicleType']}</td>
                            <td>{$row['VehiclePlateNumber']}</td>
                            <td>{$row['ParkingSlot']}</td>
                            <td>{$formattedTimeout}</td>
                            <td>
                            <button onclick=\"deleteEntry(" . $row['ID'] . ")\" class=\"btn btn-danger btn-sm\">Delete</button>
                            </td>
                        </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found for today.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- QR Scanner Script -->
<script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    let selectedCameraIndex = 0; // Track the selected camera index

    // Function to switch cameras (front/back)
    function switchCamera(cameras) {
        selectedCameraIndex = (selectedCameraIndex + 1) % cameras.length;
        startScanner(cameras[selectedCameraIndex]);
    }

    // Function to start the scanner with a given camera
    function startScanner(camera) {
        scanner.stop(); // Stop any existing scanner instance
        scanner.start(camera).catch(function (e) {
            console.error("Error starting scanner:", e);
            document.getElementById('scanner-status').textContent =
                "Error: Unable to start the scanner. Please check camera permissions.";
        });

        // Apply a CSS transformation to correct mirroring for the back camera
        const videoElement = document.getElementById('preview');
        if (camera.name.toLowerCase().includes('back')) {
            videoElement.style.transform = "scaleX(1)"; // Normal view
        } else {
            videoElement.style.transform = "scaleX(-1)"; // Mirrored view for front camera
        }
    }

    // Attempt to get available cameras
    // Update the scanner initialization code to bind the button action
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            let backCameraIndex = cameras.findIndex(camera => camera.name.toLowerCase().includes('back'));
            selectedCameraIndex = backCameraIndex >= 0 ? backCameraIndex : 0;

            startScanner(cameras[selectedCameraIndex]);

            // Add functionality to the button
            document.getElementById('switchCameraBtn').addEventListener('click', () => switchCamera(cameras));
        } else {
            document.getElementById('scanner-status').textContent =
                "No camera detected. Please check if the device has an available camera.";
        }
    }).catch(function (e) {
        console.error("Error accessing cameras:", e);
        document.getElementById('scanner-status').textContent =
            "Error: Unable to access cameras. Make sure permissions are allowed and refresh the page.";
    });

    scanner.addListener('scan', function (content) {
        // Post the scanned content to the same page for logout processing
        fetch('qrlogout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `qrData=${encodeURIComponent(content)}`,
        })
        .then(response => {
            if (response.ok) {
                window.location.reload(); // Reload the page after successful logout
            } else {
                console.error('Logout failed.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });


    function toggleMenu() {
        document.getElementById("navbarMenu").classList.toggle("show");
    }
    window.onload = function() {
            setTimeout(function() {
                var successAlert = document.getElementById('successAlert');
                if (successAlert) {
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500); // Remove alert after fade-out
                }
            }, 3000); // Fade-out after 3 seconds
        };

        function deleteEntry(id) {
    if (confirm("Are you sure you want to delete this entry?")) {
        fetch("qrlogout.php", { // Use the correct PHP script URL
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id=" + id
        })
        .then(response => response.text())
        .then(result => {
            if (result === "success") {
                alert("Entry deleted successfully.");
                location.reload(); // Reload the page to refresh the table
            } else {
                alert("Failed to delete entry.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
}

</script>

</body>
</html>
