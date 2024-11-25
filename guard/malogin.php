<?php 
session_start();

if (!isset($_SESSION['guardid'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

date_default_timezone_set('Asia/Manila');

$server = "localhost";
$username = "u132092183_parkingz";
$password = "@Parkingz!2024";
$dbname = "u132092183_parkingz";

$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the number of records per page
$recordsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $recordsPerPage;

// Fetch records with pagination
$vehicles = [];
$query = "SELECT *, CONVERT_TZ(TimeIn, '+00:00', '+08:00') AS TimeInLocal FROM tblmanual_login WHERE DATE(TimeIn) = CURDATE() ORDER BY ID DESC LIMIT $start, $recordsPerPage";
$result = $conn->query($query);


if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}

// Calculate total pages
$totalQuery = "SELECT COUNT(*) as count FROM tblmanual_login WHERE DATE(TimeIn) = CURDATE()";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['count'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Initialize response variable
$response = ['exists' => false, 'vehicle' => null];

// Handling Edit and Delete functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contactNumber']) && isset($_POST['plateNumber'])) {
        $contactNumber = $_POST['contactNumber'];
        $plateNumber = $_POST['plateNumber'];

        // Check if the contact number and plate number exist
        $query = "SELECT * FROM tblvehicle WHERE OwnerContactNumber = ? AND RegistrationNumber = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        
        $stmt->bind_param("ss", $contactNumber, $plateNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['exists'] = true;
            $response['vehicle'] = $result->fetch_assoc();
        }
        
        echo json_encode($response);
        exit;
    } elseif (isset($_POST['addVehicle'])) {
        $ownerName = $_POST['ownerName'];
        $contactNumber = $_POST['contactNumber'];
        $vehicleType = $_POST['vehicleType'];
        $registrationNumber = $_POST['registrationNumber'];
        $parkingSlot = $_POST['parkingSlot'];

        // Insert new vehicle data into tblmanual_login
        $insertQuery = "INSERT INTO tblmanual_login (OwnerName, OwnerContactNumber, VehicleCategory, RegistrationNumber, ParkingSlot, TimeIn) VALUES (?, ?, ?, ?, ?, NOW())";
        $insertStmt = $conn->prepare($insertQuery);

        if (!$insertStmt) {
            die("SQL Error: " . $conn->error);
        }
        
        $insertStmt->bind_param("sssss", $ownerName, $contactNumber, $vehicleType, $registrationNumber, $parkingSlot);
        $insertStmt->execute();

        echo json_encode(['success' => $insertStmt->affected_rows > 0]);
        $insertStmt->close();
        exit;
    } elseif (isset($_POST['deleteVehicle'])) {
        $id = $_POST['id'];
        
        // Delete vehicle data
        $deleteQuery = "DELETE FROM tblmanual_login WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
        
        echo json_encode(['success' => $deleteStmt->affected_rows > 0]);
        $deleteStmt->close();
        exit;
    } elseif (isset($_POST['editVehicle'])) {
        $id = $_POST['id'];
        $parkingSlot = $_POST['parkingSlot'];
        
        // Update parking slot
        $updateQuery = "UPDATE tblmanual_login SET ParkingSlot = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        
        $updateStmt->bind_param("si", $parkingSlot, $id);
        $updateStmt->execute();
        
        echo json_encode(['success' => $updateStmt->affected_rows > 0]);
        $updateStmt->close();
        exit;
    }
}

$conn->close();
?>

<html class="no-js" lang="">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="guard.css">

    <title>QR Code Login Scanner | CTU DANAO Parking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
     .containers{
        padding-top:10px;
        margin-top:-3em;
        padding: 10px;
        margin-left: 27em;
    }
    /*qrbutton add css*/
    .dropbtns{
        margin-top: 40px;
            color: white;
            padding:1px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background-color: orange;
            border-radius: 9px;
            font-weight: bold;
            border: solid;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .dropbtns:hover{
            background-color: white;
            color: orange;
            border: solid orange;
        }
        .navbar{
            background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            }
    @media (max-width: 480px){
    .containers{
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
    
<div class="navbar-brand"><a href="monitor.php" style="color: white; margin-top: 10px; margin-left: 10px;">Parking Slot Manager</a></div>
<div class="containers">
    <div class="navbar-toggler" onclick="toggleMenu()">&#9776;</div>
    <div class="navbar-menu" id="navbarMenu" >

        <!-- QR Login Button -->
        <a href="qrlogin.php" class=" dropbtns"><i class="bi bi-car-front-fill"></i> QR Log-in</a>
      

        <!-- Manual Input Button -->
        <a href="malogin.php" class=" dropbtns"><i class="bi bi-display-fill"></i> Manual Log-in</a>

        <a href="logout.php" class=" dropbtns"><i class="bi bi-house-fill"></i> Home</a>
       
    </div>
</div>
</nav>
    <style>
         /* Body and Container */
        .container {
            max-width: 600px;
            margin-top: 100px;
            text-align: center;
        }
        .hidden-field{
            display: none;
        }
        h2 {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        /* Search Button */
            .btn-primary{
                border: solid lightgray;
                border-radius: 10px;
                padding: 10px;
                background-color: rgb(53, 97, 255);
                color: white;
                cursor: pointer;
                font-family: 'Montserrat',sans-serif;
                font-weight: bolder;
        }

           .btn-primary:hover{
                background-color: darkblue;
                border: solid blue;
            }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1000;
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .modal-content p {
            color: #d9534f;
            font-size: 16px;
            margin: 0;
            padding: 10px 0;
        }
        .close-button {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .close-button:hover {
            background-color: #c9302c;
        }
        .table-responsive {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-top: 20px;
        align-items: center;
        overflow-x: hidden; /* Prevent horizontal scrolling */
    }
    .table {
        width: 50%;
        table-layout: fixed; /* Ensure columns fit within the table width */
        border-collapse: collapse;
        background-color: #f9fcff;
        word-wrap: break-word; /* Wrap text within cells */
    }
    .table th, .table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
        white-space: normal; /* Allow text wrapping */
    }
    .table th {
        font-weight: bold;
        background-color: #e0e6ed;
    }
    .table td {
        background-color: #ffffff;
    }
    h3{
        text-align: center;
    }
    /* Column Responsiveness */
    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 12px;
            padding: 6px;
        }
    }
    #deletebtn{
                border: solid darkred;
                border-radius: 10px;
                padding: 10px;
                background-color: red;
                color: white;
                cursor: pointer;
                font-family: 'Montserrat',sans-serif;
                font-weight: bolder;
        }
        #deletebtn:hover{
            background-color: darkblue;
            border: solid blue;
        }
            #editbtn{
                border: solid lightgray;
                border-radius: 10px;
                padding: 10px;
                background-color: rgb(53, 97, 255);
                color: white;
                cursor: pointer;
                font-family: 'Montserrat',sans-serif;
                font-weight: bolder;
        }

           #editbtn:hover{
                background-color: darkblue;
                border: solid blue;
            }
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
    </style>
