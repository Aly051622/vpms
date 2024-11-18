<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .left-panelbg {
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            padding-bottom: 5px;
            font-size:12px;
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
                            <li><i class="fa fa-road"></i><a href="add-category.php">Add Vehicle Category</a></li>
                            <li><i class="fa fa-road"></i><a href="manage-category.php">Manage Vehicle Category</a></li>
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
                            <li><i class="menu-icon fa fa-automobile"></i><a href="manage-incomingvehicle.php">Manage In Vehicle</a></li>
                            <li><i class="menu-icon fa fa-automobile"></i><a href="manage-outgoingvehicle.php">Manage Out Vehicle</a></li>
                        </ul>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa bi bi-qr-code"></i>QR Code Scanner
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa-qrcode"></i><a href="qrlogin.php">Login Scanner</a></li>
                            <li><i class="menu-icon fa-qrcode"></i><a href="qrlogout.php">Logout Scanner</a></li>
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

                    <li>
                        <a href="reg-users.php"><i class="menu-icon fa fa-address-book"></i>Client Information</a>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa bi bi-card-checklist"></i>Validation
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa bi bi-journal-text"></i><a href="validation.php">Validate</a></li>
                            <li><i class="menu-icon fa bi bi-journal-check"></i><a href="validated.php">Validated</a></li>
                            <li><i class="menu-icon fa bi bi-journal-x"></i><a href="unvalidated.php">Unvalidated</a></li>
                            <li><i class="menu-icon bi bi-journal-minus"></i><a href="invalidated.php">InValidated</a>

                        </ul>
                    </li>

                    <li>
                        <a href="register.php"><i class="menu-icon fa fa-user-circle-o"></i>Register Client</a>
                    </li>

                    <li>
                        <a href="manage-slot.php"><i class="menu-icon fa  bi bi-geo-fill"></i> Add Area and Slot</a>
                    </li>

                    <li>
                        <a href="admin_comments.php"><i class="menu-icon  fa bi bi-chat-dots-fill"></i> Comment</a>
                    </li>

                    <li>
                        <a href="admin_feedbacks.php"><i class="menu-icon fa  bi bi-envelope-paper-heart"></i> Feedback</a>
                    </li>

                    <li>
                        <a href="admin_service.php"><i class="menu-icon fa  bi bi-headset"></i> Customer Service</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
</div>
