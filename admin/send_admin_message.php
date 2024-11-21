<?php
// Include the database connection
include('includes/dbconnection.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data and decode it into a PHP array
    $input = json_decode(file_get_contents('php://input'), true);

    // Get the message from the input, default to an empty string if not provided
    $message = $input['message'] ?? '';

    // Validate that the message is not empty
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']);
        exit;
    }

    // Prepare the SQL statement to save the admin message
    $stmt = $conn->prepare("INSERT INTO messages (username, message, isSupport, created_at) VALUES (?, ?, ?, NOW())");

    // Check if the prepare was successful
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement.']);
        exit;
    }

    // Admin username and isSupport flag
    $isSupport = 1; // 1 for admin messages
    $username = 'Admin'; // Set the admin username or dynamic if needed

    // Bind the parameters to the statement
    $stmt->bind_param("ssi", $username, $message, $isSupport);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
