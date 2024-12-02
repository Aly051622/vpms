<?php
session_start();
include_once('includes/dbconnection.php');

// Fetch validated clients
$query = "SELECT email, expiration_date FROM validations WHERE validity = 1 ORDER BY expiration_date DESC";
$result = mysqli_query($conn, $query);
$validatedClients = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validated | CTU Danao Parking System</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1 class="text-center">Validated Clients</h1>
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Expiration Date</th>
                    <th>Remaining Days</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($validatedClients as $client): ?>
                    <?php
                        $expirationDate = new DateTime($client['expiration_date']);
                        $currentDate = new DateTime();
                        $remainingDays = $currentDate->diff($expirationDate)->format('%r%a');
                        $status = $remainingDays > 0 ? "Valid" : "Expired";
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                        <td><?= $remainingDays ?> days</td>
                        <td><?= $status ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($validatedClients)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No validated clients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
