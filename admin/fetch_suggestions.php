<?php
include('includes/dbconnection.php');

if (isset($_POST['query'])) {
    // Escape user input to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($con, $_POST['query']);
    
    // Prepare the SQL query to search for owner names or registration numbers
    $query = "SELECT OwnerName, RegistrationNumber FROM tblvehicle 
              WHERE OwnerName LIKE '%$searchTerm%' 
              OR RegistrationNumber LIKE '%$searchTerm%' 
              LIMIT 5";
    
    $result = mysqli_query($con, $query);

    // Check if there are results and output them as a list
    if (mysqli_num_rows($result) > 0) {
        echo '<ul style="list-style-type: none; padding: 0;">'; // Added styling for a cleaner look
        while ($row = mysqli_fetch_array($result)) {
            echo '<li style="padding: 5px; cursor: pointer;" class="suggestion-item" data-owner="' . htmlspecialchars($row['OwnerName']) . '" data-reg="' . htmlspecialchars($row['RegistrationNumber']) . '">' 
                 . htmlspecialchars($row['OwnerName']) . ' - ' . htmlspecialchars($row['RegistrationNumber']) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<ul style="list-style-type: none; padding: 0;"><li>No suggestions found</li></ul>';
    }
}
?>
