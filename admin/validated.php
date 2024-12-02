<?php
session_start();
include('../DBconnection/dbconnection.php');

// Fetch validated clients with expiration date and ensure uniqueness
$queryValidated = "
    SELECT DISTINCT u.email, u.expiration_date, u.validity
    FROM uploads u
    JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity > 0 AND u.expiration_date >= CURDATE()
";
$resultValidated = mysqli_query($con, $queryValidated);
$validatedClients = [];

if ($resultValidated && mysqli_num_rows($resultValidated) > 0) {
    while ($row = mysqli_fetch_assoc($resultValidated)) {
        // Check if the email is already in the validated clients array
        if (!in_array($row['email'], array_column($validatedClients, 'email'))) {
            $validatedClients[] = $row;
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validated | CTU Danao Parking System</title>
    <style>
        body {
            background: whitesmoke;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }
        h1 {
            text-align: center;
            margin-top: 7px;
            color: #1e3c72;
            font-weight: bold;
        }
        .bg-primary {
            color: white;
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
</head>
<body>

<div class="container">
    <?php if (empty($validatedClients)): ?>
        <div class="alert alert-info">
            No validated clients found in the system.
        </div>
    <?php endif; ?>

    <h4 class="text-center">Validated Clients</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th>Email</th>
                    <th>Expiration Date</th>
                    <th>Remaining Days</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($validatedClients)): ?>
                    <?php foreach ($validatedClients as $client): ?>
                        <?php
                            $expirationDate = new DateTime($client['expiration_date']);
                            $currentDate = new DateTime();
                            $remainingDays = $currentDate->diff($expirationDate)->format('%r%a'); // Positive or negative days

                            // Determine if the expiration date is far or not met
                            $status = '';
                            if ($remainingDays > 0) {
                                $status = 'Expiration is coming up in ' . $remainingDays . ' days.';
                            } elseif ($remainingDays == 0) {
                                $status = 'Expiration is today.';
                            } else {
                                $status = 'Expired ' . abs($remainingDays) . ' days ago.';
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['expiration_date']) ?></td>
                            <td><?= $remainingDays ?> days remaining</td>
                            <td><?= $status ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No data to display.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
