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
        <title>QR Code Logout | Parking System</title>

        <style>
  /* Body and Container */
  body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    color: black;
    background-color: #f9fcff;
    background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
        }
        .form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    margin-top: 10px;
    margin-bottom: 20px;
  }

/* Adjust Input Field Width */
.form-container .form-group input {
    width: 100%; /* Ensure input fields are wide */
    max-width: 700px; /* Limit maximum width */
    margin: 10px auto; /* Center-align the input fields */
    display: block;
    padding: 10px;
    font-size: 1.1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}
        .hidden-field{
            display: none;
        }
        h2, h3 {
    text-align: center;
    margin-top: 20px;
    margin-bottom: 20px;
  }

/* Center the Search Button */
.form-container form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-container .btn-primary {
    display: inline-block;
    border: solid lightgray;
                border-radius: 10px;
                padding: 10px;
                background-color: rgb(53, 97, 255);
    font-size: 1.1rem;
    color: white;
    border-radius: 10px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s ease;
}

.form-container .btn-primary:hover {
    background-color: darkblue;
    border: 1px solid blue;
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
                align-items: center;
                text-align: center;
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
    margin: 0 auto; /* Center the table container */
    text-align: center; /* Center-align content within */
    max-width: 90%; /* Adjust width as needed */
    display: block;
    overflow-x: auto;
}
    .table {
        margin: 20px auto; /* Center the table */
    width: 100%; /* Full width */ 
        table-layout: auto; /* Ensure columns fit within the table width */
        border-collapse: collapse;
        background-color: #f9fcff;
        text-align: center; /* Center-align content within */
    }
    .table th, .table td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
        white-space: normal; /* Allow text wrapping */
        font-size: 1rem;
        word-wrap: break-word;
        white-space: nowrap;
    }
    .table th {
        font-weight: bold;
        background-color: #e0e6ed;
    }
    .table td {
        background-color: #ffffff;
    }
    /* Column Responsiveness */
    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 0.9rem;
            padding: 6px;
        }
        .container {
        margin-left: 0;
        padding: 10px;
    }

    .navbar-toggler {
        margin-left: 1em;
    }

    .btn-primary, .btn-success, .btn-danger, .btn-warning {
        font-size: 0.8rem; /* Smaller font for buttons on small screens */
        padding: 8px;
    }

    .form-group label, .form-control {
        font-size: 0.9rem;
    }

    .modal-content {
        width: 90%; /* Full width modal for small screens */
    }
    .form-container .form-group input {
        width: 90%; /* Reduce width for smaller screens */
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
        /* Extra small screens */
@media (max-width: 480px) {
    h2, h3 {
        font-size: 1.5rem;
    }

    .dropbtns {
        font-size: 0.8rem;
        padding: 6px;
    }

    .navbar-brand {
        font-size: 1rem;
    }

    .table th, .table td {
        font-size: 0.9rem;
        padding: 6px;
    }
}





.container{
    padding: 10px;
    margin: auto;
    max-width: 1200px;
    width: 90%;
    }
    .navbar {
    background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px,
                rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    padding: 0.5em 1em;
}

.navbar a {
    text-decoration: none;
    color: white;
    font-size: 1.2em;
    font-weight: bold;
    margin-left: 10px;
}

.navbar a:hover {
    color: orange; /* Highlight effect */
}
    /*qrbutton add css*/
    .dropbtns{
            color: white;
            padding: 8px 15px;
            font-size: 1em;
            border: none;
            cursor: pointer;
            background-color: orange;
            border-radius: 8px;
            font-weight: bold;
            border: solid;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            transition: all 0.3s ease-in-out;
        }
        .dropbtns:hover{
            background-color: white;
            color: orange;
            border: 2px solid orange;
        }
        .navbar-toggler {
    font-size: 1.5em;
    color: white;
    cursor: pointer;
    display: none; /* Hidden by default */
    border: none;
    background: none;
}
/* Smaller Button Styling When Menu is Toggled */
.navbar-menu.show .dropbtns {
    padding: 5px 10px;
    font-size: 0.9em;
    border-radius: 5px;
}

    @media (max-width: 480px){
    .container{
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

/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .navbar-toggler {
        display: block; /* Show toggler on small screens */
    }

    .navbar-menu {
        display: none; /* Hidden by default */
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        background-color: #1e3c72;
        padding: 10px 0;
    }

    .navbar-menu a {
        margin: 10px 20px;
    }

    .navbar-menu.show {
        display: flex; /* Show menu when toggled */
    }
}


/* Center Pagination */
.pagination {
    display: flex;
    justify-content: center; /* Center-align pagination */
    margin-top: 20px;
}

.pagination .page-link {
    margin: 0 5px; /* Add spacing between pagination links */
    padding: 8px 12px; /* Adjust padding */
    text-decoration: none;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.pagination .page-link.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
</style>
</head>
<body>

<nav class="navbar">
<div class="container">
    <div class="navbar-brand"><a href="monitor.php">Parking Slot Manager</a></div>
    <div class="navbar-toggler" onclick="toggleMenu()">&#9776;</div>
    <div class="navbar-menu" id="navbarMenu">
        <!-- QR Login Button -->
        <a href="qrlogin.php" class="navbar-item dropbtns"><i class="bi bi-car-front-fill"></i> QR Log-in</a>
      

        <!-- Manual Input Button -->
        <a href="malogin.php" class="navbar-item dropbtns"><i class="bi bi-display-fill"></i> Manual Log-in</a>

        <a href="logout.php" class="navbar-item dropbtns"><i class="bi bi-house-fill"></i> Home</a>
       
    </div>
</div>
</nav>

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
