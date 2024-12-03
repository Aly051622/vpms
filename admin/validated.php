<?php
session_start();
include_once('includes/dbconnection.php');

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']);
}

// Fetch the latest record from the 'validations' table
$sql = "SELECT email, expiration_date FROM validations ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Email: " . htmlspecialchars($row['email']) . "<br>";
    echo "Expiration Date: " . htmlspecialchars($row['expiration_date']) . "<br>";
} else {
    echo "No data found.";
}
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
