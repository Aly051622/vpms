<?php
session_start();
include 'includes/dbconnection.php';

$dir = 'uploads/validity'; // Directory where images are stored
$files = scandir($dir); // Get all files in the directory

$extracted_texts = []; // Array to store extracted texts

foreach ($files as $file) {
    // Check if the file is an image
    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
        $image_path = $dir . '/' . $file;

        // Command to run Tesseract
        $output_file = tempnam(sys_get_temp_dir(), 'tess_'); // Create a temporary file
        $command = "tesseract $image_path $output_file --psm 6"; // Use Page Segmentation Mode 6 (Assumes a single uniform block of text)

        // Execute Tesseract command
        exec($command, $output, $return_var);

        // Check for errors
        if ($return_var === 0) {
            // Read the extracted text from the temporary file
            $extracted_text = file_get_contents($output_file . '.txt');
            $extracted_texts[$file] = $extracted_text; // Store in array
        } else {
            $extracted_texts[$file] = "Could not extract text from the image.";
        }

        // Clean up temporary files
        unlink($output_file . '.txt'); // Remove the temporary text file
    }
}

// Store extracted texts in session for display
$_SESSION['extracted_texts'] = $extracted_texts;

// Redirect to a page to display the results
header('Location: display_extracted_text.php');
exit();
?>
