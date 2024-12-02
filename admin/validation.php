<?php
session_start();

// Error handling
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']);
}

// Include Tesseract OCR library
require_once 'vendor/autoload.php';  // Ensure Tesseract is installed and autoloaded via Composer

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if an image file was uploaded
    if (isset($_FILES['license_image'])) {
        // Process the uploaded image
        $imagePath = $_FILES['license_image']['tmp_name'];

        // Use Tesseract to extract text from the image
        $ocrResult = (new TesseractOCR($imagePath))->run();

        // Search for the expiration date pattern in the OCR result
        preg_match('/\b(?:\d{2}|\d{4})[-\/]?\d{2}[-\/]?\d{2}\b/', $ocrResult, $matches); // Looking for dates like 12/25/2025 or 2025-12-25

        if ($matches) {
            $expirationDate = $matches[0]; // First match
            $remainingDays = getRemainingDays($expirationDate);
        } else {
            $_SESSION['error_message'] = "Expiration date not found in the image.";
            header("Location: upload.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "No file uploaded.";
        header("Location: upload.php");
        exit;
    }
}

// Function to calculate remaining days until expiration
function getRemainingDays($expirationDate) {
    $currentDate = new DateTime();
    $expirationDate = new DateTime($expirationDate);
    $interval = $currentDate->diff($expirationDate);
    return $interval->days;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Driver's License | CTU Danao Parking System</title>

    <!-- Include your CSS and JS links here -->
</head>
<body>

    <div class="container">
        <h2>Update Driver's License</h2>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>

            <label for="license_image">Select Driver's License Image:</label>
            <input type="file" id="license_image" name="license_image" accept="image/*" required><br>

            <button type="submit" id="submit">Submit</button>
        </form>

        <?php if (isset($expirationDate)) { ?>
            <div class="alert alert-info">
                <p>Expiration Date: <?= htmlspecialchars($expirationDate) ?></p>
                <p>Remaining days: <?= $remainingDays ?> days</p>
            </div>
        <?php } ?>
    </div>

</body>
</html>
