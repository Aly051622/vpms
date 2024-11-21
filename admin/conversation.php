<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('includes/dbconnection.php');

if (!isset($con)) {
    die("Database connection not established.");
}

// Get user ID from the query parameter
if (!isset($_GET['user_id'])) {
    die("User ID not provided.");
}

$user_id = intval($_GET['user_id']);

// Fetch the conversation, prioritizing the first message sender
$sql = "
    SELECT * 
    FROM messages 
    WHERE sender_id = $user_id OR receiver_id = $user_id 
    ORDER BY message_time ASC 
    LIMIT 1
";

$result = $con->query($sql);

if (!$result || $result->num_rows === 0) {
    die("No conversation found.");
}

// Fetch the first message's details
$conversation = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation</title>
</head>
<body>
    <h1>Conversation with User #<?php echo $user_id; ?></h1>
    <p>First Message:</p>
    <p><?php echo htmlspecialchars($conversation['message_content']); ?></p>
</body>
</html>
