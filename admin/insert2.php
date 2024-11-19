<?php
session_start();

$server = "localhost";
$username = "root";
$password = "";
$dbname = "parkingz";

$conn = new mysqli($server, $username, $password, $dbname);

if($conn->connect_error){
    die("Connection failed" .$conn->connect_error);
}

if(isset($_POST['text'])){	   
    $text = $_POST['text'];

    $sql = "INSERT INTO table_logout(STUDENTID, TIMEOUT) VALUES('$text', NOW())";
    if($conn->query($sql) === TRUE){
        $_SESSION['success'] = 'Added Successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
}

header("location: qrlogout.php");
$conn->close();
?>