</head>
<body>

<!-- Form for Contact Number and Plate Number Search -->
<div class="container">
    <h2>Search Vehicle</h2>
    <form id="searchForm">
        <div class="form-group">
            <label for="contactNumber">Contact Number</label>
            <input type="text" class="form-control" id="contactNumber" placeholder="Enter Contact Number" required>
        </div>
        <div class="form-group">
         <label for="vehiclePlateNumber">Vehicle Plate Number</label>
            <input type="text" class="form-control" id="vehiclePlateNumber" placeholder="Enter Vehicle Plate Number" required>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
     <!-- Hidden Area and Parking Slot Input Fields -->
<div id="additionalFields" class="hidden-field">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="area">Area:</label>
                <select class="form-control" id="area" name="area">
                    <option value="">--Select Area--</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="slotArea">Parking Slot:</label>
                <input type="text" class="form-control" id="slotArea" name="slotArea" placeholder="Enter slot area">
            </div>
        </div>

            <div id="slotWarning" style="color: red; display: none;">
        Please enter a valid parking slot with the required prefix or suffix.
    </div>
    </div>
</div>

<!-- Add to Table Button -->
<button type="button" class="btn btn-success" id="addToTableButton" style="display:none;">Add to Table</button>

</div>

<!-- Modal for Invalid Search -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
        <p>Contact Number or Vehicle Plate Number does not exist.</p>
        <button class="close-button" onclick="closeModal()">Close</button>
    </div>
</div>

       
<!-- Vehicle Information Table -->
<h3>Vehicle Information</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Vehicle Type</th>
                <th>Plate Number</th>
                <th>Parking Slot</th>
                <th>TIMEIN</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="vehicleTableBody">
            <?php if (!empty($vehicles)): ?>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?= htmlspecialchars($vehicle['id']); ?></td>
                        <td><?= htmlspecialchars($vehicle['OwnerName']); ?></td>
                        <td><?= htmlspecialchars($vehicle['OwnerContactNumber']); ?></td>
                        <td><?= htmlspecialchars($vehicle['VehicleCategory']); ?></td>
                        <td><?= htmlspecialchars($vehicle['RegistrationNumber']); ?></td>
                        <td id="slot_<?= htmlspecialchars($vehicle['id']); ?>"><?= htmlspecialchars($vehicle['ParkingSlot']); ?></td>

                        <td><?= date("h:i:s A m-d-y", strtotime($vehicle['TimeInLocal'])); ?></td>

                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editSlot(<?= $vehicle['id'] ?>)" id="editbtn">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteVehicle(<?= $vehicle['id'] ?>)" id="deletebtn">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>




<script>

function toggleMenu() {
    const navbarMenu = document.querySelector('.navbar-menu');
    navbarMenu.classList.toggle('show');
}

// JavaScript to handle page navigation without page reloads
function navigatePage(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', page);
    window.location.href = url.toString();
}

// Variable to hold vehicle data globally
let vehicle = null;

// Function to show the modal for missing records
function showModal() {
    document.getElementById('modalOverlay').style.display = 'block';
}

// Function to close the modal
function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}

