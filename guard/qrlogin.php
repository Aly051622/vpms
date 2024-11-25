
<?php session_start(); 

session_start();

if (!isset($_SESSION['guardid'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

date_default_timezone_set('Asia/Manila');
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Set log file path
ini_set('display_errors', 1); // Disable on-screen error display


$server = "localhost";
$username = "u132092183_parkingz";
$password = "@Parkingz!2024";
$dbname = "u132092183_parkingz";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM tblqr_login WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit; // Stop further processing after deletion response
}

$conn->close();
?>


<html class="no-js" lang="">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

.scanner-container, 
.table-container {
    margin-top: 20px;
}

video {
    width: 100%;
    max-width: 500px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

.navbar-item .dropbtns:hover {
    background-color: white;
    color: orange;
    border: solid orange;
    border-radius: 9px;
}

/* Navbar styling */
.navbar {
    background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
}

/* Button styling */
#switchCameraBtn {
    margin-top: 10px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

#switchCameraBtn:hover {
    background-color: #0056b3;
}

.alert {
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    text-align: center;
    display: block;
}

/* Responsive Styles */
@media (max-width: 1024px) {
    .container {
        padding: 15px;
    }

    video {
        max-width: 450px;
    }

    .scanner-label {
        font-size: 18px;
    }
}

@media (max-width: 954px) {
    .container {
        padding: 10px;
    }

    video {
        max-width: 400px;
    }

    #switchCameraBtn {
        padding: 8px 18px;
        font-size: 15px;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    video {
        max-width: 350px;
    }

    .scanner-label {
        font-size: 16px;
    }

    #switchCameraBtn {
        font-size: 14px;
    }
}

@media (max-width: 500px) {
    .container {
        padding: 8px;
    }

    video {
        max-width: 300px;
    }

    .scanner-label {
        font-size: 14px;
    }

    #switchCameraBtn {
        font-size: 13px;
        padding: 8px 15px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 8px;
        margin-top: -8px;
    }

    .navbar-brand {
        margin-left: 10px;
    }

    .navbar-toggler {
        margin-top: -33px;
        margin-left: 11em;
    }

    video {
        max-width: 280px;
    }

    .scanner-label {
        font-size: 13px;
    }

    #switchCameraBtn {
        font-size: 12px;
    }
}

@media (max-width: 300px) {
    .container {
        padding: 5px;
    }

    video {
        max-width: 250px;
    }

    .scanner-label {
        font-size: 12px;
    }

    #switchCameraBtn {
        font-size: 11px;
        padding: 6px 12px;
    }
}

    </style>
</head>
<body>
<!-- Responsive Navigation Bar -->
<?php include_once('includes/headerin.php');?>

<div class="container" style="background: transparent;">
    <div class="row">
        <!-- Scanner Section -->
        <div class="col-md-12 scanner-container" style=" margin-top: 7em;">
        <video id="preview"></video>
        <div id="scanner-status" style="text-align: center; font-weight: bold; color: orange; margin-top: 10px;"></div>
        <button id="switchCameraBtn" class="btn btn-primary">Switch Camera</button> <!-- Add button here -->
            </div>

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
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
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
                        <td>Action</td> <!-- New column for delete action -->
                    </tr>
                </thead>
                <tbody>
                <?php
$server = "localhost";
$username = "u132092183_parkingz";
$password = "@Parkingz!2024";
$dbname = "u132092183_parkingz";

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
    $model = str_replace('Model: ', '', $dataLines[4]);
    $timeIn = date("Y-m-d H:i:s");

    // Define models that require 5 slots
    $largeModels = ['Fortuner', 'MU-X', 'Montero Sport', 'Everest', 'Terra', 'Trailblazer', 'Land Cruiser', 'Patrol', 'Expedition'];



    // Get the selected area prefix
    $selectedArea = $_POST['selectedArea'];
    
    if (!$selectedArea) {
        $_SESSION['error'] = 'Please select an area first.';
        // Stay on the current page (no redirection to monitor.php)
        header('Location: qrlogin.php');
        exit();
    }

  // Check logout status in tblqr_logout and login status in tblqr_login
