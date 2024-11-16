<?php
// Include the database connection file
include('../DBconnection/dbconnection.php');


// Function to check if the slot number already exists
function isSlotNumberExists($con, $slotNumber) {
    $sql = "SELECT COUNT(*) as count FROM tblparkingslots WHERE SlotNumber = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $slotNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Function to get the next available slot number for an area
function getNextSlotNumber($con, $area, $prefix) {
    $sql = "SELECT MAX(SUBSTRING(SlotNumber, 2) * 1) as max_num FROM tblparkingslots WHERE Area = ? AND SlotNumber LIKE ?";
    $stmt = $con->prepare($sql);
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
        } elseif (isSlotNumberExists($con, $manualSlotNumber)) {
            echo "<script>alert('Slot number already exists! Please choose a different number.');</script>";
        } else {
            $slotNumber = $manualSlotNumber;
        }
    }

    // Insert the new slot into the database if slot number is valid
    if (isset($slotNumber)) {
        $stmt = $con->prepare("INSERT INTO tblparkingslots (Area, SlotNumber, Status) VALUES (?, ?, ?)");
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
        if (isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
            $status = $_POST['status'];
            $stmt = $con->prepare("UPDATE tblparkingslots SET Status = ? WHERE SlotNumber = ?");
            $stmt->bind_param("ss", $status, $slotNumber);
            $stmt->execute();
            $stmt->close();
            echo "Slot $slotNumber marked as $status.";
            exit;
        }

        if (isset($_POST['action']) && $_POST['action'] === 'deleteSlot') {
            $stmt = $con->prepare("DELETE FROM tblparkingslots WHERE SlotNumber = ?");
            $stmt->bind_param("s", $slotNumber);
            $stmt->execute();
            $stmt->close();
            echo "Slot $slotNumber deleted.";
            exit;
        }
    }
}

// Fetch parking slots from the database, sorted by the numerical portion of SlotNumber
// Fetch parking slots from the database, sorted by Area prefix (A, B, C, D) and then by numeric portion
$slots_result = $con->query("SELECT * FROM tblparkingslots ORDER BY 
    CASE 
        WHEN LEFT(SlotNumber, 1) = 'A' THEN 1
        WHEN LEFT(SlotNumber, 1) = 'B' THEN 2
        WHEN LEFT(SlotNumber, 1) = 'C' THEN 3
        WHEN LEFT(SlotNumber, 1) = 'D' THEN 4
    END, 
    CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED) ASC");


// Function to fetch and display slots
function fetchAndDisplaySlots($con, $area, $prefix) {
    $sql = "SELECT SlotNumber, Status FROM tblparkingslots WHERE Area = ? AND SlotNumber LIKE ? ORDER BY CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED)";
    $stmt = $con->prepare($sql);
    $prefixLike = $prefix . '%';
    $stmt->bind_param("ss", $area, $prefixLike);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display slots with appropriate styles
    while ($row = $result->fetch_assoc()) {
        $slotClass = $row['Status'] === 'Occupied' ? 'occupied' : 'vacant';
        echo "<div class='slot $slotClass' data-slot='{$row['SlotNumber']}'>";
        echo htmlspecialchars($row['SlotNumber']) . " (" . htmlspecialchars($row['Status']) . ")";
        echo "</div>";
    }
}
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
    <?php include_once('includes/headerin.php');?>

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

    // Function to update slot status via AJAX
    function updateSlotStatus(slotNumber, status) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "monitor.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert(xhr.responseText);
                window.location.reload(); // Reload page after updating
            }
        };
        xhr.send("action=updateStatus&slotNumber=" + encodeURIComponent(slotNumber) + "&status=" + encodeURIComponent(status));
    }

    // Function to delete a slot via AJAX
    function deleteSlot(slotNumber) {
        if (confirm("Are you sure you want to delete this slot?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "monitor.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    window.location.reload(); // Reload page after deleting
                }
            };
            xhr.send("action=deleteSlot&slotNumber=" + encodeURIComponent(slotNumber));
        }
    }

    // Automatically set a default area if none is selected
    if (!localStorage.getItem('selectedArea')) {
        localStorage.setItem('selectedArea', 'Front Admin');
    }
</script>

</body>
</html>
