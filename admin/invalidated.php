<?php
session_start();
include 'includes/dbconnection.php';

// Fetch invalidated clients (validity = 0) without duplicates based on email
$queryInvalidated = "
    SELECT u.email, 
           MAX(u.expiration_date) AS expiration_date, 
           MAX(u.validity) AS validity, 
           MAX(r.cr_image) AS cr_image, 
           MAX(r.nv_image) AS nv_image, 
           MAX(r.or_image) AS or_image, 
           MAX(r.profile_pictures) AS profile_pictures 
    FROM uploads u
    JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity = 0
    GROUP BY u.email
";

$resultInvalidated = mysqli_query($con, $queryInvalidated);

if (mysqli_num_rows($resultInvalidated) > 0) {
    echo "<h1>Invalidated Clients</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Email</th>
            <th>Expiration Date</th>
    
          </tr>";

    while ($row = mysqli_fetch_assoc($resultInvalidated)) {
        echo "<tr>
                <td>{$row['email']}</td>
                <td>{$row['expiration_date']}</td>
               
              </tr>";
    }
    echo "</table>";
} else {
    echo "No invalidated clients found.";
}

mysqli_close($con);
?>
<button onclick="window.history.back()">Back</button>
