<?php
session_start();
include '../DBconnection/dbconnection.php';

// Fetch all users' image data
$queryAllUsers = "
    SELECT or_image, cr_image, nv_image, profile_pictures
    FROM tblregusers
";

$resultAllUsers = mysqli_query($con, $queryAllUsers);

if ($resultAllUsers && mysqli_num_rows($resultAllUsers) > 0) {
    echo "<h1>User Images</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>OR Image</th>
            <th>CR Image</th>
            <th>NV Image</th>
            <th>Profile Picture</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($resultAllUsers)) {
        // Assuming your images are stored in these directories
        $orImagePath = "../../uploads/or_image/" . $row['or_image'];
        $crImagePath = "../../uploads/cr_image/" . $row['cr_image'];
        $nvImagePath = "../../uploads/nv_image/" . $row['nv_image'];
        $profilePicturePath = "../../uploads/profile_uploads/" . $row['profile_pictures'];

        // Display user images
        echo "<tr>
                <td><img src='$orImagePath' alt='OR Image' width='100'></td>
                <td><img src='$crImagePath' alt='CR Image' width='100'></td>
                <td><img src='$nvImagePath' alt='NV Image' width='100'></td>
                <td><img src='$profilePicturePath' alt='Profile Picture' width='100'></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No user images found.</p>";
}

mysqli_close($con);
?>
