<?php
session_start();
include 'includes/dbconnection.php';

// Fetch validated clients (validity = 1)
$queryValidated = "
    SELECT u.email, u.expiration_date, u.validity, 
           r.cr_image, r.nv_image, r.or_image, r.profile_pictures 
    FROM uploads u
    JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity = 1
";

$resultValidated = mysqli_query($con, $queryValidated);

if (mysqli_num_rows($resultValidated) > 0) {
    echo "<h1>Validated Clients</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Email</th>
            <th>Expiration Date</th>
            <th>Validity</th>
            <th>Remaining Days</th>
            <th>CR Image</th>
            <th>NV Image</th>
            <th>OR Image</th>
            <th>Profile Picture</th>
            <th>Action</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($resultValidated)) {
        $expirationDate = new DateTime($row['expiration_date']);
        $currentDate = new DateTime();
        $remainingDays = $currentDate->diff($expirationDate)->format('%r%a'); // Positive or negative days

        // If expiration has passed, update validity and skip further processing
        if ($remainingDays < 0) {
            $updateQuery = "UPDATE uploads SET validity = 0 WHERE email = '{$row['email']}'";
            mysqli_query($con, $updateQuery);
            continue;
        }

        echo "<tr>
                <td>{$row['email']}</td>
                <td>{$row['expiration_date']}</td>
                <td>{$row['validity']}</td>
                <td>$remainingDays days remaining</td>
                <td><img src='uploads/validated/{$row['cr_image']}' width='100'></td>
                <td><img src='uploads/validated/{$row['nv_image']}' width='100'></td>
                <td><img src='uploads/validated/{$row['or_image']}' width='100'></td>
                <td><img src='../uploads/profile_uploads/{$row['profile_pictures']}' width='100'></td>
                <td>
                    <form action='delete_client.php' method='POST'>
                        <input type='hidden' name='email' value='{$row['email']}'>
                        <button type='submit' onclick='return confirm(\"Delete this client?\")'>Delete</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No validated clients found.";
}

mysqli_close($con);
?>
<button onclick="window.history.back()">Back</button>
