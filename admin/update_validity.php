<?php
// Start session and include database connection
session_start();
include('includes/dbconnection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $licenseImage = $_FILES['license_image']['name'];
    $uploadedDate = date('Y-m-d'); // Assuming the current upload date is used
    $expirationDate = ''; // Extract from the uploaded image using Tesseract or input manually

    // For now, set expiration date manually for testing
    $expirationDate = '2024-12-15'; // Example expiration date
    
    // Compare the expiration date with the current date
    $currentDate = date('Y-m-d');
    $validity = (strtotime($expirationDate) > strtotime($currentDate)) ? 1 : 0;

    // Update the database
    $query = "UPDATE tblregusers SET validity = '$validity' WHERE email = '$email'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'License updated successfully!';
        if ($validity === 1) {
            header('Location: validated.php');
        } else {
            header('Location: invalidated.php');
        }
        exit();
    } else {
        $_SESSION['error_message'] = 'Error updating license!';
        header('Location: validation.php');
        exit();
    }
}
?>
