<?php
session_start();
include 'includes/dbconnection.php';
require_once 'vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        }

        if (isset($_FILES['license_image'])) {
            $license_image = $_FILES['license_image']['name'];
            $upload_path = '../uploads/validated/';
            
            if ($_FILES['license_image']['error'] === UPLOAD_ERR_OK && move_uploaded_file($_FILES['license_image']['tmp_name'], $upload_path . $license_image)) {
                $tesseract = new TesseractOCR($upload_path . $license_image);
                $tesseract->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');
                $tesseract_output = $tesseract->run();

                preg_match_all('/\b(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\b/', $tesseract_output, $matches);

                if (!empty($matches[0])) {
                    $expiration_date_str = $matches[0][0];
                    $expiration_date = date("Y-m-d", strtotime($expiration_date_str));
                    $current_date = date("Y-m-d");
                    $validity = ($expiration_date >= $current_date) ? 1 : 0;

                    $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                                     VALUES ('$email', '$license_image', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

                    if (mysqli_query($con, $insert_query)) {
                        $update_query = "UPDATE tblregusers SET validity = $validity WHERE Email='$email'";
                        mysqli_query($con, $update_query);
                        
                        header("Location: validated.php");
                        exit();
                    } else {
                        error_log("MySQL Error: " . mysqli_error($con));
                    }
                } else {
                    $_SESSION['error_message'] = "Could not extract expiration date from the image.";
                    header('Location: validation.php');
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
} catch (Exception $e) {
    error_log("Upload Error: " . $e->getMessage());
    $_SESSION['error_message'] = "An unexpected error occurred.";
    header('Location: upload_form.php');
    exit();
} finally {
    mysqli_close($con);
}
