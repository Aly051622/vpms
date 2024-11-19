<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Driver's License</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Upload Driver's License</h2>

    <!-- Display Error Message -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);  // Clear the message after displaying
            ?>
        </div>
    <?php endif; ?>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="email">Enter your email:</label>
        <input type="text" id="email" name="email" required>

        <label for="license_image">Upload Driver's License:</label>
        <input type="file" id="license_image" name="license_image" required>

        <input type="submit" value="Upload">
    </form>
</div>

</body>
</html>
