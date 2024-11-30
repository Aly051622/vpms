<?php
session_start();
include('../DBconnection/dbconnection.php');

// Check if user is logged in
if (empty($_SESSION['vpmsuid'])) {
    header('location:logout.php');
    exit;
}

// Fetch user information from the database
$uid = $_SESSION['vpmsuid'];
$ret = mysqli_query($con, "SELECT * FROM tblregusers WHERE ID='$uid'");
$row = mysqli_fetch_array($ret);

// Fetch image paths
$orImage = !empty($row['or_image']) ? 'uploads/' . htmlspecialchars($row['or_image']) : '';
$crImage = !empty($row['cr_image']) ? 'uploads/' . htmlspecialchars($row['cr_image']) : '';
$nvImage = !empty($row['nv_image']) ? 'uploads/' . htmlspecialchars($row['nv_image']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credentials</title>
</head>
<body>
    <h1>Credentials</h1>

    <table border="1">
        <tr>
            <th>Email</th>
            <th>OR Image</th>
            <th>CR Image</th>
            <th>NV Image</th>
            <th>Profile Picture</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <!-- Display OR Image -->
            <td>
                <?php if ($orImage): ?>
                    <img src="<?php echo $orImage; ?>" alt="OR Image" width="100" onerror="this.onerror=null; this.src='images/placeholder.png';">
                <?php else: ?>
                    No OR image uploaded.
                <?php endif; ?>
            </td>

            <!-- Display CR Image -->
            <td>
                <?php if ($crImage): ?>
                    <img src="<?php echo $crImage; ?>" alt="CR Image" width="100" onerror="this.onerror=null; this.src='images/placeholder.png';">
                <?php else: ?>
                    No CR image uploaded.
                <?php endif; ?>
            </td>

            <!-- Display NV Image -->
            <td>
                <?php if ($nvImage): ?>
                    <img src="<?php echo $nvImage; ?>" alt="NV Image" width="100" onerror="this.onerror=null; this.src='images/placeholder.png';">
                <?php else: ?>
                    No NV image uploaded.
                <?php endif; ?>
            </td>

            <!-- Display Profile Picture -->
            <td>
                <?php if (!empty($row['profile_pictures'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['profile_pictures']); ?>" alt="Profile Picture" width="100" onerror="this.onerror=null; this.src='images/placeholder.png';">
                <?php else: ?>
                    No Profile Picture uploaded.
                <?php endif; ?>
            </td>
        </tr>
    </table>
</body>
</html>
