<?php session_start(); date_default_timezone_set('Asia/Manila');?>
<html class="no-js" lang="">
<head>
    <script type="text/javascript" src="js/adapter.min.js"></script>
    <script type="text/javascript" src="js/vue.min.js"></script>
    <script src="js/html5-qrcode.min.js"></script>

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

    <title>QR Code Login Scanner | CTU DANAO Parking System</title>

    <style>
        body {
            color: black;
            background-color: #f9fcff;
            background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
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
            width: 500px;
            height: 300px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto;
        }
        .scanner-label {
            font-weight: bold; 
            color: orange; 
            font-size: 20px; 
            text-align: center; 
            margin-top: 10px;
        }
    </style>
</head>
<body>
<!-- Responsive Navigation Bar -->
<nav class="navbar">
    <div class="navbar-brand"><a href="monitor.php">Parking Slot Manager</a></div>
    <div class="navbar-toggler" onclick="toggleMenu()">&#9776;</div>
    <div class="navbar-menu" id="navbarMenu">
        <!-- QR Scanner Dropdown -->
        <div class="dropdown">
            <button class="dropbtn">QR Scanner</button>
            <div class="dropdown-content">
                <a href="qrlogin.php">Log-in</a>
                <a href="qrlogout.php">Log-out</a>
            </div>
        </div>

        <!-- Manual Input Dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Manual Input</button>
            <div class="dropdown-content">
                <a href="malogin.php">Log-in</a>
                <a href="malogout.php">Log-out</a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="background: transparent;">
    <div class="row">
        <!-- Scanner Section -->
        <div class="col-md-12 scanner-container">
            <div id="reader" style="width: 500px; height: 300px;"></div>
            <div class="scanner-label">SCAN QR CODE <i class="fas fa-qrcode"></i></div>

            <?php
            if (isset($_SESSION['error'])) {
                echo "
                <div class='alert alert-danger mt-2'>
                    <h4>Error!</h4>
                    " . $_SESSION['error'] . "
                </div>
                ";
                unset($_SESSION['error']); // Clear the error message after displaying
            }

            if (isset($_SESSION['success'])) {
                echo "
                <div class='alert alert-primary mt-2 alert-dismissible fade show' role='alert' id='successAlert'>
                    <h4>Success!</h4>
                    Added Successfully
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                ";
                unset($_SESSION['success']); // Clear the success message after displaying
            }
            ?>
        </div>

    <!-- Area Selection Dropdown -->
    <div class="col-md-12">
        <label for="areaSelect" style="font-weight: bold; color: orange; font-size: 18px;">Select Area:</label>
        <select id="areaSelect" class="form-control" required>
            <option value="">--Select Area--</option>
            <option value="A">Front Admin</option>
            <option value="B">Beside CME</option>
            <option value="C">Kadasig</option>
            <option value="D">Behind</option>
        </select>
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
                    <td>TIMEIN</td>
                </tr>
                </thead>
                <tbody>
                <?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "parkingz";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// After processing the QR code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrData'])) {
    $qrData = $_POST['qrData'];

    $dataLines = explode("\n", $qrData);
    $vehicleType = str_replace('Vehicle Type: ', '', $dataLines[0]);
    $vehiclePlateNumber = str_replace('Plate Number: ', '', $dataLines[1]);
    $name = str_replace('Name: ', '', $dataLines[2]);
    $mobilenum = str_replace('Contact Number: ', '', $dataLines[3]);

    $timeIn = date("Y-m-d h:i:s A");


    // Get the selected area prefix
    $selectedArea = $_POST['selectedArea'];
    
    if (!$selectedArea) {
        $_SESSION['error'] = 'Please select an area first.';
        // Stay on the current page (no redirection to monitor.php)
        header('Location: qrlogin.php');
        exit();
    }

    // Check if user is already logged in without logging out
    $checkLogout = "SELECT * FROM tblqr_logout WHERE Name = '$name' AND VehiclePlateNumber = '$vehiclePlateNumber' ORDER BY TIMEOUT DESC LIMIT 1";
    $checkLogin = "SELECT * FROM tblqr_login WHERE Name = '$name' AND VehiclePlateNumber = '$vehiclePlateNumber' ORDER BY TIMEIN DESC LIMIT 1";
    
    $logoutResult = $conn->query($checkLogout);
    $loginResult = $conn->query($checkLogin);

    $lastLogoutTime = $logoutResult->num_rows > 0 ? $logoutResult->fetch_assoc()['TIMEOUT'] : null;
    $lastLoginTime = $loginResult->num_rows > 0 ? $loginResult->fetch_assoc()['TIMEIN'] : null;

    // Ensure last login time is later than last logout time or no previous login exists
    if ($lastLoginTime && (!$lastLogoutTime || $lastLoginTime > $lastLogoutTime)) {
        $_SESSION['error'] = 'You cannot log in again without logging out first.';
        header('Location: qrlogin.php');
        exit();
    }

    // Check for available slots based on vehicle type
    if ($vehicleType === 'Four Wheeler Vehicle') {
        $limit = 3; // Four-wheeler needs 3 slots
    } elseif ($vehicleType === 'Two Wheeler Vehicle') {
        $limit = 1; // Two-wheeler needs 1 slot
    }

    // Find consecutive vacant slots
    $slotQuery = "SELECT SlotNumber FROM tblparkingslots 
                  WHERE Status = 'Vacant' 
                  AND SlotNumber LIKE '$selectedArea%' 
                  ORDER BY CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED)";

    $slotResult = $conn->query($slotQuery);
    $availableSlots = [];

    if ($slotResult->num_rows > 0) {
        while ($row = $slotResult->fetch_assoc()) {
            $availableSlots[] = $row['SlotNumber'];
        }

        // Check for sufficient consecutive slots
        $occupiedSlots = [];
        for ($i = 0; $i <= count($availableSlots) - $limit; $i++) {
            $isConsecutive = true;
            for ($j = 1; $j < $limit; $j++) {
                if (intval(substr($availableSlots[$i + $j], 1)) !== intval(substr($availableSlots[$i], 1)) + $j) {
                    $isConsecutive = false;
                    break;
                }
            }
            if ($isConsecutive) {
                $occupiedSlots = array_slice($availableSlots, $i, $limit);
                break;
            }
        }

        if (count($occupiedSlots) == $limit) {
            $slots = implode(', ', $occupiedSlots);

            // Insert login information and parking slot
            $sql = "INSERT INTO tblqr_login (Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEIN)
                    VALUES ('$name', '$mobilenum', '$vehicleType', '$vehiclePlateNumber', '$slots', '$timeIn')";

            // Update the status of the occupied slots
            foreach ($occupiedSlots as $slot) {
                $updateSlot = "UPDATE tblparkingslots SET Status = 'Occupied' WHERE SlotNumber = '$slot'";
                $conn->query($updateSlot);
            }

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = 'Vehicle added successfully.';
                // Redirect after success
                header('Location: monitor.php');
            } else {
                $_SESSION['error'] = 'Error: ' . $conn->error;
                // Stay on the current page in case of error
                header('Location: qrlogin.php');
            }
        } else {
            $_SESSION['error'] = 'Not enough slots!';
            header('Location: qrlogin.php');
        }
    } else {
        $_SESSION['error'] = 'No available slots.';
        header('Location: qrlogin.php');
    }

    exit();
}




