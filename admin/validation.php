<?php
session_start();

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['license_image'])) {
    // Define the file path
    $uploadDirectory = "../uploads/";  // Make sure this directory exists
    $targetFile = $uploadDirectory . basename($_FILES["license_image"]["name"]);

    // Move uploaded file to the server
    if (move_uploaded_file($_FILES["license_image"]["tmp_name"], $targetFile)) {
        // Use Tesseract OCR to extract text from the uploaded image
        $output = null;
        $resultCode = null;
        $imagePath = escapeshellarg($targetFile);
        
        // Run Tesseract OCR command on the uploaded image
        exec("tesseract $imagePath -c tessedit_char_whitelist=0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -l eng stdout", $output, $resultCode);

        // Check if Tesseract OCR was successful
        if ($resultCode == 0) {
            $text = implode("\n", $output); // Combine lines of text

            // Use regex to find the relevant payment dates in the text
            preg_match('/this payment is valid until\s*(\d{2}\/\d{2}\/\d{4})\s*and due for renewal on\s*(\d{2}\/\d{2}\/\d{4})\s*-\s*(\d{2}\/\d{2}\/\d{4})/i', $text, $matches);

            // Check if a match was found
            if ($matches) {
                $validUntil = $matches[1];  // This is the "valid until" date
                $dueStartDate = $matches[2];  // The start date for renewal
                $dueEndDate = $matches[3];    // The end date for renewal

                // Display the extracted dates
                echo "<div class='alert alert-success'>";
                echo "<strong>Payment Information:</strong><br>";
                echo "This payment is valid until: " . $validUntil . "<br>";
                echo "Due for renewal from: " . $dueStartDate . " to " . $dueEndDate . "<br>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning'>No expiration or renewal information found in the image.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error processing the image with Tesseract OCR.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>There was an error uploading the file.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Driver's License | CTU Danao Parking System</title>
    <style>
        body { background: whitesmoke; height: 100vh; display: flex; flex-direction: column; justify-content: flex-start; }
        .container { background-color: white; padding: 40px; border-radius: 10px; box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px; width: 500px; margin: 20px auto; }
        h2 { color: black; font-size: 30px; margin-left: 12em; }
        label { font-size: 14px; font-weight: bold; color: #333; }
        input[type="email"], input[type="file"] { width: 100%; padding: 8px; margin: 10px 0; border: 1px solid gray; border-radius: 7px; font-size: 14px; }
        #submit { width: 100%; padding: 10px; border-radius: 9px; background-color: rgb(53, 97, 255); color: white; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Driver's License</h2>
        <form action="validation.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>
            <label for="license_image">Select Driver's License Image:</label>
            <input type="file" id="license_image" name="license_image" accept="image/*" required><br>
            <button type="submit" id="submit">Submit</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
