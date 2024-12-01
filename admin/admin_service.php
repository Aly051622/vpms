<?php
session_set_cookie_params([
    'domain' => '.ctudanaoparksys.icu',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);
session_start();
include('includes/auth_check.php'); // Ensure admin is logged in

if (!isset($_SESSION['adminid'])) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Customer Service | CTU DANAO Parking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
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
<div class="container">
    <h3>Admin Chat</h3>
    <div id="chat-box"></div>
    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Type your response...">
        <div class="input-group-append">
            <button id="send-button" class="btn btn-primary">Send</button>
        </div>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    function addMessage(username, message, isSupport = false) {
        const messageDiv = document.createElement('div');
        messageDiv.textContent = `${username}: ${message}`;
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    sendButton.addEventListener('click', () => {
        const message = messageInput.value.trim();
        if (message === '') return;

        fetch('send_admin_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                addMessage('Admin', message, true);
                messageInput.value = '';
            } else {
                alert('Error sending message');
            }
        });
    });

    setInterval(() => {
        fetch('get_messages.php')
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML = '';
                data.messages.forEach(msg => {
                    addMessage(msg.username, msg.message, msg.isSupport);
                });
            });
    }, 2000);
</script>
</body>
</html>

