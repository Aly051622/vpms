<?php
// Enable error logging instead of displaying errors
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

// Set content type to JSON
header('Content-Type: application/json');

// Include database connection
include('../DBconnection/dbconnection.php');

// Check if the database connection is valid
if (!$con) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

// Query to fetch comments
$query = "SELECT username, comment FROM comments ORDER BY created_at DESC";
$result = mysqli_query($con, $query);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch comments: ' . mysqli_error($con)
    ]);
    exit;
}

$comments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $comments[] = [
        'username' => $row['username'] ?: 'Anonymous', // Default to 'Anonymous' if username is empty
        'comment' => $row['comment']
    ];
}

// Send JSON response
echo json_encode([
    'success' => true,
    'comments' => $comments
]);

// Close the database connection
mysqli_close($con);
?>
