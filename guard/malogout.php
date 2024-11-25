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

$response = ['exists' => false, 'vehicle' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    
        $query = "DELETE FROM tblmanual_logout WHERE ID = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            echo json_encode(['success' => $stmt->affected_rows > 0]);
            $stmt->close();
        }
        exit;
    }

    if (isset($_POST['contactNumber'])) {
        $contactNumber = $_POST['contactNumber'];
    
        // Modify the query to select only the most recent login entry for the given contact number
        $query = "SELECT * FROM tblmanual_login WHERE OwnerContactNumber = ? ORDER BY TimeIn DESC LIMIT 1"; 
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        
        $stmt->bind_param("s", $contactNumber);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $response['exists'] = true;
            $response['vehicle'] = $result->fetch_assoc();
    
            $logoutQuery = "INSERT INTO tblmanual_logout (OwnerName, OwnerContactNumber, VehicleCategory, RegistrationNumber, ParkingSlot, TimeOut) 
                            VALUES (?, ?, ?, ?, ?, NOW())";
            $logoutStmt = $conn->prepare($logoutQuery);
            
            if ($logoutStmt) {
                $logoutStmt->bind_param(
                    "sssss",
                    $response['vehicle']['OwnerName'],
                    $response['vehicle']['OwnerContactNumber'],
                    $response['vehicle']['VehicleCategory'],
                    $response['vehicle']['RegistrationNumber'],
                    $response['vehicle']['ParkingSlot']
                );
                $logoutStmt->execute();
                $logoutStmt->close();
            }
        }
        
        echo json_encode($response);
        exit;
    }    
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalCountQuery = "SELECT COUNT(*) AS count FROM tblmanual_logout WHERE DATE(Timeout) = CURDATE()";
$totalCountResult = $conn->query($totalCountQuery);
$totalCountRow = $totalCountResult->fetch_assoc();
$totalCount = $totalCountRow['count'];
$totalPages = ceil($totalCount / $limit);

$vehiclesQuery = "SELECT * FROM tblmanual_logout WHERE DATE(TimeOut) = CURDATE() ORDER BY TimeOut DESC LIMIT $limit OFFSET $offset";
$vehiclesResult = $conn->query($vehiclesQuery);


if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    while ($row = $vehiclesResult->fetch_assoc()) {
        $formattedTimeOut = date('h:i:s A m-d-y', strtotime($row['TimeOut']));
        echo "<tr>
            <td>" . htmlspecialchars($row['ID']) . "</td>
            <td>" . htmlspecialchars($row['OwnerName']) . "</td>
            <td>" . htmlspecialchars($row['OwnerContactNumber']) . "</td>
            <td>" . htmlspecialchars($row['VehicleCategory']) . "</td>
            <td>" . htmlspecialchars($row['RegistrationNumber']) . "</td>
            <td>" . htmlspecialchars($row['ParkingSlot']) . "</td>
            <td>" . $formattedTimeOut . "</td>
            <td><button onclick=\"deleteVehicle('" . $row['ID'] . "')\" class=\"btn btn-danger btn-sm\">Delete</button></td>
        </tr>";
    }
    exit;
}
?>



    <html class="no-js" lang="">
    <head>
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
        <title>QR Code Logout | Parking System</title>

        <style>
            body {
                color: black;
                background-color:whitesmoke;
                height: 100vh;
            }
            .container {
                max-width: 1000px;
                text-align: center;
            }
            .navbar{
                background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
                box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                    rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                    rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            }
            .submit {
                width: 100%;
                font-size: 1.2em;
                color: white;
            padding: 8px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background-color: rgb(53, 97, 255);
            border-radius: 9px;
            font-weight: bold;
            border: solid;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            }
            .submit:hover{
                background-color: darkblue;
            color: white;
            border: solid blue;
            border-radius: 9px;
            }
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
                width: 300px;
                text-align: center;
            }
            .modal-content.success {
                border: 2px solid green;
            }
            .modal-content.error {
                border: 2px solid red;
            }
            .close-button {
                border: none;
                border-radius: 4px;
                padding: 5px 10px;
                font-size: 14px;
                cursor: pointer;
            }
            .close-button.error {
                background-color: #d9534f;
                color: white;
            }
            .close-button.error:hover {
                background-color: #c9302c;
            }
            .close-button.success {
                background-color: #5cb85c;
                color: white;
            }
            .close-button.success:hover {
                background-color: #4cae4c;
            }
            #btn-sm{
                border: solid darkred;
                border-radius: 10px;
                padding: 10px;
                background-color: red;
                color: white;
                cursor: pointer;
                font-family: 'Montserrat',sans-serif;
                font-weight: bolder;
        }
        #btn-sm:hover{
            background-color: darkblue;
            border: solid blue;
        }
        #btn-danger{
                border: solid darkred;
                border-radius: 10px;
                padding: 10px;
                background-color: red;
                color: white;
                cursor: pointer;
                font-family: 'Montserrat',sans-serif;
                font-weight: bolder;
        }
        #btn-danger:hover{
            background-color: darkblue;
            border: solid blue;
        }
        </style>
    </head>
    <body>
            <!-- Responsive Navigation Bar -->
    <?php include_once('includes/headerout.php');?>

        <!-- Vehicle Information Table -->
        <div class="container " style="margin-top: 5em; width: 500px;">
        <h2>Logout Vehicle</h2>
        <form id="logoutForm" method="post">
            <div class="form-group">
                <label for="contactNumber">Contact Number:</label>
                <input type="text" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number">
            </div>
            <button type="submit" class="submit">Submit</button>    
        </form>

        <!-- Error Modal -->
        <div id="errorModal" class="modal-overlay">
            <div class="modal-content error">
                <p>Contact number does not exist.</p>
                <button class="close-button error" onclick="closeModal('errorModal')">Close</button>
            </div>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="modal-overlay">
            <div class="modal-content success">
                <p>User Logged Out Successfully!</p>
                <button class="close-button success" onclick="closeModal('successModal')">Close</button>
            </div>
        </div>
    </div>


    <div class="container mt-5" id="table">
            <h2>Vehicle Information</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Plate Number</th>
                        <th>Parking Slot</th>
                        <th>TIMEOUT</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="vehicleTableBody">
    <?php
        $count = $vehiclesResult->num_rows; // Get the total count of results
        while ($row = $vehiclesResult->fetch_assoc()) {
            // Format the TIMEOUT column in 24-hour format with AM/PM
            $formattedTimeOut = date('h:i:s A m-d-y', strtotime($row['TimeOut']));
            echo "<tr>
                <td>" . htmlspecialchars($row['ID']) . "</td>
                <td>" . htmlspecialchars($row['OwnerName']) . "</td>
                <td>" . htmlspecialchars($row['OwnerContactNumber']) . "</td>
                <td>" . htmlspecialchars($row['VehicleCategory']) . "</td>
                <td>" . htmlspecialchars($row['RegistrationNumber']) . "</td>
                <td>" . htmlspecialchars($row['ParkingSlot']) . "</td>
                <td>" . $formattedTimeOut . "</td>
                <td><button onclick=\"deleteVehicle('" . $row['ID'] . "')\" class=\"btn btn-danger btn-sm\">Delete</button></td>
            </tr>";
            $count--; // Decrement the count for the next row
        }
    ?>
    </tbody>

            </table>
            <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link pagination-link" data-page="<?php echo $page - 1; ?>" href="#">Previous</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link pagination-link" data-page="<?php echo $i; ?>" href="#"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link pagination-link" data-page="<?php echo $page + 1; ?>" href="#">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
        </div>

    <script>

