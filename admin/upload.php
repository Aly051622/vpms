<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

    // Generate a unique filename
    $file_extension = pathinfo($license_image, PATHINFO_EXTENSION); // Get the file extension
    $unique_filename = uniqid('license_', true) . '.' . $file_extension; // Create a unique filename
    $target_file = $upload_path . $unique_filename;

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES['license_image']['tmp_name'], $target_file)) {
        die("Failed to move the uploaded file.");
    }

    // Define the path to the Python script
    $python_script_path = 'C:/xampp/htdocs/vpms/admin/extract.py';  // Ensure this path is correct
    $command = "python $python_script_path $target_file 2>&1";  // Capture both stdout and stderr

    // Execute the Python script and capture the output
    $output = shell_exec($command);

    // Debug: Log the output for verification
    error_log("Python script output: " . $output);

    // Parse the output from the Python script to extract the expiration date
    preg_match('/Expiration Date Found: (\d{2}[-/]\d{2}[-/]\d{4})/', $output, $matches);

    if (empty($matches)) {
        die("No expiration date found in the image.");
    }

    // Get the expiration date
    $expiration_date_str = $matches[1];
    $expiration_date = date("Y-m-d", strtotime($expiration_date_str));

    // Determine if the license is valid or expired
    $current_date = date("Y-m-d");
    $validity = ($expiration_date >= $current_date) ? 1 : 0;

    // Insert the expiration date into the database
    $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                     VALUES ('$email', '$unique_filename', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

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
?>
