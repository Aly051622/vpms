<?php
// Include the database connection
include('includes/dbconnection.php');

// Fetch feedback from the database
$query = "SELECT username, feedback FROM feedbacks ORDER BY created_at DESC"; // Assuming you have a timestamp for ordering
$result = $con->query($query);

$feedbacks = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['success' => true, 'feedbacks' => $feedbacks]);

$con->close();
