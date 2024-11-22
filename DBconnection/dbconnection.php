<?php
try {
    // Create a new PDO instance
    $con = new PDO("mysql:host=localhost;dbname=u132092183_parkingz", "u132092183_parkingz", "@Parkingz!2024");
    
    // Set the PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Uncomment for debugging
    // echo "Database connected successfully.";
} catch (PDOException $e) {
    // Handle the error if the connection fails
    echo "Connection Failed: " . $e->getMessage();
    exit(); // Stop execution if the connection fails
}
?>
