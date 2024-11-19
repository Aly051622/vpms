<?php
session_start();
include 'includes/dbconnection.php';

try {
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        
        // Check if email exists
        $query = "SELECT * FROM tblregusers WHERE Email='$email'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_message'] = "Email not found. You cannot proceed with the upload.";
            header('Location: upload_form.php');
            exit();
        } else {
            if (isset($_FILES['license_image'])) {
                $license_image = $_FILES['license_image']['name'];
                $upload_path = '..\\uploads\\validated\\'; // Update the path to point to the validated uploads

                // Proceed with uploading the file
                if (move_uploaded_file($_FILES['license_image']['tmp_name'], $upload_path . $license_image)) {
                    // Path to the Tesseract executable
                    $tesseract_path = '"C:\Program Files\Tesseract-OCR\tesseract.exe"'; // Enclose in double quotes

                    // Run Tesseract to extract text from the uploaded image
                    $tesseract_output = shell_exec($tesseract_path . " " . escapeshellarg($upload_path . $license_image) . " stdout 2>&1");

                    // Debugging: log the output for inspection
                    error_log("Tesseract Output: " . $tesseract_output); // Log output for debugging

                    // Display the extracted text on the validation.php page
                    $_SESSION['extracted_text'] = trim($tesseract_output); // Store extracted text in session

                    // Show the extracted text for debugging
                    if (empty($_SESSION['extracted_text'])) {
                        $_SESSION['error_message'] = "Extracted text is empty.";
                        header('Location: validation.php');
                        exit();
                    }

                    // Regex to find expiration date
                    preg_match_all('/\b(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\b/', $tesseract_output, $matches);
                    
                    if (!empty($matches[0])) {
                        $expiration_date_str = $matches[0][0]; // Get the first match
                        $expiration_date = date("Y-m-d", strtotime($expiration_date_str)); // Convert to Y-m-d format

                        // Determine validity based on expiration date
                        $current_date = date("Y-m-d");
                        $validity = ($expiration_date >= $current_date) ? 1 : 0;

                        // Insert into `uploads` table
                        $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                                         VALUES ('$email', '$license_image', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

                        if (mysqli_query($con, $insert_query)) {
                            // Update validity in tblregusers based on expiration
                            $update_query = "UPDATE tblregusers SET validity = $validity WHERE Email='$email'";
                            mysqli_query($con, $update_query);
                            
                            header("Location: validated.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error_message'] = "Could not extract expiration date from the image.";
                        header('Location: validation.php'); // Redirect to validation.php to display error
                        exit();
                    }
                } else {
                    $_SESSION['error_message'] = "Error uploading the license image.";
                    header('Location: upload_form.php');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "Please upload the driver's license.";
                header('Location: upload_form.php');
                exit();
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['error_message'] = "An unexpected error occurred: " . $e->getMessage();
    header('Location: upload_form.php');
    exit();
} finally {
    mysqli_close($con);
}
?>
