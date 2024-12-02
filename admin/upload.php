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

    // Check if the file already exists in the database
    // First, sanitize the filename to ensure it’s safe
    $license_image_safe = basename($license_image);

    // Check if the file already exists in the database
    $query_check = "SELECT * FROM uploads WHERE filename = '$license_image_safe'";
    $result_check = mysqli_query($con, $query_check);

    // If file exists, append a unique identifier (e.g., timestamp or random string)
    if (mysqli_num_rows($result_check) > 0) {
        $file_extension = pathinfo($license_image_safe, PATHINFO_EXTENSION);
        $filename_without_extension = pathinfo($license_image_safe, PATHINFO_FILENAME);
        $license_image_safe = $filename_without_extension . '_' . time() . '.' . $file_extension;
    }

    // Move the uploaded file to the target directory
    $target_file = $upload_path . $license_image_safe;
    if (!move_uploaded_file($_FILES['license_image']['tmp_name'], $target_file)) {
        die("Failed to move the uploaded file.");
    }

    // OCR API key and endpoint
    $ocr_api_key = 'K86756414488957'; // Replace with your actual API key
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

    // Decode the OCR API response
    $ocrResult = json_decode($ocrResponse, true);

    // Check if the OCR response contains parsed text
    if (isset($ocrResult['ParsedResults'][0]['ParsedText'])) {
        $tesseract_output = $ocrResult['ParsedResults'][0]['ParsedText'];
    } else {
        die("No text found in the image.");
    }

    // Attempt to extract the expiration date from the OCR output
    // Regex for date: This will match dates in various formats like YYYY/MM/DD, YYYY-MM-DD, etc.
    preg_match_all('/\b(\d{4})[\/\-\s]?\d{1,2}[\/\-\s]?\d{1,2}\b/', $tesseract_output, $matches);

    if (empty($matches[0])) {
        die("No expiration date found in the image.");
    }

    // Extract the first match (assuming it's the expiration date)
    $expiration_date_str = $matches[0][0];

    // Debugging: Display the extracted expiration date
    // echo "Extracted Expiration Date: " . $expiration_date_str . "<br>";

    // Normalize the expiration date to a standard format (YYYY-MM-DD)
    // Fix any invalid date formats if necessary, e.g., 2033/10103 -> 2033/10/03
    $expiration_date_str = preg_replace('/(\d{4})\/(\d{2})\/(\d{3})/', '$1/$2/01', $expiration_date_str);

    // Convert to standard date format (YYYY-MM-DD)
    $expiration_date = date("Y-m-d", strtotime($expiration_date_str));

    // Display or use the expiration date as needed
    echo "Expiration Date: " . $expiration_date . "<br>";

    // Insert the expiration date into the database (optional)
    $current_date = date("Y-m-d");
    $validity = ($expiration_date >= $current_date) ? 1 : 0;

    // Prepare the insert query
    $insert_query = "INSERT INTO uploads (email, filename, file_size, file_type, uploaded_at, status, expiration_date, validity) 
                     VALUES ('$email', '$license_image_safe', {$_FILES['license_image']['size']}, '{$_FILES['license_image']['type']}', NOW(), 'approved', '$expiration_date', $validity)";

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
