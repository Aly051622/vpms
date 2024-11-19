<?php
session_start();

if (isset($_SESSION['extracted_texts'])) {
    echo "<h2>Extracted Texts from Images</h2>";
    foreach ($_SESSION['extracted_texts'] as $file => $text) {
        echo "<h3>From file: $file</h3>";
        echo "<pre>" . htmlspecialchars($text) . "</pre>";
    }
    unset($_SESSION['extracted_texts']); // Clear the session variable
} else {
    echo "No extracted texts available.";
}
?>