document.addEventListener('DOMContentLoaded', () => {
    function loadPage(page) {
        fetch(`malogout.php?ajax=1&page=${page}`, {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('vehicleTableBody').innerHTML = data;
            updatePaginationLinks(page);
        })
        .catch(error => console.error('Error fetching page data:', error));
    }

    function updatePaginationLinks(activePage) {
        document.querySelectorAll('.pagination-link').forEach(link => {
            link.closest('li').classList.toggle('active', link.getAttribute('data-page') == activePage);
        });
    }

    document.querySelectorAll('.pagination-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = e.target.getAttribute('data-page');
            loadPage(page);
        });
    });
});

    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function deleteVehicle(vehicleId) {
        if (confirm("Are you sure you want to delete this record?")) {
            fetch('malogout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${vehicleId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`button[onclick="deleteVehicle('${vehicleId}')"]`).closest('tr');
                    if (row) row.remove();
                } else {
                    alert("Error: Could not delete the record.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error: Could not delete the record.");
            });
        }
    }

    document.getElementById('logoutForm').addEventListener('submit', function(event) {
        event.preventDefault();
        let contactNumber = document.getElementById('contactNumber').value;

        if (contactNumber) {
            fetch('malogout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `contactNumber=${contactNumber}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    const vehicle = data.vehicle;
                    const vehicleTableBody = document.getElementById('vehicleTableBody');
                    const newRow = document.createElement('tr');

                    newRow.innerHTML = 
                        `<td></td>
                        <td>${vehicle.OwnerName}</td>
                        <td>${vehicle.OwnerContactNumber}</td>
                        <td>${vehicle.VehicleCategory}</td>
                        <td>${vehicle.RegistrationNumber}</td>
                        <td>${vehicle.ParkingSlot}</td>
                        <td>${formatDateTime(new Date())}</td>
                        <td><button onclick="deleteVehicle('${vehicle.ID}')" class="btn btn-danger btn-sm">Delete</button></td>`;

                    vehicleTableBody.insertBefore(newRow, vehicleTableBody.firstChild);

                    Array.from(vehicleTableBody.rows).forEach((row, index) => {
                        row.cells[0].textContent = vehicleTableBody.rows.length - index;
                    });

                    showModal('successModal');
                } else {
                    showModal('errorModal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('errorModal');
            });
        } else {
            showModal('errorModal');
        }
    });

    function formatDateTime(date) {
        const hours = date.getHours() % 12 || 12;
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const ampm = date.getHours() >= 12 ? 'PM' : 'AM';
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const year = date.getFullYear();

        return `${hours}:${minutes} ${ampm} ${month}/${day}/${year}`;
    }
    </script>

    </body>
    </html>
