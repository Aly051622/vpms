<?php

session_start();

if (!isset($_SESSION['guardid'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

// Database connection
$server = "localhost";
$username = "u132092183_parkingz";
$password = "@Parkingz!2024";
$dbname = "u132092183_parkingz";

$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if the slot number already exists
function isSlotNumberExists($conn, $slotNumber) {
    $sql = "SELECT COUNT(*) as count FROM tblparkingslots WHERE SlotNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slotNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Function to get the next available slot number for an area
function getNextSlotNumber($conn, $area, $prefix) {
    $sql = "SELECT MAX(SUBSTRING(SlotNumber, 2) * 1) as max_num FROM tblparkingslots WHERE Area = ? AND SlotNumber LIKE ?";
    $stmt = $conn->prepare($sql);
    $prefixLike = $prefix . '%';
    $stmt->bind_param("ss", $area, $prefixLike);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $maxNum = $row['max_num'] ? $row['max_num'] + 1 : 1;
    return $prefix . $maxNum;
}

// Handle form submission to add a new parking slot
if (isset($_POST['add_slot'])) {
    $area = $_POST['area'];
    $status = $_POST['status'];
    $manualSlotNumber = trim($_POST['slotNumber']); // Trim whitespace

    // Validate area input
    $validAreas = ["Front Admin", "Beside CME", "Kadasig", "Behind"];
    if (!in_array($area, $validAreas)) {
        echo "<script>alert('Invalid area selected.');</script>";
    }

    // Determine the area prefix based on selected area
    $prefix = match ($area) {
        "Front Admin" => "A",
        "Beside CME" => "B",
        "Kadasig" => "C",
        "Behind" => "D",
        default => ""
    };

    // Remove the block for generating the next available slot number if manualSlotNumber is empty
    if (empty($manualSlotNumber)) {
        echo "<script>alert('Please enter a slot number.');</script>";
    } else {
        // Validate slot number format and check if it already exists
        if (!preg_match("/^$prefix\d+$/", $manualSlotNumber)) {
            echo "<script>alert('Invalid slot number! Slot number should start with $prefix and be followed by numbers.');</script>";
        } elseif (isSlotNumberExists($conn, $manualSlotNumber)) {
            echo "<script>alert('Slot number already exists! Please choose a different number.');</script>";
        } else {
            $slotNumber = $manualSlotNumber;
        }
    }

    // Insert the new slot into the database if slot number is valid
    if (isset($slotNumber)) {
        $stmt = $conn->prepare("INSERT INTO tblparkingslots (Area, SlotNumber, Status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $area, $slotNumber, $status);
        if ($stmt->execute()) {
            header("Location: monitor2.php");
            exit;
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Handle AJAX requests for updating or deleting slots
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $slotNumber = trim($_POST['slotNumber']); // Trim whitespace
        if ($_POST['action'] === 'updateStatus') {
            $status = $_POST['status'];
            $stmt = $conn->prepare("UPDATE tblparkingslots SET Status = ? WHERE SlotNumber = ?");
            $stmt->bind_param("ss", $status, $slotNumber);
            $stmt->execute();
            $stmt->close();
            echo json_encode(["status" => "success", "slotNumber" => $slotNumber, "newStatus" => $status]);
            exit;
        }

        if ($_POST['action'] === 'deleteSlot') {
            $stmt = $conn->prepare("DELETE FROM tblparkingslots WHERE SlotNumber = ?");
            $stmt->bind_param("s", $slotNumber);
            $stmt->execute();
            $stmt->close();
            echo json_encode(["status" => "success", "message" => "Slot $slotNumber deleted."]);
            exit;
        }

        // Handle status update checks
        if ($_POST['action'] === 'checkStatusUpdate') {
            $slotNumber = $_POST['slotNumber'];
            $stmt = $conn->prepare("SELECT Status FROM tblparkingslots WHERE SlotNumber = ?");
            $stmt->bind_param("s", $slotNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
                echo json_encode([
                    'slotNumber' => $slotNumber,
                    'status' => $row['Status']
                ]);
            }
            $stmt->close();
            exit;
        }
    }
}

// Fetch parking slots from the database, sorted by the numerical portion of SlotNumber
$slots_result = $conn->query("SELECT * FROM tblparkingslots ORDER BY 
    CASE 
        WHEN LEFT(SlotNumber, 1) = 'A' THEN 1
        WHEN LEFT(SlotNumber, 1) = 'B' THEN 2
        WHEN LEFT(SlotNumber, 1) = 'C' THEN 3
        WHEN LEFT(SlotNumber, 1) = 'D' THEN 4
    END, 
    CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED) ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" href="images/ctul.png">
    <link rel="shortcut icon" href="images/ctul.png">
    <title>Parking Slot Manager</title>
    <link rel="stylesheet" href="guard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
     .container{
        padding-top:10px;
        margin-top:-8px;
    }
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
        .dropbtns:hover{
            background-color: white;
            color: orange;
            border: solid orange;
        }
    @media (max-width: 480px){
   
    .navbar{
        margin-top:-10px;
        position: absolute;
        height: 100px;
    }
    .navbar-brand{
        margin-left: 10px;
        padding-bottom:2px;
    }
    .navbar-toggler{
        margin-right: 20px;
        margin-top:-6em;
    }
    .navbar-item{
        position: relative;
    }
    h4{
        margin-top: 30px;

    }
    .dropbtns{
        margin-right: 1em;
    }
}
h4{
    margin-left: 20px;
}
</style>

<nav class="navbar">
<div class="navbar-brand"><a href="monitor2.php"><h4>Parking Slot Manager</h4></a></div>
<div class="container">
    <div class="navbar-toggler" onclick="toggleMenu()"  >&#9776;</div>
    <div class="navbar-menu" id="navbarMenu" style="margin-right: 30px;">
        <!-- QR Login Button -->
        <a href="qrlogout.php" class="navbar-item dropbtns"><i class="bi bi-car-front-fill"></i> QR Log-out</a>
      

        <!-- Manual Input Button -->
        <a href="malogout.php" class="navbar-item dropbtns"><i class="bi bi-display-fill"></i> Manual Log-out</a>

        <a href="logout.php" class="navbar-item dropbtns"><i class="bi bi-car-front"></i> Logout</a>
       
    </div>
</div>
</nav>

    <style>
        /* Style for the alert prompt using CSS */
        .custom-alert {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #721c24;
            font-size: 1.2em;
            font-weight: bold;
            border: 1px solid #f5c6cb;
            width: 300px;
        }

        /*navbar add css*/
        .navbar{
            padding: 1px;
            background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            }
        #title{
            margin-left: 50px;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            #title{
                margin-top: 25px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
                margin-left: 20px;
            }
        }
        @media (max-width: 480px) {
            #title{
                margin-left: 25px;
                margin-top: 20px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
            }
        }
      
         /* Responsive adjustments */
        @media (max-width: 768px) {
            .toggle-menu {
                margin-top: -10px; /* Reduced margin for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .toggle-menu {
                margin-top: -5px; /* Further reduced margin for very small screens */
                margin-left: 35px;
            }
            body{
                margin-top: -15em;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                margin-top: 17em; /* Reduced margin for smaller screens */
            }
        }

       

        

        .slot-action{
            align-items: left;
        }
        /*slot add css*/
        .slot{
            width: 100px;
            height: 100px;
            border-radius: 15px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 
            0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            font-size: 20px;
            }
            
        .vacant {
            background-color: rgba(34, 191, 16, 0.949); 
            color: white;
        }

        .occupied {
            background-color: rgba(255, 43, 43, 0.95); 
            color: white;
        }

        /*function for slots css*/
        #stat, #areaSelect, #searchInput, #slotNumberInput {
            margin-top: 7px;
            border-radius:9px;
            cursor:text;    
            border: solid;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {

            #stat, #areaSelect, #searchInput, #slotNumberInput {
                margin-left: 7px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
            }
        }
        #stat:hover, #areaSelect:hover, #searchInput:hover, #slotNumberInput:hover{
            border:solid orange;
            background-color: aliceblue;
        }
        #areaSelect{
            border-bottom-left-radius: 9px;
            border-bottom-right-radius: 9px;
        }
        #btnFrontAdmin, #btnBesideCME, 
        #btnKadasig, #btnBehind {
            background-color: rgb(53, 97, 255);        
            color: white;
            border-radius: 9px;
            border: 2px solid white;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #btnFrontAdmin:hover, #btnBesideCME:hover, #btnKadasig:hover,#btnsearch:hover, 
        #btnBehind:hover, #btnadd:hover, #btnsearch:hover .slot-action button:hover{
            background-color: white;
            color: darkblue;
            border: solid 2px blue;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }

        .legend {
            margin-top: -40px;
            margin-left: 50px;
            display: block;
            align-items: flex-start; 
        }

        /* Adding flexbox for better alignment */
        .legend-container {
            display: flex;
            flex-wrap: wrap; 
            justify-content: flex-start;
        }

        .v-legend {
            color: rgba(34, 191, 16, 0.949);
            margin-right: 10px; 
        }

        .o-legend {
            color: rgba(255, 43, 43, 0.95);
            margin-right: 10px; 
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .legend {
                margin-left: 20px; 
                margin-top: -18px; 
            }

            .v-legend,
            .o-legend {
                font-size: 16px; 
                margin-right: 10px; 
            }
        }

        @media (max-width: 480px) {
            .legend {
                margin-left: 10px; 
                margin-top: 3px;  
            }

            .v-legend,
            .o-legend {
                font-size: 16px;
                margin-right: 10px; 
            }
        }
        .dropdown-content{
            font-size: 12px;
            border-bottom-left-radius: 9px;
            border-bottom-right-radius: 9px;
            margin-top: 0px;
            width: 135px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        .dropdown-content a:hover {
            background-color: #f3ab19e0;
            color:white;
        }
        #drp-con2,  #drp-con1{
                margin-top: -2px;
                width: 82%; 
                text-align: center; 
                padding: -1px;
                z-index:1007;
            }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .dropbtns {
                margin-top: 9px;
                display: inline;
                position: relative;
                z-index: 1006;
            }

            #drp-con1{
                margin-top: -7px; 
                width: 40%; 
                text-align: center; 
                padding: 0px;
                z-index:1007;
                margin-left: 20px;
                position: absolute;
            }
            #drp-con2 {
                margin-top: -7px; 
                width: 40%; 
                text-align: center; 
                padding: -1px;
                z-index:1007;
                margin-left: 195px;
                position: absolute;
            }
            #bt1{
                margin-bottom: 8px; 
                width: 40%;
                text-align: center;
                padding: 5px;
                margin-left: 20px;  
            }
            #bt2{
                margin-left: 195px;
                margin-top: -42px;
                width: 40%;
                padding: 5px;
                z-index: 1006;
            }
        }


        .search, .add {
                color: white;
                padding: 8px;
                font-size: 16px;
                border: solid 2px white;
                background: #2fadce;
                cursor: pointer;
                border-radius: 9px;
                font-weight: bold;
                margin-right: 30px;
                box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }

    .search:hover, .add:hover{
        background-color: white;
        color: darkblue;
        box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    }
    .search-slot{
        margin-top: 7em;
    }
    </style>