$checkLogoutQR = "SELECT * FROM tblqr_logout WHERE Name = '$name' AND VehiclePlateNumber = '$vehiclePlateNumber' ORDER BY TIMEOUT DESC LIMIT 1";
$checkLoginQR = "SELECT * FROM tblqr_login WHERE Name = '$name' AND VehiclePlateNumber = '$vehiclePlateNumber' ORDER BY TIMEIN DESC LIMIT 1";

// Execute the queries
$logoutResultQR = $conn->query($checkLogoutQR);
$loginResultQR = $conn->query($checkLoginQR);

// Determine the latest logout and login times
$lastLogoutTime = null;
$lastLoginTime = null;

if ($logoutResultQR->num_rows > 0) {
    $lastLogoutTime = $logoutResultQR->fetch_assoc()['TIMEOUT'];
}

if ($loginResultQR->num_rows > 0) {
    $lastLoginTime = $loginResultQR->fetch_assoc()['TIMEIN'];
}

// Ensure last login time is later than last logout time, or no previous login exists
if ($lastLoginTime && (!$lastLogoutTime || $lastLoginTime > $lastLogoutTime)) {
    $_SESSION['error'] = 'You cannot log in again without logging out first.';
    header('Location: qrlogin.php');
    exit();
}



     // Determine slot requirements based on vehicle type and model
     if ($vehicleType === 'Four Wheeler Vehicle' && in_array($model, $largeModels)) {
        $limit = 5; // Specific large models need 5 slots
    } elseif ($vehicleType === 'Four Wheeler Vehicle') {
        $limit = 4; // General four-wheeler needs 4 slots
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
        $sequence = []; // Temporary array to hold consecutive slots

        foreach ($availableSlots as $slot) {
            if (empty($sequence)) {
                $sequence[] = $slot;
            } else {
                $lastSlotNumber = intval(substr(end($sequence), 1));
                $currentSlotNumber = intval(substr($slot, 1));

                if ($currentSlotNumber === $lastSlotNumber + 1) {
                    $sequence[] = $slot;
                } else {
                    $sequence = [$slot]; // Reset sequence if it's broken
                }
            }

            if (count($sequence) === $limit) {
                $occupiedSlots = $sequence;
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
                header('Location: monitor.php');
                exit();
            } else {
                $_SESSION['error'] = 'Error: ' . $conn->error;
            }
        } else {
            $_SESSION['error'] = 'No consecutive slots available for this vehicle type.';
        }
    } else {
        $_SESSION['error'] = 'No vacant slots available in this area.';
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
    $formattedTimeIn = (new DateTime($row['TIMEIN']))->format('h:i:s A m-d-Y');

    echo "
    <tr>
        <td>" . $row['ID'] . "</td>
        <td>" . $row['Name'] . "</td>
        <td>" . $row['ContactNumber'] . "</td>
        <td>" . $row['VehicleType'] . "</td>
        <td>" . $row['VehiclePlateNumber'] . "</td>
        <td>" . $row['ParkingSlot'] . "</td>
        <td>" . $formattedTimeIn . "</td>
        <td>
        <button onclick=\"deleteEntry(" . $row['ID'] . ")\" class=\"btn btn-danger btn-sm\">Delete</button>
                        </td>
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


    // Handle QR code scan event
    scanner.addListener('scan', function (content) {
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
            body: 'qrData=' + encodeURIComponent(content) + '&selectedArea=' + encodeURIComponent(selectedArea),
        })
            .then(response => response.text())
            .then(data => {
                console.log('Server Response:', data);
                if (data.includes('Error!')) {
                    document.body.innerHTML = data;
                } else {
                    window.location.href = 'monitor.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while processing the QR code.");
            });
    });

    // Function to delete an entry
    function deleteEntry(id) {
        if (confirm("Are you sure you want to delete this entry?")) {
            fetch("", { // Use the same script URL
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
