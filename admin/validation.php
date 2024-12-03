<?php
session_start();
include 'includes/dbconnection.php';

// Display any error messages
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
}

// Display important details if available
if (isset($_SESSION['email']) && isset($_SESSION['license_image'])) {
    $email = htmlspecialchars($_SESSION['email']); // Escape output for security
    $license_image = htmlspecialchars($_SESSION['license_image']); // Escape output for security

    echo "<h2>Submitted Details</h2>";
    echo "<p>Email: $email</p>";
    echo "<p>Driver's License Image:</p>";
    echo "<img src='$license_image' alt='Driver's License' style='max-width: 300px;'><br>";
}

// Display extracted text from Tesseract
if (isset($_SESSION['extracted_text'])) {
    echo "<h3>Extracted Text from Image:</h3>";
    echo "<pre>" . htmlspecialchars($_SESSION['extracted_text']) . "</pre>"; // Display extracted text
    unset($_SESSION['extracted_text']); // Clear the session variable
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Driver's License</title>
</head>
<body>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="license_image">Select Driver's License Image:</label>
        <input type="file" id="license_image" name="license_image" accept="image/*" required><br><br>

        <button type="submit">Submit</button>
    </form>

    <br>
    <!-- Back Button -->
    <button onclick="window.history.back()">Back</button>
</body>
</html>
