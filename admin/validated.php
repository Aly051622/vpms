<?php
session_start();
include 'includes/dbconnection.php';

// Fetch validated clients with expiration date
$queryValidated = "
    SELECT email, expiration_date 
    FROM uploads 
    WHERE validity > 0 AND expiration_date >= CURDATE()
";
$resultValidated = mysqli_query($con, $queryValidated);

if (mysqli_num_rows($resultValidated) > 0) {
    echo "<h1>Validated Clients</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Email</th><th>Expiration Date</th></tr>";

    while ($row = mysqli_fetch_assoc($resultValidated)) {
        echo "<tr>
                <td>{$row['email']}</td>
                <td>{$row['expiration_date']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No validated clients found.";
}

mysqli_close($con);
?>
