<?php
session_start();
include('../DBconnection/dbconnection.php');

// Check database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch validated clients (validity = 1) from tblregusers
$queryValidated = "
    SELECT email, expiration_date 
    FROM tblregusers 
    WHERE validity = 1
";
$resultValidated = mysqli_query($con, $queryValidated);

// Check if the query executed successfully
if (!$resultValidated) {
    die("Query failed: " . mysqli_error($con));
}

// Initialize array to store validated clients
$validatedClients = [];
if (mysqli_num_rows($resultValidated) > 0) {
    while ($row = mysqli_fetch_assoc($resultValidated)) {
        $validatedClients[] = $row;
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: whitesmoke;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            margin-top: 10px;
            color: #1e3c72;
            font-weight: bold;
        }
        .table {
            margin: 20px auto;
            width: 90%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
            padding: 10px;
        }
    </style>
    <title>Validated | CTU Danao Parking System</title>
</head>
<body>

<div class="container">
    <h1 class="text-center">Validated Clients</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th>Email</th>
                    <th>Expiration Date</th>
                    <th>Remaining Days</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($validatedClients)): ?>
                    <?php foreach ($validatedClients as $client): ?>
                        <?php
                        // Calculate remaining days until expiration
                        $expirationDate = DateTime::createFromFormat('Y-m-d', $client['expiration_date']);
                        $currentDate = new DateTime();
                        $remainingDays = $expirationDate ? $currentDate->diff($expirationDate)->format('%r%a') : 'N/A';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                            <td>
                                <?= $remainingDays !== 'N/A' ? ($remainingDays >= 0 ? "$remainingDays days remaining" : "Expired") : "Invalid date" ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No validated clients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
