<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
}

// Path to your Tesseract OCR executable
$tesseract_path = '/path/to/tesseract'; // Update this to your Tesseract installation path

// Function to extract text from an image using Tesseract OCR
function extractTextFromImage($imagePath) {
    global $tesseract_path;
    $output = null;
    $resultCode = null;
    // Run Tesseract OCR command to extract text from the image
    exec("$tesseract_path $imagePath -c tessedit_char_whitelist=0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz --psm 6 stdout", $output, $resultCode);
    return implode("\n", $output); // Return the text extracted from the image
}

// If the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['license_image']) && $_FILES['license_image']['error'] === UPLOAD_ERR_OK) {
        $licenseImage = $_FILES['license_image']['tmp_name'];

        // Extract text from the uploaded image
        $extractedText = extractTextFromImage($licenseImage);

        // Search for expiration date and renewal dates in the extracted text (You may need to adjust this regex based on actual format)
        preg_match('/payment is valid until (\d{2}\/\d{2}\/\d{4})/', $extractedText, $validUntilMatches);
        preg_match('/due for renewal on (\d{2}\/\d{2}\/\d{4}) - (\d{2}\/\d{2}\/\d{4})/', $extractedText, $renewalDatesMatches);

        $validUntil = isset($validUntilMatches[1]) ? $validUntilMatches[1] : 'Not found';
        $dueForRenewalStart = isset($renewalDatesMatches[1]) ? $renewalDatesMatches[1] : 'Not found';
        $dueForRenewalEnd = isset($renewalDatesMatches[2]) ? $renewalDatesMatches[2] : 'Not found';

        // Display the extracted information
        echo "<div class='alert alert-info'>";
        echo "<strong>Payment Valid Until:</strong> $validUntil <br>";
        echo "<strong>Due for Renewal from:</strong> $dueForRenewalStart to $dueForRenewalEnd <br>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>Error uploading image.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <title>Update Driver's License | CTU Danao Parking System</title>

    <style>
        body {
            background: whitesmoke;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Breadcrumbs styling */
        .breadcrumbs {
            padding: 15px;
        }
        .breadcrumbs h1 {
            color: black;
        }
        .page-title ol {
            margin-bottom: 0;
        }

        /* Container styling */
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;            
            width: 500px;
            margin: 20px auto; 
        }
        h2 {
            font-family: 'Poppins', sans-serif;
            color: black;
            font-size: 30px;
            margin-left: 12em;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid gray;
            border-radius: 7px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"]:hover,
        input[type="file"]:hover {
            background-color: whitesmoke;
            box-shadow: rgba(3, 102, 214, 0.3) 0px 0px 0px 3px;
        }
        #submit {
            width: 100%;
            padding: 10px;
            border-radius: 9px;
            background-color: rgb(53, 97, 255);
            color: white;
            font-weight: bold;
            border: 2px solid white;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #submit:hover {
            background-color: darkblue;
            border: 2px solid darkblue;
        }

        /* Error message styling */
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>
    <!-- Right Panel -->

    <?php include_once('includes/header.php'); ?>

    <!-- Breadcrumbs Section -->
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Driver's License Validation</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="validation.php">Validation</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Container Section -->
    <h2 class="mb-5">Update Driver's License</h2>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <label for="license_image">Upload OR Image (JPEG/PNG)</label>
            <input type="file" name="license_image" id="license_image" required />
            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</body>
</html>
