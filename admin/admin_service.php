<?php
session_start();
include('includes/auth_check.php'); // Ensure admin is logged in
include('includes/dbconnection.php');

// Ensure admin is logged in by checking the correct session variable 'vpmsaid'
if (!isset($_SESSION['vpmsaid'])) {
    echo "Please log in as an admin.";
    exit;
}

$adminId = $_SESSION['vpmsaid']; // Use the session variable 'vpmsaid' for admin ID

try {
    // Corrected query: Use sender_id and receiver_id instead of user_id
    $query = "SELECT DISTINCT sender_id, receiver_id FROM messages ORDER BY sent_at DESC";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        echo "<h3>Users Who Messaged</h3>";
        while ($row = $result->fetch_assoc()) {
            // Get sender and receiver names
            $sender_id = $row['sender_id'];
            $receiver_id = $row['receiver_id'];

            // Fetch user details from tblregusers using sender_id and receiver_id
            $userQuery = "SELECT FirstName, LastName, ID FROM tblregusers WHERE ID = ? LIMIT 1";
            $stmt = $con->prepare($userQuery);

            // Check sender
            $stmt->bind_param("i", $sender_id);
            $stmt->execute();
            $senderResult = $stmt->get_result();
            $sender = $senderResult->fetch_assoc();

            // Check receiver
            $stmt->bind_param("i", $receiver_id);
            $stmt->execute();
            $receiverResult = $stmt->get_result();
            $receiver = $receiverResult->fetch_assoc();

            echo "<div>";
            echo "<strong>Sender:</strong> " . htmlspecialchars($sender['FirstName']) . " " . htmlspecialchars($sender['LastName']) . " ";
            echo "<a href='view_messages.php?user_id=" . $sender['ID'] . "'>View Conversation</a><br>";
            echo "<strong>Receiver:</strong> " . htmlspecialchars($receiver['FirstName']) . " " . htmlspecialchars($receiver['LastName']) . " ";
            echo "<a href='view_messages.php?user_id=" . $receiver['ID'] . "'>View Conversation</a>";
            echo "</div><hr>";
        }
    } else {
        echo "No users have messaged the admin yet.";
    }
} catch (Exception $e) {
    echo "Error fetching messages: " . $e->getMessage();
} finally {
    $con->close();
}
?>
