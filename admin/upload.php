<?php
session_start();
include 'includes/dbconnection.php';

try {
    if (!isset($_POST['email'])) {
        die("Email not provided.");
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM tblregusers WHERE Email='$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 0) {
        die("Email not found in the database.");
    }

    if (!isset($_FILES['license_image'])) {
        die("No file uploaded.");
    }

    // Upload file
    $license_image = $_FILES['license_image']['name'];
    $upload_path = realpath('../uploads/validated/') . DIRECTORY_SEPARATOR;

    if (!move_uploaded_file($_FILES['license_image']['tmp_name'], $upload_path . $license_image)) {
        die("Failed to move uploaded file.");
    }

    // Tesseract OCR - Make sure the path is correct
    $tesseract_path = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'; // Update the path if necessary
    if (!file_exists($tesseract_path)) {
        die("Tesseract executable not found. Please ensure Tesseract is installed.");
    }

    // Test Tesseract command and capture output
    $tesseract_output = shell_exec($tesseract_path . ' ' . escapeshellarg($upload_path . $license_image) . ' stdout 2>&1');

    if ($tesseract_output === null) {
        die("Tesseract execution failed.");
    }

    // Process expiration date
    preg_match_all('/\b(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\b/', $tesseract_output, $matches);

    if (empty($matches[0])) {
        die("No expiration date found in the image.");
    }

    $expiration_date_str = $matches[0][0];
    $expiration_date = date("Y-m-d", strtotime($expiration_date_str));

    echo "Extracted Expiration Date: $expiration_date";

    // Insert into database
    $current_date = date("Y-m-d");
    $validity = ($expiration_date >= $current_date) ? 1 : 0;

    $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                     VALUES ('$email', '$license_image', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

    if (mysqli_query($con, $insert_query)) {
        // Update user validity based on expiration date
        $update_query = "UPDATE tblregusers SET validity = $validity WHERE Email='$email'";
        mysqli_query($con, $update_query);

        // Redirect after successful upload and processing
        header("Location: validated.php");
        exit();
    } else {
        die("Database insert error: " . mysqli_error($con));
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    die("An unexpected error occurred: " . $e->getMessage());
} finally {
    mysqli_close($con);
}
?>
