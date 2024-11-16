<?php

// Include the database connection file
include('../DBconnection/dbconnection.php');

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
            header("Location: monitor.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Slot Manager</title>
    <link rel="stylesheet" href="guard.css">

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

      
    </style>
</head>
<body>
    <!-- Responsive Navigation Bar -->
    <?php include_once('includes/headerout.php');?>

    <div class="container">
        <h1>Parking Slot Manager</h1>
        <!-- Search Slot -->
        <a href="qrlogin.php">Log-in</a>
                    <a href="qrlogout.php">Log-out</a>
                    <a href="test.php">Test</a>
            <!-- Search Slot -->
<div class="search-slot">
    <input type="text" id="searchInput" placeholder="Enter Slot Number or Prefix" maxlength="10">
    <button onclick="filterSlots()">Search</button> <!-- Added Search Button -->
</div>


        <!-- Add New Slot -->
        <form method="POST" action="monitor.php">
            <div class="add-slot">
                <select name="area" id="areaSelect">
                    <option value="Front Admin" selected>Front Admin</option>
                    <option value="Beside CME">Beside CME</option>
                    <option value="Kadasig">Kadasig</option>
                    <option value="Behind">Behind</option>
                </select>
                <input type="text" name="slotNumber" id="slotNumberInput" placeholder="Enter Slot Number (or leave empty for auto)" maxlength="10">
                <select name="status">
                    <option value="Vacant">Vacant</option>
                    <option value="Occupied">Occupied</option>
                </select>
                <button type="submit" name="add_slot">Add Slot</button>
            </div>
        </form>

        <!-- Select Area -->
        <div class="select-area">
    <button id="btnFrontAdmin" onclick="selectArea('Front Admin')">Front Admin</button>
    <button id="btnBesideCME" onclick="selectArea('Beside CME')">Beside CME</button>
    <button id="btnKadasig" onclick="selectArea('Kadasig')">Kadasig</button>
    <button id="btnBehind" onclick="selectArea('Behind')">Behind</button>
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
        xhr.open("POST", "monitor.php", true);
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

            fetch('monitor.php', {
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

            fetch('monitor.php', {
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