</head>

    <div class="container">
            <!-- Search Slot -->
<div class="search-slot">
    <input type="text" id="searchInput" placeholder="Enter Slot Number or Prefix" maxlength="10">
    <button onclick="filterSlots()" class="search">Search</button> <!-- Added Search Button -->
</div>


        <!-- Add New Slot -->
        <form method="POST" action="monitor.php">
            <div class="add-slot">
                <select name="area" id="areaSelect"> 
                    <option value="Front Admin" selected>A</option>
                    <option value="Beside CME">B</option>
                    <option value="Kadasig">C</option>
                    <option value="Behind">D</option>
                </select>
                <select name="status" id="areaSelect">
                    <option value="Vacant">Vacant</option>
                    <option value="Occupied">Occupied</option>
                </select>
                <input type="text" name="slotNumber" id="slotNumberInput" placeholder="Enter Slot Number (or leave empty for auto)" maxlength="10">
                <button type="submit" name="add_slot" class="add">Add Slot</button>
            </div>
        </form>

        <!-- Select Area -->
        <div class="select-area">
    <button id="btnFrontAdmin" onclick="selectArea('Front Admin')">A</button>
    <button id="btnBesideCME" onclick="selectArea('Beside CME')">B</button>
    <button id="btnKadasig" onclick="selectArea('Kadasig')">C</button>
    <button id="btnBehind" onclick="selectArea('Behind')">D</button>
