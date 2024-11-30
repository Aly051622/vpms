<?php
session_start();
include('includes/auth_check.php'); // Ensure admin is logged in
include('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />

    <title>Admin Customer Service | CTU DANAO Parking System</title>

    
<!--header-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    #header{
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .left-panel{
        text-decoration: none !important;
    }
    .nav-link:hover{
        border-radius: 4px;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    #hh{
        box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;        
        font: 16x;
           }
     /* logout message */
     .alert-message {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        background-color: red;
        color: white;
        font-weight: bold;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
  
/* modal for logout */
.modal {
    display: none; 
    position: fixed;
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
}
.modal-content {
    background: whitesmoke;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 300px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal-content button {
    margin: 10px;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    color: black;
    cursor: pointer;
      color: white;
      font-size: 18px;
      letter-spacing: 1px;
      font-weight: 600;
      font-family: 'Montserrat',sans-serif;
      background: whitesmoke;
    border: 1px solid white;
}

.modal-content button:first-of-type {
    background-color:#2691d9;
    color: white;
}

.modal-content button:last-of-type {
    background-color: #2691d9;
    color: white;
}
.modal-content button:first-of-type:hover,
.modal-content button:last-of-type:hover
{
    background-color: darkblue;
    border: solid 1px blue;
}

    </style>
<div id="right-panel" class="right-panel">
<header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header" style="background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);">
                    <a class="navbar-brand" href="dashboard.php"><img src="images/hylogo.png" alt="Logo" style=" width: 100px; height: auto;"></a>
                    <a class="navbar-brand hidden" href="./"><img src="images/logo3.png" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
            <div class="header-menu">
                <div class="header-left">
                    <div class="form-inline">
                    </div>
                </div>

                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-avatar rounded-circle" src="images/images.png" alt="User Avatar">
                    </a>

                    <div class="user-menu dropdown-menu" id="hh">
                        <a class="nav-link" href="admin-profile.php"><i class="fa fa-user"></i>My Profile</a>
                        <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i>Change Password</a>
                        <a class="nav-link" onclick="return handleLogout();"><i class="fa fa-power-off"></i> Logout</a>
                    </div>
                </div>
                <div id="logout-confirm-modal" class="modal">
                    <div class="modal-content">
                        <p>Are you sure you want to log out?</p>
                        <button onclick="confirmLogout(true)" class="btn-danger">Yes</button>
                        <button onclick="confirmLogout(false)" class="btn-warning">No</button>
                    </div>
                </div>
                <div class="alert-message" id="logout-alert" style="display: none;">
                <i class="bi bi-shield-fill-check"></i> You have successfully logged out.
                </div>
            </div>
        </div>
        <script>
            function handleLogout() {
                // Show the modal for confirmation
                document.getElementById("logout-confirm-modal").style.display = "block";
                return false; // Prevent the default action temporarily
            }

            function confirmLogout(isConfirmed) {
                // Hide the modal
                document.getElementById("logout-confirm-modal").style.display = "none";

                if (isConfirmed) {
                    // Show the logout alert
                    var alertMessage = document.getElementById("logout-alert");
                    alertMessage.style.display = "block";

                    // Redirect or proceed with logout actions if necessary
                    window.location.href = "../welcome.php"; // Or any other logout URL
                }
            }

        </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#menuToggle').click(function () {
            $('#right-panel').toggleClass('open-menu'); // Add or remove a class to handle the toggle action
        });
    });
