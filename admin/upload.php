<?php
session_start();
include 'includes/dbconnection.php';

try {
    // Check if email is provided in the POST request
    if (!isset($_POST['email'])) {
        die("Email not provided.");
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists in the database
    $query = "SELECT * FROM tblregusers WHERE Email='$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 0) {
        die("Email not found in the database.");
    }

    // Check if license image is uploaded
    if (!isset($_FILES['license_image'])) {
        die("No file uploaded.");
    }

    // Upload the license image
    $license_image = $_FILES['license_image']['name'];
    $upload_path = realpath('../uploads/validated/') . DIRECTORY_SEPARATOR;

    // Ensure the upload directory exists
    if (!is_dir($upload_path)) {
        die("Upload directory does not exist.");
    }

    // Move the uploaded file to the target directory
    $target_file = $upload_path . $license_image;
    if (!move_uploaded_file($_FILES['license_image']['tmp_name'], $target_file)) {
        die("Failed to move the uploaded file.");
    }

    // Call Python script for OCR text extraction
    $pythonScript = 'C:\\xampp\\htdocs\\vpms\\admin\\ocr.py';  // Adjust path as needed
    $ocr_output = shell_exec($command);

    if (!$ocr_output) {
        die("OCR extraction failed.");
    }

    // Assuming the OCR output contains the expiration date, extract it
    preg_match_all('/\b(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\b/', $ocr_output, $matches);

    if (empty($matches[0])) {
        die("No expiration date found in the image.");
    }

    // Assuming the first match is the correct expiration date
    $expiration_date_str = $matches[0][0];
    $expiration_date = date("Y-m-d", strtotime($expiration_date_str));

    // Insert the expiration date into the database
    $current_date = date("Y-m-d");
    $validity = ($expiration_date >= $current_date) ? 1 : 0;

    // Prepare the insert query
    $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                     VALUES ('$email', '$license_image', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

    if (mysqli_query($con, $insert_query)) {
        // Update the user's validity status in the tblregusers table
        $update_query = "UPDATE tblregusers SET validity = $validity WHERE Email='$email'";
        mysqli_query($con, $update_query);

        // Redirect to validated.php
        header("Location: validated.php");
        exit();
    } else {
        die("Database insert error: " . mysqli_error($con));
    }

} catch (Exception $e) {
    // Log the error message for debugging
    error_log("Error: " . $e->getMessage());
    die("An unexpected error occurred: " . $e->getMessage());
} finally {
    // Close the database connection
    mysqli_close($con);
}