</div>
</div>
        
          <!-- Slot's Legend -->
          <div class="legend">
            <span class="v-legend"><i class="bi bi-square-fill"></i> Vacant</span><br>
            <span class="o-legend"><i class="bi bi-dash-square-fill"></i> Occupied</span>
        </div>
        
        <!-- Slots Display -->
        <div class="slots-display" id="slotsDisplay">
            <?php while ($row = $slots_result->fetch_assoc()): 
                $area_class = strtolower(str_replace(' ', '-', $row['Area'])); ?>
                <div class="slot <?= $area_class ?> <?= $row['Status'] === 'Vacant' ? 'vacant' : 'occupied' ?>" 
                     data-slot-number="<?= htmlspecialchars($row['SlotNumber']) ?>" 
                     data-status="<?= htmlspecialchars($row['Status']) ?>" 
                     onclick="toggleSlotButtons('<?= htmlspecialchars($row['SlotNumber']) ?>')">
                    <?= htmlspecialchars($row['SlotNumber']) ?>

                    <!-- Slot Actions: Vacant, Occupied, Delete -->
                    <div class="slot-actions" id="slotActions-<?= htmlspecialchars($row['SlotNumber']) ?>" style="display:none;">
                        <button class="status-btn vacant-btn" onclick="updateSlotStatus('<?= htmlspecialchars($row['SlotNumber']) ?>', 'Vacant')">Vacant</button>
                        <button class="status-btn occupied-btn" onclick="updateSlotStatus('<?= htmlspecialchars($row['SlotNumber']) ?>', 'Occupied')">Occupied</button>
                        <button class="delete-btn" onclick="deleteSlot('<?= htmlspecialchars($row['SlotNumber']) ?>')">Delete</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="guard.js"></script>
