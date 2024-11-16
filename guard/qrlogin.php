<?php 
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1); // Show errors on the page
date_default_timezone_set('Asia/Manila');

// Database connection
$conn = mysqli_connect("localhost", "u132092183_parkingz", "@Parkingz!2024", "u132092183_parkingz");

if (mysqli_connect_errno()) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Handle deletion of entries if 'id' is set in POST request
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
    $conn->close();
    exit; // Stop further processing after deletion response
}

// Process QR data after scanning
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrData'])) {
    $qrData = $_POST['qrData'];
    $dataLines = explode("\n", $qrData);

    $vehicleType = str_replace('Vehicle Type: ', '', $dataLines[0]);
    $vehiclePlateNumber = str_replace('Plate Number: ', '', $dataLines[1]);
    $name = str_replace('Name: ', '', $dataLines[2]);
    $mobilenum = str_replace('Contact Number: ', '', $dataLines[3]);
    $model = str_replace('Model: ', '', $dataLines[4]);
    $timeIn = date("Y-m-d h:i:s A");

    // Define models that require 5 slots
    $largeModels = ['Fortuner', 'MU-X', 'Montero Sport', 'Everest', 'Terra', 'Trailblazer', 'Land Cruiser', 'Patrol', 'Expedition'];

    // Get the selected area prefix
    $selectedArea = $_POST['selectedArea'];

    if (!$selectedArea) {
        $_SESSION['error'] = 'Please select an area first.';
        header('Location: qrlogin.php');
        exit();
    }

    // Check if user is already logged in without logging out
    $checkLogoutQR = "SELECT * FROM tblqr_logout WHERE Name = '$name' AND VehiclePlateNumber = '$vehiclePlateNumber' ORDER BY TIMEOUT DESC LIMIT 1";
    $checkLoginQR = "SELECT * FROM tblqr_login WHERE Name = '$name' AND VehiclePlateNumber = '$vehiclePlateNumber' ORDER BY TIMEIN DESC LIMIT 1";

    $checkLogoutManual = "SELECT * FROM tblmanual_logout WHERE Name = '$name' AND RegistrationNumber = '$vehiclePlateNumber' ORDER BY TIMEOUT DESC LIMIT 1";
    $checkLoginManual = "SELECT * FROM tblmanual_login WHERE Name = '$name' AND RegistrationNumber = '$vehiclePlateNumber' ORDER BY TIMEIN DESC LIMIT 1";

    // Execute the queries
    $logoutResultQR = $conn->query($checkLogoutQR);
    $loginResultQR = $conn->query($checkLoginQR);
    $logoutResultManual = $conn->query($checkLogoutManual);
    $loginResultManual = $conn->query($checkLoginManual);

    // Determine the latest logout and login times across both tables
    $lastLogoutTime = null;
    $lastLoginTime = null;

    if ($logoutResultQR->num_rows > 0) {
        $lastLogoutTime = $logoutResultQR->fetch_assoc()['TIMEOUT'];
    }

    if ($logoutResultManual->num_rows > 0) {
        $lastLogoutTime = max($lastLogoutTime, $logoutResultManual->fetch_assoc()['TIMEOUT']);
    }

    if ($loginResultQR->num_rows > 0) {
        $lastLoginTime = $loginResultQR->fetch_assoc()['TIMEIN'];
    }

    if ($loginResultManual->num_rows > 0) {
        $lastLoginTime = max($lastLoginTime, $loginResultManual->fetch_assoc()['TIMEIN']);
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
                error_log('SQL Error: ' . $conn->error);
                $_SESSION['error'] = 'Error: ' . $conn->error;
            }
        } else {
            $_SESSION['error'] = 'No consecutive slots available for this vehicle type.';
        }
    } else {
        $_SESSION['error'] = 'No vacant slots available in this area.';
    }
}

// Retrieve and display current login data
$sql = "SELECT ID, Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEIN 
        FROM tblqr_login 
        WHERE DATE(TIMEIN) = CURDATE() 
        ORDER BY TIMEIN DESC";

$query = $conn->query($sql);

if (!$query) {
    die('Error: ' . mysqli_error($conn));
}
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

    <title>QR Login</title>
</head>
<body>
    <!-- Display error or success messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>QR Login</h2>
        <form method="POST" action="qrlogin.php">
            <div class="form-group">
                <label for="selectedArea">Select Area:</label>
                <select id="selectedArea" name="selectedArea" class="form-control">
                    <option value="">--Select Area--</option>
                    <option value="A">Front Admin Area (A)</option>
                    <option value="B">Beside CME (B)</option>
                    <option value="C">Kadasig Area (C)</option>
                    <option value="D">Behind (D)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="qrData">QR Data:</label>
                <textarea id="qrData" name="qrData" class="form-control" rows="5"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3>Today's Login Records</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Plate Number</th>
                    <th>Parking Slot</th>
                    <th>Time In</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $query->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo $row['Name']; ?></td>
                        <td><?php echo $row['ContactNumber']; ?></td>
                        <td><?php echo $row['VehicleType']; ?></td>
                        <td><?php echo $row['VehiclePlateNumber']; ?></td>
                        <td><?php echo $row['ParkingSlot']; ?></td>
                        <td><?php echo $row['TIMEIN']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<script>
     let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

// Attempt to get available cameras
Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            let selectedCamera = cameras[0]; // Default to the first camera

            // Attempt to prioritize the back camera for mobile devices
            cameras.forEach(function (camera) {
                if (camera.name.toLowerCase().includes('back')) {
                    selectedCamera = camera;
                }
            });

            scanner.start(selectedCamera).catch(function (e) {
                console.error("Error starting scanner:", e);
                document.getElementById('scanner-status').textContent = "Error: Unable to start the scanner. Please check camera permissions.";
            });
        } else {
            document.getElementById('scanner-status').textContent = "No camera detected. Please check if the device has an available camera.";
        }
    }).catch(function (e) {
        console.error("Error accessing cameras:", e);
        document.getElementById('scanner-status').textContent = "Error: Unable to access cameras. Make sure permissions are allowed and refresh the page.";
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
        if (data.includes('Error!')) {
            document.body.innerHTML = data;
        } else {
            window.location.href = 'monitor.php';
        }
    })
    .catch(error => console.error('Error:', error));
});

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