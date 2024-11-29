<?php
session_start();
include 'includes/dbconnection.php';

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
        // Construct image paths from the values in the database (assumed to be file names)
        $orImagePath = "../uploads/or_image/" . $row['or_image'];
        $crImagePath = "../uploads/cr_image/" . $row['cr_image'];
        $nvImagePath = "../uploads/nv_image/" . $row['nv_image'];
        $profilePicturePath = "../uploads/profile_uploads/" . $row['profile_pictures'];

        // Display user data with images
        echo "<tr>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['MobileNumber']}</td>";

        // Display OR Image
        if (!empty($row['or_image'])) {
            echo "<td><img src='$orImagePath' alt='OR Image' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        // Display CR Image
        if (!empty($row['cr_image'])) {
            echo "<td><img src='$crImagePath' alt='CR Image' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        // Display NV Image
        if (!empty($row['nv_image'])) {
            echo "<td><img src='$nvImagePath' alt='NV Image' width='100'></td>";
        } else {
            echo "<td>No image</td>";
        }

        // Display Profile Picture
        if (!empty($row['profile_pictures'])) {
            echo "<td><img src='$profilePicturePath' alt='Profile Picture' width='100'></td>";
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

<button onclick="window.history.back()">Back</button>
