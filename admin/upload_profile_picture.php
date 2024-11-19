<?php
session_start();

// Define the directory where the profile pictures will be uploaded
$uploadDir = '../uploads/profile_pictures/';
$uploadFile = $uploadDir . basename($_FILES['profile_picture']['name'] ?? '');

// Check if the upload directory exists, if not, create it
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if the file was uploaded
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    // Check if the uploaded file is a valid image
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $validImageTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $validImageTypes)) {
        // Attempt to move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            // Update the profile picture in the session
            $_SESSION['profilePicture'] = $uploadFile;

            // Redirect to a page where the user can see their updated profile
            header('Location: admin-profile.php?uploadsuccess=1');
            exit();
        } else {
            echo "Error: Unable to upload the file.";
        }
    } else {
        echo "Error: The file you uploaded is not a valid image format.";
    }
} else {
    echo "Error: No file uploaded or there was an upload error.";
}
?>
