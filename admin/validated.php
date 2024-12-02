<?php
// Database connection
include('includes/db.php');

// Query to fetch valid users
$query = "SELECT DISTINCT u.email, u.expiration_date, u.validity 
          FROM uploads u 
          JOIN tblregusers r ON u.email = r.Email 
          WHERE u.validity > 0 AND DATE(u.expiration_date) >= CURDATE()";
$result = mysqli_query($connection, $query);

// Display the results
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Email: " . $row['email'] . "<br>";
        echo "Expiration Date: " . $row['expiration_date'] . "<br>";
        echo "Validity: " . $row['validity'] . "<br><br>";
    }
} else {
    echo "No valid users found.";
}
?>
