<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila');

include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $catename = $_POST['catename'];
        $vehcomp = $_POST['vehcomp'];
        $model = $_POST['model'];
        $color = $_POST['color'];
        $vehreno = $_POST['vehreno'];
        $ownername = $_POST['ownername'];
        $ownercontno = $_POST['ownercontno'];

        if ($_POST['model'] === "Others, please specify") {
            $model = $_POST['otherModel'];
        }

        $imagePath = '';
        if ($vehcomp === 'Chevrolet') {
            if ($model === 'Tracker') {
                $imagePath = '../admin/vehicles/chevrolet/tracker.png';
            } elseif ($model === 'Trailblazer') {
                $imagePath = '../admin/vehicles/chevrolet/trailblazer.png';
            } elseif ($model === 'Suburban') {
                $imagePath = '../admin/vehicles/chevrolet/suburban.jpg';
            } elseif ($model === 'Corvette') {
                $imagePath = '../admin/vehicles/chevrolet/corvette.png';
            } elseif ($model === 'Tahoe') {
                $imagePath = '../admin/vehicles/chevrolet/tahoe.jpg';
            } elseif ($model === 'Trax') {
                $imagePath = '../admin/vehicles/chevrolet/trax.png';
            } elseif ($model === 'Captiva') {
                $imagePath = '../admin/vehicles/chevrolet/captiva.png';
            } elseif ($model === 'Camaro') {
                $imagePath = '../admin/vehicles/chevrolet/camaro.png';
            }
        } elseif ($vehcomp === 'Honda Motors') {
            if ($model === 'DIO') {
                $imagePath = '../admin/vehicles/honda motors/honda_dio.png';
            }
        }

        $checkPlateQuery = mysqli_query($con, "SELECT * FROM tblvehicle WHERE RegistrationNumber='$vehreno'");
        $plateExists = mysqli_num_rows($checkPlateQuery);

        if ($plateExists > 0) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { 
                    showModal('Plate Number already exists'); 
                });</script>";
        } else {
            $checkContactQuery = mysqli_query($con, "SELECT * FROM tblregusers WHERE MobileNumber='$ownercontno'");
            $userExists = mysqli_num_rows($checkContactQuery);

            if ($userExists > 0) {
                $userData = mysqli_fetch_assoc($checkContactQuery);
                $firstName = $userData['FirstName'];
                $lastName = $userData['LastName'];
                $contactno = $userData['MobileNumber'];

                // Generate QR Code Data
                $qrCodeData = json_encode([
                    'VehicleType' => $catename,
                    'PlateNumber' => $vehreno,
                    'Name' => $ownername,
                    'ContactNumber' => $ownercontno,
                    'Model' => $model
                ]);

                // Generate the QR code
                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qrCodeData) . "&size=150x150";

                // Save the generated QR code as an image
                $qrImageName = "qr_" . uniqid() . ".png"; // Generate a unique name for the QR code image
                $qrImagePath = "qrcodes/" . $qrImageName;
                $qrCodeContent = file_get_contents($qrCodeUrl);
                if ($qrCodeContent === false) {
                    die("Failed to fetch QR code from the server.");
                }

                if (file_put_contents($qrImagePath, $qrCodeContent) === false) {
                    die("Failed to save the QR code image.");
                }

                $inTime = date('Y-m-d H:i:s');

                // Update INSERT query to include the ImagePath column
                $query = "INSERT INTO tblvehicle (VehicleCategory, VehicleCompanyname, Model, Color, RegistrationNumber, OwnerName, OwnerContactNumber, QRCodePath, ImagePath, InTime) 
                          VALUES ('$catename', '$vehcomp', '$model', '$color', '$vehreno', '$ownername', '$ownercontno', '$qrImagePath', '$imagePath', '$inTime')";

                if (mysqli_query($con, $query)) {
                    echo "<script>alert('Vehicle Entry Detail has been added');</script>";
                    echo "<script>window.location.href ='manage-reg.php'</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
                }
            } else {
                echo "<script>alert('Contact number not found in the user database. Please ensure the contact number is registered.');</script>";
            }
        }
    }
}
?>
