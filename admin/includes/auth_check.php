<?php
session_start();

if (!isset($_SESSION['vpmsaid'])) {
    // Redirect to login page if admin is not logged in
    header('location: index.php');
    exit;
}
