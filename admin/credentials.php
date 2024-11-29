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
        // Correct the path to images (relative to this file)
        $orImagePath = "../../uploads/or_image/" . $row['or_image'];
        $crImagePath = "../../uploads/cr_image/" . $row['cr_image'];
        $nvImagePath = "../../uploads/nv_image/" . $row['nv_image'];
        $profilePicturePath = "../../uploads/profile_uploads/" . $row['profile_pictures'];

        // Display user data with images
        echo "<tr>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['MobileNumber']}</td>
                <td><img src='$orImagePath' alt='OR Image' width='100'></td>
                <td><img src='$crImagePath' alt='CR Image' width='100'></td>
                <td><img src='$nvImagePath' alt='NV Image' width='100'></td>
                <td><img src='$profilePicturePath' alt='Profile Picture' width='100'></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}

mysqli_close($con);
?>

<!-- Back button -->
<button onclick="window.history.back()">Back</button>
