<?php
// Assuming the connection to the database is established
include_once('includes/db.php');

// Fetch validated users (you can adjust the query as needed)
$query = "SELECT * FROM tblregusers WHERE validity = 1"; // assuming validity=1 means validated
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Display the user's details
        echo "<div class='user-details'>";
        echo "<p>Name: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "<p>License Expiry: " . $row['license_expiry'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No validated users found.</p>";
}
?>
