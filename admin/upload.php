<?php
session_start();
include 'includes/dbconnection.php';

try {
    // Check if email and expiration date are provided in the POST request
    if (!isset($_POST['email']) || !isset($_POST['expiration_date'])) {
        die("Email or expiration date not provided.");
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $expiration_date = mysqli_real_escape_string($con, $_POST['expiration_date']);

    // Check if email exists in the database
    $query = "SELECT * FROM tblregusers WHERE Email='$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 0) {
        die("Email not found in the database.");
    }

    // Get the current date and calculate the remaining days
    $current_date = date("Y-m-d");
    $expiration_date_obj = new DateTime($expiration_date);
    $current_date_obj = new DateTime($current_date);
    $remaining_days = $current_date_obj->diff($expiration_date_obj)->days;

    // Set validity based on expiration date
    $validity = ($expiration_date >= $current_date) ? 1 : 0;

    // Insert or update the expiration date into the database
    $update_query = "UPDATE tblregusers SET expiration_date='$expiration_date', validity=$validity WHERE Email='$email'";

    if (mysqli_query($con, $update_query)) {
        // Redirect to validated.php
        header("Location: validated.php");
        exit();
    } else {
        die("Database update error: " . mysqli_error($con));
    }

} catch (Exception $e) {
    // Log the error message for debugging
    error_log("Error: " . $e->getMessage());
    die("An unexpected error occurred: " . $e->getMessage());
} finally {
    // Close the database connection
    mysqli_close($con);
}
?>
