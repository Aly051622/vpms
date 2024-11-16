<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Database Configuration
$server = "localhost";
$username = "u132092183_parkingz";
$password = "@Parkingz!2024";
$dbname = "u132092183_parkingz";

// Establish Database Connection
$conn = new mysqli($server, $username, $password, $dbname);

// Check Database Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete Request
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM tblqr_login WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo $stmt->affected_rows > 0 ? "success" : "error";
    $stmt->close();
    exit;
}

// Handle QR Code Processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrData'])) {
    $qrData = $_POST['qrData'];
    $selectedArea = $_POST['selectedArea'];

    if (!$selectedArea) {
        $_SESSION['error'] = 'Please select an area first.';
        header('Location: qrlogin.php');
        exit();
    }

    // Parse QR Data
    $dataLines = explode("\n", $qrData);
    list($vehicleType, $vehiclePlateNumber, $name, $mobilenum, $model) = array_map(function ($line) {
        return trim(explode(": ", $line)[1]);
    }, $dataLines);

    $timeIn = date("Y-m-d h:i:s A");
    $largeModels = ['Fortuner', 'MU-X', 'Montero Sport', 'Everest', 'Terra', 'Trailblazer', 'Land Cruiser', 'Patrol', 'Expedition'];

    // Check Logout/Login State
    $logoutQuery = "SELECT MAX(TIMEOUT) AS LastLogout FROM tblqr_logout 
                    WHERE Name = ? AND VehiclePlateNumber = ?";
    $loginQuery = "SELECT MAX(TIMEIN) AS LastLogin FROM tblqr_login 
                   WHERE Name = ? AND VehiclePlateNumber = ?";

    $stmtLogout = $conn->prepare($logoutQuery);
    $stmtLogin = $conn->prepare($loginQuery);

    $stmtLogout->bind_param("ss", $name, $vehiclePlateNumber);
    $stmtLogin->bind_param("ss", $name, $vehiclePlateNumber);

    $stmtLogout->execute();
    $stmtLogin->execute();

    $lastLogoutTime = $stmtLogout->get_result()->fetch_assoc()['LastLogout'] ?? null;
    $lastLoginTime = $stmtLogin->get_result()->fetch_assoc()['LastLogin'] ?? null;

    $stmtLogout->close();
    $stmtLogin->close();

    if ($lastLoginTime && (!$lastLogoutTime || $lastLoginTime > $lastLogoutTime)) {
        $_SESSION['error'] = 'You cannot log in again without logging out first.';
        header('Location: qrlogin.php');
        exit();
    }

    // Determine Slot Requirements
    $limit = match ($vehicleType) {
        'Four Wheeler Vehicle' => in_array($model, $largeModels) ? 5 : 4,
        'Two Wheeler Vehicle' => 1,
        default => 0,
    };

    // Find Vacant Slots
    $slotQuery = "SELECT SlotNumber FROM tblparkingslots 
                  WHERE Status = 'Vacant' AND SlotNumber LIKE ?
                  ORDER BY CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED)";
    $stmtSlots = $conn->prepare($slotQuery);
    $likeArea = $selectedArea . '%';
    $stmtSlots->bind_param("s", $likeArea);
    $stmtSlots->execute();

    $availableSlots = [];
    $resultSlots = $stmtSlots->get_result();
    while ($row = $resultSlots->fetch_assoc()) {
        $availableSlots[] = $row['SlotNumber'];
    }
    $stmtSlots->close();

    // Find Consecutive Slots
    $occupiedSlots = [];
    $sequence = [];
    foreach ($availableSlots as $slot) {
        if (empty($sequence)) {
            $sequence[] = $slot;
        } else {
            $lastSlotNumber = intval(substr(end($sequence), 1));
            $currentSlotNumber = intval(substr($slot, 1));
            $sequence = ($currentSlotNumber === $lastSlotNumber + 1) ? array_merge($sequence, [$slot]) : [$slot];
        }

        if (count($sequence) === $limit) {
            $occupiedSlots = $sequence;
            break;
        }
    }

    if (count($occupiedSlots) === $limit) {
        $slots = implode(', ', $occupiedSlots);

        // Insert Login Record
        $insertLogin = "INSERT INTO tblqr_login 
                        (Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEIN) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertLogin);
        $stmtInsert->bind_param("ssssss", $name, $mobilenum, $vehicleType, $vehiclePlateNumber, $slots, $timeIn);
        $stmtInsert->execute();

        // Update Slot Status
        foreach ($occupiedSlots as $slot) {
            $updateSlot = "UPDATE tblparkingslots SET Status = 'Occupied' WHERE SlotNumber = ?";
            $stmtUpdate = $conn->prepare($updateSlot);
            $stmtUpdate->bind_param("s", $slot);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        if ($stmtInsert->affected_rows > 0) {
            $_SESSION['success'] = 'Vehicle added successfully.';
            header('Location: monitor.php');
        } else {
            $_SESSION['error'] = 'Error: ' . $conn->error;
        }
        $stmtInsert->close();
    } else {
        $_SESSION['error'] = 'No consecutive slots available for this vehicle type.';
    }

    exit();
}

// Fetch Today's Logins
$sql = "SELECT ID, Name, ContactNumber, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEIN 
        FROM tblqr_login 
        WHERE DATE(TIMEIN) = CURDATE() 
        ORDER BY TIMEIN DESC";
$query = $conn->query($sql);

if (!$query) {
    die('Error: ' . $conn->error);
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
<?php include_once('includes/headerin.php');?>

<div class="container" style="background: transparent;">
    <div class="row">
        <!-- Scanner Section -->
        <div class="col-md-12">
                <video id="preview" style="width: 100%; max-width: 500px; height: auto;"></video>
                <div id="scanner-status" style="text-align: center; font-weight: bold; color: orange; margin-top: 10px;"></div>
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
                        <td>Action</td> <!-- New column for delete action -->
                    </tr>
                </thead>
                <tbody>
            
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
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        scanner.start(cameras[0]).catch(e => console.error(e));
    } else {
        alert('No cameras found.');
    }
}).catch(e => console.error(e));

scanner.addListener('scan', function (content) {
    const selectedArea = document.getElementById('areaSelect').value;
    if (!selectedArea) {
        alert('Please select an area first!');
        return;
    }

    fetch('qrlogin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `qrData=${encodeURIComponent(content)}&selectedArea=${encodeURIComponent(selectedArea)}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            window.location.href = 'monitor.php';
        } else {
            alert("Error: " + data);
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