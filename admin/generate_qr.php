<?php
require_once __DIR__ . '/vendor/autoload.php';  // Include Composer's autoloader

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Fetch the encryption key from the environment variables
$encryptionKey = getenv('ENCRYPTION_KEY');
if (!$encryptionKey) {
    die("Encryption key not set in environment variables.");
}
$encryptionKey = base64_decode($encryptionKey);  // Decode the key if it's base64 encoded

$cipher = "AES-256-CBC";  // Encryption method
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));  // Initialization vector

// Generate random values for the QR code
$randomVehicleType = bin2hex(random_bytes(3));  // Random vehicle type
$randomPlateNumber = bin2hex(random_bytes(5));  // Random plate number
$randomName = bin2hex(random_bytes(6));  // Random name
$randomContactNumber = bin2hex(random_bytes(4));  // Random contact number
$randomModel = bin2hex(random_bytes(4));  // Random model

// Prepare the data for encryption
$qrCodeData = json_encode([
    'VehicleType' => $randomVehicleType,
    'PlateNumber' => $randomPlateNumber,
    'Name' => $randomName,
    'ContactNumber' => $randomContactNumber,
    'Model' => $randomModel,
]);

// Debug: Display the data before encryption
echo "<pre>";
echo "Data to encrypt: ";
print_r($qrCodeData);
echo "</pre>";

// Encrypt the data
$encryptedQrCodeData = openssl_encrypt($qrCodeData, $cipher, $encryptionKey, 0, $iv);
$encryptedQrCodeData = base64_encode($encryptedQrCodeData . "::" . base64_encode($iv));  // Base64 encode the encrypted data and IV

// Debug: Display the encrypted data
echo "<pre>";
echo "Encrypted Data: ";
print_r($encryptedQrCodeData);
echo "</pre>";

// Generate the QR Code with encrypted data
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($encryptedQrCodeData) . "&size=150x150";
$qrImageName = "qr_" . time() . ".png";
$qrImagePath = "qrcodes/" . $qrImageName;
$qrCodeContent = file_get_contents($qrCodeUrl);
file_put_contents($qrImagePath, $qrCodeContent);

// Show success message with path to the saved QR code
echo "QR Code generated successfully. Check it at: " . $qrImagePath;
?>
