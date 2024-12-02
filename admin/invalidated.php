<?php
session_start();
include_once('includes/dbconnection.php');

// Fetch invalidated clients
$query = "
    SELECT email, expiration_date 
    FROM validations 
    WHERE validity = 0 
    GROUP BY email 
    ORDER BY expiration_date DESC";
$result = mysqli_query($conn, $query);
$invalidatedClients = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalidated | CTU Danao Parking System</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1 class="text-center">Invalidated Clients</h1>
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invalidatedClients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                        <td>Expired</td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($invalidatedClients)): ?>
                    <tr>
                        <td colspan="3" class="text-center">No invalidated clients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
