<?php
session_start();
include('../DBconnection/dbconnection.php');

// Fetch validated clients with expiration date and ensure uniqueness
$queryValidated = "
    SELECT DISTINCT u.email, u.expiration_date, u.validity
    FROM uploads u
    JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity > 0 AND DATE(u.expiration_date) >= CURDATE()
";
$resultValidated = mysqli_query($con, $queryValidated);
$validatedClients = [];

if ($resultValidated && mysqli_num_rows($resultValidated) > 0) {
    while ($row = mysqli_fetch_assoc($resultValidated)) {
        // Calculate the remaining days
        $current_date = new DateTime();
        $expiration_date = new DateTime($row['expiration_date']);
        $remaining_days = $current_date->diff($expiration_date)->days;
        
        // Add the client to the array with remaining days
        $row['remaining_days'] = $remaining_days;
        $validatedClients[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validated Driver's Licenses</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Table to display validated clients -->
    <h2>Validated Clients</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Email</th>
                <th>Expiration Date</th>
                <th>Remaining Days</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($validatedClients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                    <td><?php echo htmlspecialchars($client['expiration_date']); ?></td>
                    <td><?php echo $client['remaining_days']; ?> days</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