<script>

                // Polling interval
        setInterval(checkSlotStatusUpdates, 1000);

// Check slot status updates
function checkSlotStatusUpdates() {
    const slots = document.querySelectorAll('.slot');
    slots.forEach(slot => {
        const slotNumber = slot.getAttribute('data-slot-number');
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "monitor2.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.slotNumber && response.status) {
                    const updatedSlot = document.querySelector(`[data-slot-number='${response.slotNumber}']`);
                    if (updatedSlot && updatedSlot.getAttribute('data-status') !== response.status) {
                        updatedSlot.setAttribute('data-status', response.status);
                        updatedSlot.classList.remove('vacant', 'occupied');
                        updatedSlot.classList.add(response.status.toLowerCase());
                        updatedSlot.querySelector('span.status').textContent = response.status;
                    }
                }
            }
        };
        xhr.send("action=checkStatusUpdate&slotNumber=" + encodeURIComponent(slotNumber));
    });
}

    
    // Function to toggle the menu visibility
function toggleMenu() {
    const navbarMenu = document.getElementById('navbarMenu');
    // Toggle the class 'active' to show or hide the dropdown menu
    navbarMenu.classList.toggle('active');
}


    // JavaScript to filter slots based on search input
function filterSlots() {
    const searchInput = document.getElementById('searchInput').value.toUpperCase();
    const slots = document.querySelectorAll('.slot');

    slots.forEach(slot => {
        const slotNumber = slot.getAttribute('data-slot-number').toUpperCase();
        
        // Display slot if it matches any part of the search input (prefix, number, or both)
        if (slotNumber.includes(searchInput)) {
            slot.style.display = 'inline-block';
        } else {
            slot.style.display = 'none';
        }
    });
}


     // Remember selected area in dropdown
     document.addEventListener("DOMContentLoaded", function() {
            const areaSelect = document.getElementById("areaSelect");
            const selectedArea = localStorage.getItem("selectedArea");
            if (selectedArea) {
                areaSelect.value = selectedArea;
            }

            areaSelect.addEventListener("change", function() {
                localStorage.setItem("selectedArea", areaSelect.value);
            });
        });

        // Remember selected area for buttons
        function selectArea(area) {
            localStorage.setItem("selectedArea", area);
            document.getElementById("areaSelect").value = area;
        }

    // Toggle slot buttons for status change and delete
    function toggleSlotButtons(slotNumber) {
        const actions = document.getElementById(`slotActions-${slotNumber}`);
        actions.style.display = actions.style.display === 'none' ? 'block' : 'none';
    }

     // AJAX function to update slot status
     function updateSlotStatus(slotNumber, status) {
            const data = new FormData();
            data.append('action', 'updateStatus');
            data.append('slotNumber', slotNumber);
            data.append('status', status);

            fetch('monitor2.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const slot = document.querySelector(`.slot[data-slot-number="${data.slotNumber}"]`);
                    slot.classList.remove('vacant', 'occupied');
                    slot.classList.add(data.newStatus.toLowerCase());
                    alert(`Slot ${data.slotNumber} updated to ${data.newStatus}.`);
                }
            });
        }

        // AJAX function to delete a slot
        function deleteSlot(slotNumber) {
            const data = new FormData();
            data.append('action', 'deleteSlot');
            data.append('slotNumber', slotNumber);

            fetch('monitor2.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const slot = document.querySelector(`.slot[data-slot-number="${slotNumber}"]`);
                    slot.remove();
                    alert(data.message);
                }
            });
        }

    // Automatically set a default area if none is selected
    if (!localStorage.getItem('selectedArea')) {
        localStorage.setItem('selectedArea', 'Front Admin');
    }
</script>

</body>
</html>
