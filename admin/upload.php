<?php
session_start();
include 'includes/dbconnection.php';

try {
    // Check if email and expiration date are provided in the POST request
    if (empty($_POST['email']) || empty($_POST['expiration_date'])) {
        $_SESSION['error_message'] = "Email or expiration date not provided.";
        header("Location: validation.php");
        exit();
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $expiration_date = mysqli_real_escape_string($con, $_POST['expiration_date']);

    // Validate expiration date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiration_date)) {
        $_SESSION['error_message'] = "Invalid expiration date format. Please use YYYY-MM-DD.";
        header("Location: validation.php");
        exit();
    }

    // Check if the email exists in the database
    $query = "SELECT * FROM tblregusers WHERE Email='$email'";
    $result = mysqli_query($con, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        $_SESSION['error_message'] = "Email not found in the database.";
        header("Location: validation.php");
        exit();
    }

    // Get the current date and calculate the remaining days
    $current_date = date("Y-m-d");
    $expiration_date_obj = new DateTime($expiration_date);
    $current_date_obj = new DateTime($current_date);

    // Ensure expiration date is valid
    if ($expiration_date_obj < $current_date_obj) {
        $remaining_days = 0;
        $validity = 0; // Expired
    } else {
        $remaining_days = $current_date_obj->diff($expiration_date_obj)->days;
        $validity = 1; // Valid
    }

    // Update expiration date and validity in the database
    $update_query = "UPDATE tblregusers SET expiration_date='$expiration_date', validity=$validity WHERE Email='$email'";

    if (mysqli_query($con, $update_query)) {
        $_SESSION['success_message'] = "Driver's license updated successfully.";
        header("Location: validated.php");
        exit();
    } else {
        throw new Exception("Database update error: " . mysqli_error($con));
    }
} catch (Exception $e) {
    // Log internal errors for debugging
    error_log("Error: " . $e->getMessage());

    $_SESSION['error_message'] = "An unexpected error occurred. Please try again.";
    header("Location: validation.php");
    exit();
} finally {
    // Close the database connection
    mysqli_close($con);
}
?>
