<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Include database connection
include('includes/dbconnection.php');

// Check if the connection is valid
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

$comments = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = [
            'username' => $row['username'] ?: 'Anonymous',
            'comment' => $row['comment']
        ];
    }
}

// Send JSON response
echo json_encode([
    'success' => true,
    'comments' => $comments
]);

// Close the database connection
mysqli_close($con);
?>