$sql = "SELECT ID, Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEIN 
        FROM tblqr_login 
        WHERE DATE(TIMEIN) = CURDATE() 
        ORDER BY TIMEIN DESC";

$query = $conn->query($sql);

if (!$query) {
    die('Error: ' . mysqli_error($conn));
}

while ($row = $query->fetch_assoc()) {
    $formattedTimeIn = (new DateTime($row['TIMEIN']))->format('h:i:s A m-d-y');
    echo "
    <tr>
        <td>" . $row['ID'] . "</td>
        <td>" . $row['Name'] . "</td>
        <td>" . $row['ContactNumber'] . "</td>
        <td>" . $row['VehicleType'] . "</td>
        <td>" . $row['VehiclePlateNumber'] . "</td>
        <td>" . $row['ParkingSlot'] . "</td>
        <td>" . $formattedTimeIn . "</td>
    </tr>
    ";
}

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
     function onScanSuccess(qrCodeMessage) {
        const selectedArea = document.getElementById('areaSelect').value;

        if (!selectedArea) {
            alert('Please select an area first!');
            return;
        }

        fetch('qrlogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'qrData=' + encodeURIComponent(qrCodeMessage) + '&selectedArea=' + encodeURIComponent(selectedArea),
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('Error!')) {
                document.body.innerHTML = data;
            } else {
                window.location.href = 'monitor.php';
            }
        })
        .catch(error => {
            console.error("Error during fetch:", error);
            alert('An error occurred while processing the QR code. Please try again.');
        });
    }

    function onScanError(errorMessage) {
        console.warn(`QR code scan error: ${errorMessage}`);
    }

    const html5QrcodeScanner = new Html5Qrcode("reader");
    const config = { fps: 10, qrbox: 250 };

    Html5Qrcode.getCameras()
        .then(devices => {
            if (devices.length === 0) {
                document.getElementById('scanner-status').textContent = "No cameras found.";
                return;
            }
            const cameraId = devices[0].id; // Default to the first camera
            // Start scanning
            html5QrcodeScanner.start(cameraId, config, onScanSuccess, onScanError)
                .catch(err => {
                    console.error("Unable to start scanning:", err);
                    document.getElementById('scanner-status').textContent = "Error: Unable to start the scanner.";
                });
        })
        .catch(err => {
            console.error("Error getting cameras:", err);
            document.getElementById('scanner-status').textContent = "Error: Unable to access cameras.";
        });
</script>

</body>
</html>