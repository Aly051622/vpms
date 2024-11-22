@ -125,279 +125,265 @@
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
            <div class="scanner-label">SCAN QR CODE <i class="fas fa-qrcode"></i></div>

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
                        $formattedTimeout = date("H:i:s m-d-Y", strtotime($row['TIMEOUT']));
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
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            // Default to prioritizing the back camera
            let backCameraIndex = cameras.findIndex(camera => camera.name.toLowerCase().includes('back'));
            selectedCameraIndex = backCameraIndex >= 0 ? backCameraIndex : 0;

            // Start the scanner with the selected camera
            startScanner(cameras[selectedCameraIndex]);

            // Add a button for switching cameras
            const switchButton = document.createElement('button');
            switchButton.textContent = "Switch Camera";
            switchButton.onclick = () => switchCamera(cameras);
            document.body.appendChild(switchButton);
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
