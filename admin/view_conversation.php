<?php
// Include the database connection
include('includes/dbconnection.php');

// Get the username from the URL
$username = $_GET['username'];

// Fetch the messages for that user
$sql = "SELECT m.username, m.message, m.isSupport, m.created_at 
        FROM messages m
        WHERE m.username = '$username' 
        ORDER BY m.created_at ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conversation with <?php echo $username; ?> | Admin Customer Service</title>
    <link rel="stylesheet" href="style.css"> <!-- Include the CSS file for styling -->
</head>
<body>
    <h3>Conversation with <?php echo $username; ?></h3>

    <div id="chat-box">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="message <?php echo $row['isSupport'] == 1 ? 'message-support' : 'message-user'; ?>">
            <p><strong><?php echo $row['username']; ?>:</strong> <?php echo $row['message']; ?></p>
            <span class="timestamp"><?php echo $row['created_at']; ?></span>
        </div>
        <?php } ?>
    </div>

    <input type="text" id="message-input" placeholder="Type your response..." />
    <button id="send-button">Send</button>

    <script>
    // Handle message sending functionality
    document.getElementById('send-button').addEventListener('click', function () {
        const adminMessage = document.getElementById('message-input').value.trim();
        if (adminMessage !== '') {
            const formData = new FormData();
            formData.append('username', '<?php echo $username; ?>');
            formData.append('message', adminMessage);
            formData.append('isSupport', 1);  // Mark as admin message

            // Send the message using AJAX
            fetch('send_admin_message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    // Reload the conversation
                    window.location.reload();
                } else {
                    alert('Failed to send message');
                }
            });
        }
    });
    </script>
</body>
</html>
