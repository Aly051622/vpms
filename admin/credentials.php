<?php
session_start();
include '../DBconnection/dbconnection.php';

// Fetch all users' details
$queryAllUsers = "
    SELECT 
           FirstName, 
           LastName, 
           Email, 
           MobileNumber, 
           or_image, 
           cr_image, 
           nv_image, 
           profile_pictures
    FROM tblregusers
";

$resultAllUsers = mysqli_query($con, $queryAllUsers);

if ($resultAllUsers && mysqli_num_rows($resultAllUsers) > 0) {
    echo "<h1>All Users</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Mobile Number</th>
            <th>OR Image</th>
            <th>CR Image</th>
            <th>NV Image</th>
            <th>Profile Picture</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($resultAllUsers)) {
        // Output user data with image data
        echo "<tr>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['MobileNumber']}</td>";

        // Display OR Image
        if ($row['or_image']) {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['or_image']) . "' alt='OR Image' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        // Display CR Image
        if ($row['cr_image']) {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['cr_image']) . "' alt='CR Image' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        // Display NV Image
        if ($row['nv_image']) {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['nv_image']) . "' alt='NV Image' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        // Display Profile Picture
        if ($row['profile_pictures']) {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['profile_pictures']) . "' alt='Profile Picture' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}

mysqli_close($con);
?>

<!-- Back button -->
<button onclick="window.history.back()">Back</button>
