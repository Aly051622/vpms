<?php
session_start();
include 'includes/dbconnection.php';

// Ensure email is passed and valid
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Update the validity status to 2 (invalidated)
    $updateQuery = "UPDATE uploads SET validity = 2 WHERE email = '$email'";
    if (mysqli_query($con, $updateQuery)) {
        // Redirect back to validated clients page
        header("Location: validated.php");
        exit();
    } else {
        echo "Error deleting client: " . mysqli_error($con);
    }
} else {
    echo "No email provided.";
}

mysqli_close($con);
?>
