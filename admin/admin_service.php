<?php
// Include the database connection
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
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: whitesmoke;
        }
        #chat-box {
            margin-left: 40px;
            width: 95%;
            border-radius: 20px; 
            height: 400px;
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
            margin-top: 10px;
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
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcrumbs -->
    <div class="breadcrumbs mb-3 ">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Dashboard</h1>
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

</body>
</html>
