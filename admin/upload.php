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

    // Restrict file types for security
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($_FILES['license_image']['type'], $allowed_types)) {
        die("Invalid file type. Only JPEG and PNG are allowed.");
    }

    // Generate a unique file name and define the upload path
    $license_image = $_FILES['license_image']['name'];
    $unique_name = uniqid() . "_" . basename($license_image);
    $upload_path = realpath('../uploads/validated/') . DIRECTORY_SEPARATOR;

    // Ensure the upload directory exists
    if (!is_dir($upload_path)) {
        die("Upload directory does not exist.");
    }

    // Move the uploaded file to the target directory
    $target_file = $upload_path . $unique_name;
    if (!move_uploaded_file($_FILES['license_image']['tmp_name'], $target_file)) {
        die("Failed to move the uploaded file.");
    }

    // OCR API key and endpoint
    $ocr_api_key = getenv('OCR_API_KEY'); // Use environment variable for API key
    if (!$ocr_api_key) {
        die("OCR API key is missing. Set it in your environment.");
    }

    $ocr_url = 'https://api.ocr.space/parse/image';

    // Prepare the cURL request for OCR API
    $data = array(
        'apikey' => $ocr_api_key,
        'language' => 'eng',
        'file' => new CURLFile($target_file)
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ocr_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    $ocrResponse = curl_exec($ch);
    
    // Check for errors in the cURL request
    if ($ocrResponse === false) {
        die("OCR API request failed: " . curl_error($ch));
    }

    curl_close($ch);

    // Decode the OCR API response
    $ocrResult = json_decode($ocrResponse, true);

    // Check if the OCR response contains parsed text
    if (isset($ocrResult['ParsedResults'][0]['ParsedText'])) {
        $tesseract_output = $ocrResult['ParsedResults'][0]['ParsedText'];
    } else {
        die("No text found in the image.");
    }

    // Extract the expiration date using refined regex
    preg_match_all('/\b(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})\b|\b(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\b/', $tesseract_output, $matches);

    // Validate and format expiration dates
    $expiration_dates = [];
    foreach ($matches[0] as $date) {
        $timestamp = strtotime($date);
        if ($timestamp) {
            $expiration_dates[] = date("Y-m-d", $timestamp);
        }
    }

    // Check if expiration dates are found
    if (empty($expiration_dates)) {
        die("No valid expiration date found in the image. Please upload a clear image.");
    }

    // Use the latest date as the expiration date
    $expiration_date = max($expiration_dates);

    // Determine validity
    $current_date = date("Y-m-d");
    $validity = ($expiration_date >= $current_date) ? 1 : 0;

    // Insert into the database
    $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                     VALUES ('$email', '$unique_name', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

    if (mysqli_query($con, $insert_query)) {
        // Update user's validity
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