</script>

        </header>

        <!--sidebar-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .left-panelbg {
            font-size:12px;
    }
    .navbar-expand-sm{
        width: 90%;
        margin-top:-1px;
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
                            <li><i class="menu-icon fa bi bi-p-square-fill"></i><a href="manage-category.php">Manage Vehicle Category</a></li>
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
                             <li><i class="menu-icon fa fa-user-circle-o"> <a href="manage-reg.php"></i>Manage Registered Client Vehicles </a></li>
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
                            <li><i class="menu-icon fa bi bi-journal-minus"></i><a href="invalidated.php">InValidated</a>
                        </ul>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-address-book"></i>Manage Users</a>
                        
                            <ul class="sub-menu children dropdown-menu">
                                <li><i class="menu-icon fa fa-user-circle-o"></i><a href="register.php">Register Client</a></li>
                                <li><i class="menu-icon fa fa-address-book"></i><a href="reg-users.php">User Information</a></li>
                                <li><i class="menu-icon  fa bi bi-chat-dots-fill"></i><a href="admin_comments.php">Comment</a></li>
                                <li><i class="menu-icon fa  bi bi-envelope-paper-heart"></i><a href="admin_feedbacks.php">Feedback</a></li>
                                <li><i class="menu-icon fa  bi bi-headset"></i><a href="admin_service.php">Customer Service</a></li>
                            </ul>
                    </li>
                    <li>
                        <a href="#" onclick="showPasswordModal('credentials', 'credentials.php')">
                            <i class="menu-icon fa bi bi-geo-fill"></i>Credentials
                        </a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
</div>

<!--content sugod-->

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: -10px;
            background-color: whitesmoke;
        }
        #chat-box {
            margin-left: 40px;
            width: 95%;
            border-radius: 20px; 
            height: 380px;
            overflow: auto;
            padding: 30px;
            border: none;
        }
        .message {
            padding: 20px;
        }
        .message-user {
            width: fit-content;
            max-width: 70%; /* Adjust width for better readability */
            border-radius: 10px;
            color: #444;
            background-color: #f1f1f1; /* Light background for user messages */
            padding: 10px;
            margin: 5px 0;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 5px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-left: -20px;
        }

        .message-support {
            width: fit-content;
            max-width: 70%; /* Adjust width for better readability */
            border-radius: 10px;
            color: #fff;
            background-color: #007bff; /* Blue background for support messages */
            padding: 10px;
            margin: 5px 0;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 5px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto; /* Align support messages to the right */
        }

        /* Icon styling to maintain spacing */
        .message i {
            margin-right: 10px; /* Space between icon and message text */
        }

        .message-support i {
            margin-left: 10px; /* Space between icon and message text for support */
        }

        #message-input {
            width: calc(100% - 140px);
            padding: 10px;
            z-index: 30px;
            margin-top: 8px;
            position: relative;
            border-radius: 4px;
            border:none;
            box-shadow: dimgray 0px 0px 0px 3px;
            margin-left: 20px;
        }
        #message-input:hover{
            border: none;
            box-shadow: rgba(3, 102, 214, 0.3) 0px 0px 0px 3px;
        }
        #send-button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: solid white;
            cursor: pointer;
            border-radius: 9px;
            margin-left: 10px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #send-button:hover {
            color:#0056b3;
            border: solid #0056b3;
            background-color: white ;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }
        h5{
            padding: 5px;
            margin-top: 5px;
            z-index: 1005;
            font-weight; bold;
            font-size: 16px;
            width:100%;
        }
        .card-body{
            margin-top: 30px;
         }
         h5{
            margin-top: 30px;
         }
    </style>
</head>
<body>

    <!-- Breadcrumbs -->
    <div class="breadcrumbs mb-3 ">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Customer Service</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="admin_service.php">Admin Customer Service</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Box Section -->
    <div class="card-body">
        <h5></h5>
        <div id="chat-box"></div>
        <input type="text" id="message-input" placeholder="Type your response..." />
        <button id="send-button"><i class="bi bi-send-fill"></i> Send</button>
    </div>

    <script>
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    // Function to add a message to the chat box
    function addMessageToChat(username, message, isSupport = false) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(isSupport ? 'message-support' : 'message-user');

        // Create the icon element
        const icon = document.createElement('i');
        icon.className = isSupport ? 'bi bi-person-workspace' : 'bi bi-person-circle';

        // Create a text node for the message
        const textNode = document.createTextNode(` ${message}`);

        // Append the icon and text to the messageDiv
        messageDiv.appendChild(icon);
        messageDiv.appendChild(textNode);

        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
    }

    // Function to send a message
    sendButton.addEventListener('click', function () {
        const adminMessage = messageInput.value.trim();
        if (adminMessage === '') {
            alert('Please enter a message before sending.');
            return;
        }

        // Add admin message to chat
        addMessageToChat('Admin', adminMessage, true);
        messageInput.value = ''; // Clear input

        // Send message to server
        fetch('send_admin_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: adminMessage })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Message sent successfully');
            } else {
                alert('Failed to send message: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    });

    // Function to fetch and display messages periodically
    function fetchMessages() {
        fetch('../users/get_messages.php') // Same endpoint as user side to get all messages
            .then(response => response.json())
            .then(data => {
                if (data.success && Array.isArray(data.messages)) {
                    chatBox.innerHTML = ''; // Clear chat box
                    data.messages.forEach(msg => {
                        addMessageToChat(msg.username, msg.message, msg.isSupport);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
    }

    // Fetch messages every 2 seconds
    setInterval(fetchMessages, 2000);
</script>

<!-- Scripts -->
 
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
