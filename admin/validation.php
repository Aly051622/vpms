<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
}

// Check if form is submitted and process image
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $license_image = $_FILES['license_image'];

    // Check if image was uploaded
    if ($license_image['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $license_image['tmp_name'];
        $file_name = basename($license_image['name']);
        $upload_dir = '../uploads/driver_licenses/';
        $uploaded_file = $upload_dir . $file_name;

        // Move uploaded file to the target directory
        if (move_uploaded_file($tmp_name, $uploaded_file)) {
            // Call Tesseract to read the image and extract text
            $output = shell_exec("tesseract {$uploaded_file} -c tessedit_char_whitelist=0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ --psm 6 stdout");

            // Extract expiration date from the text
            preg_match('/(valid until|expires on)[^a-zA-Z]*(\d{2}\/\d{2}\/\d{4})/i', $output, $matches);

            if (count($matches) > 0) {
                $expiration_date = $matches[2]; // Extracted expiration date
                $renewal_period = date('Y-m-d', strtotime($expiration_date . ' +3 months')); // Due date for renewal is 3 months after expiration
                echo "<p>This payment is valid until {$expiration_date} and due for renewal on {$renewal_period}.</p>";
            } else {
                echo "<p>Expiration date could not be extracted from the image.</p>";
            }
        } else {
            echo "<p>Failed to upload the image.</p>";
        }
    } else {
        echo "<p>Error uploading the image.</p>";
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
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

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

    <h2 class="mb-5">Update Driver's License</h2>
    <div class="container">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>

            <label for="license_image">Select Driver's License Image:</label>
            <input type="file" id="license_image" name="license_image" accept="image/*" required><br>

            <button type="submit" id="submit">Submit</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
