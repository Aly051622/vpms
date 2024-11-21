<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .left-panelbg {
        font-size: 12px;
    }
</style>

<div class="left-panelbg">
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="dashboard.php"><i class="menu-icon fa fa-dashboard"></i>Dashboard</a>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-list-alt"></i>Vehicle Category
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-road"></i><a href="add-category.php">Add Vehicle Category</a></li>
                            <li><i class="menu-icon fa bi-p-square-fill"></i><a href="manage-category.php">Manage Vehicle Category</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="add-vehicle.php"><i class="menu-icon fa fa-user-circle-o"></i>Add Vehicle</a>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-th"></i>Manage Vehicle
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-user-circle-o"></i><a href="manage-reg.php">Manage Registered Client Vehicles</a></li>
                            <li><i class="menu-icon bi bi-car-front-fill"></i><a href="manage-incomingvehicle.php">Manage In Vehicle</a></li>
                            <li><i class="menu-icon bi bi-car-front"></i><a href="manage-outgoingvehicle.php">Manage Out Vehicle</a></li>
                        </ul>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-solid fa-qrcode"></i>QR Code Scanner
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa bi bi-qr-code-scan"></i><a href="qrlogin.php">Login Scanner</a></li>
                            <li><i class="menu-icon fa bi bi-qr-code"></i><a href="qrlogout.php">Logout Scanner</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="search-vehicle.php"><i class="menu-icon fa fa-search"></i>Search Vehicle</a>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-newspaper-o"></i>Reports
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-calendar"></i><a href="bwdates-report-ds.php">Between Dates Reports</a></li>
                        </ul>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa bi bi-card-checklist"></i>Validation
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa bi bi-journal-text"></i><a href="validation.php">Validate</a></li>
                            <li><i class="menu-icon fa bi bi-journal-check"></i><a href="validated.php">Validated</a></li>
                            <li><i class="menu-icon fa bi bi-journal-x"></i><a href="unvalidated.php">Unvalidated</a></li>
                            <li><i class="menu-icon fa bi bi-journal-minus"></i><a href="invalidated.php">InValidated</a></li>
                        </ul>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-address-book"></i>Client Management</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-user-circle-o"></i><a href="register.php">Register Client</a></li>
                            <li>
                                <i class="menu-icon fa fa-address-book"></i>
                                <a href="#" onclick="showPasswordModal()">User Information</a>
                            </li>
                            <li><i class="menu-icon fa bi-chat-dots-fill"></i><a href="admin_comments.php">Comment</a></li>
                            <li><i class="menu-icon fa bi-envelope-paper-heart"></i><a href="admin_feedbacks.php">Feedback</a></li>
                            <li><i class="menu-icon fa bi-headset"></i><a href="admin_service.php">Customer Service</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" onclick="showPasswordModal()">
                            <i class="menu-icon fa bi-geo-fill"></i> Credentials
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
</div>

<!-- Password Modal -->
<div id="passwordModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background:white; border:1px solid #ccc; box-shadow:0 4px 8px rgba(0,0,0,0.2); z-index:1000;">
    <h3>Enter Password</h3>
    <input type="password" id="passwordInput" placeholder="Enter password" style="padding:10px; width:100%; margin-bottom:10px;" />
    <button onclick="validatePassword()" style="padding:10px 20px; background:#007bff; color:white; border:none; cursor:pointer;">Submit</button>
    <button onclick="closePasswordModal()" style="padding:10px 20px; background:#ccc; color:black; border:none; cursor:pointer;">Cancel</button>
</div>

<!-- Modal Background -->
<div id="modalBackground" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="closePasswordModal()"></div>

<script>
function showPasswordModal() {
    document.getElementById('passwordModal').style.display = 'block';
    document.getElementById('modalBackground').style.display = 'block';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
    document.getElementById('modalBackground').style.display = 'none';
}

function validatePassword() {
    const password = document.getElementById('passwordInput').value.trim(); // Trim spaces

    // Correct password check (adjust as necessary)
    const correctPassword = "information"; // Replace with your secure password

    if (!password) {
        alert("Please enter a password.");
        return;
    }

    if (password.toLowerCase() === correctPassword.toLowerCase()) {
        window.location.href = "reg-users.php"; // Redirect to the appropriate page
    } else {
        alert("Invalid password. Access denied.");
    }
}
</script>
