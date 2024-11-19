<?php
session_start();
include 'includes/dbconnection.php';

// Fetch unvalidated clients
$queryUnvalidated = "SELECT email FROM uploads WHERE validity = 0 OR expiration_date < CURDATE()";
$resultUnvalidated = mysqli_query($con, $queryUnvalidated);

if (mysqli_num_rows($resultUnvalidated) > 0) {
    echo "<h1>Unvalidated Clients</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Email</th></tr>";

    while ($row = mysqli_fetch_assoc($resultUnvalidated)) {
        echo "<tr><td>{$row['email']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No unvalidated clients found.";
}

mysqli_close($con);
?>
