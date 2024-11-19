<?php
// Correct the path to dbconnection.php based on its actual location
include('users/includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']); // Optional email
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Insert feedback into the database
    $query = "INSERT INTO feedbacks (name, email, message) VALUES ('$name', '$email', '$message')";

    if (mysqli_query($con, $query)) {
        echo "Your feedback has been submitted successfully.";
    } else {
        echo "There was an error submitting your feedback. Please try again.";
    }
}
?>