// Event listener for the search form
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent default form submission

    let contactNumber = document.getElementById('contactNumber').value;
    let plateNumber = document.getElementById('vehiclePlateNumber').value;

    // Check if input fields are filled
    if (contactNumber && plateNumber) {
        fetch('malogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `contactNumber=${contactNumber}&plateNumber=${plateNumber}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                // Set vehicle data globally
                vehicle = data.vehicle;

                // Show hidden fields and add-to-table button
                document.getElementById('additionalFields').classList.remove('hidden-field');
                document.getElementById('addToTableButton').style.display = 'block';

                // Prefill vehicle details if needed
                if (vehicle) {
                    document.getElementById('vehiclePlateNumber').value = vehicle.RegistrationNumber || '';
                }
            } else {
                // Show modal only if no data is found
                showModal();
                document.getElementById('additionalFields').classList.add('hidden-field');
                document.getElementById('addToTableButton').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal();  // Show modal on error as well
        });
    } else {
        showModal();  // Show modal if inputs are missing
    }
});

// Add close event for the modal close button
document.querySelector('.close-button').addEventListener('click', closeModal);

function editSlot(id) {
    const currentSlot = document.getElementById(`slot_${id}`).innerText;
    const newSlot = prompt("Enter new parking slot:", currentSlot);

    if (newSlot && newSlot !== currentSlot) {
        fetch('malogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `editVehicle=true&id=${id}&parkingSlot=${newSlot}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Parking slot updated successfully.");
                document.getElementById(`slot_${id}`).innerText = newSlot;
            } else {
                alert("Failed to update parking slot.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while updating the parking slot.");
        });
    }
}


function deleteVehicle(id) {
    if (confirm("Are you sure you want to delete this vehicle?")) {
        fetch('malogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `deleteVehicle=true&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Vehicle deleted successfully.");
                location.reload();
            } else {
                alert("Failed to delete vehicle.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while deleting the vehicle.");
        });
    }
}


document.getElementById('addToTableButton').addEventListener('click', function() {
    if (validateParkingSlot() && vehicle) {
        const ownerName = vehicle.OwnerName;
        const contactNumber = vehicle.OwnerContactNumber;
        const vehicleType = vehicle.VehicleCategory;
        const registrationNumber = vehicle.RegistrationNumber;
        const parkingSlot = document.getElementById('slotArea').value;

        // AJAX request to save the new vehicle to the database
        fetch('malogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `addVehicle=true&ownerName=${ownerName}&contactNumber=${contactNumber}&vehicleType=${vehicleType}&registrationNumber=${registrationNumber}&parkingSlot=${parkingSlot}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Vehicle added successfully.');
                location.reload();  // Reload the page to fetch and display the updated list
            } else {
                alert('Failed to add vehicle.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the vehicle.');
        });
    }
});



// Automatically capitalize letters in the parking slot input field
document.getElementById('slotArea').addEventListener('input', function() {
    this.value = this.value.toUpperCase(); // Convert to uppercase
    validateParkingSlot(); // Call validation to check input validity
});

// Update the validateParkingSlot function to show warning messages
function validateParkingSlot() {
    const area = document.getElementById('area').value;
    const slotInput = document.getElementById('slotArea');
    const slotWarning = document.getElementById('slotWarning');
    const slotValue = slotInput.value;

    // Reset warning message visibility
    slotWarning.style.display = 'none';
    slotInput.style.borderColor = '';

    // Validation conditions for each area
    switch (area) {
        case 'A': // Front Admin
            if (!slotValue.startsWith('A')) {
                slotWarning.innerText = 'Parking slot must start with "A" for Front Admin.';
                slotWarning.style.display = 'block';
                slotInput.style.borderColor = 'red';
                return false;
            }
            break;
        case 'B': // Beside CME
            if (!slotValue.startsWith('B')) {
                slotWarning.innerText = 'Parking slot must end with "B" for Beside CME.';
                slotWarning.style.display = 'block';
                slotInput.style.borderColor = 'red';
                return false;
            }
            break;
        case 'C': // Kadasig
            if (!slotValue.startsWith('C')) {
                slotWarning.innerText = 'Parking slot must start with "C" for Kadasig.';
                slotWarning.style.display = 'block';
                slotInput.style.borderColor = 'red';
                return false;
            }
            break;
        case 'D': // Behind
            if (!slotValue.startsWith('D')) {
                slotWarning.innerText = 'Parking slot must end with "D" for Behind.';
                slotWarning.style.display = 'block';
                slotInput.style.borderColor = 'red';
                return false;
            }
            break;
        default:
            slotWarning.style.display = 'none';
            slotInput.style.borderColor = '';
    }
    return true;
}

// Event listener for area dropdown to trigger slot validation
document.getElementById('area').addEventListener('change', validateParkingSlot);

// Event listener for slot input to validate as user types
document.getElementById('slotArea').addEventListener('input', function() {
    this.value = this.value.toUpperCase(); // Convert input to uppercase
    validateParkingSlot(); // Validate the parking slot
});



</script>

</body>
</html>