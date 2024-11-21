<?php
session_start();
require '../vendor/autoload.php'; // Ensure this path is correct

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['verification_email'])) {
    $email = $_SESSION['verification_email'];

    // Generate a verification code
    $verificationCode = rand(100000, 999999);
    $_SESSION['verification_code'] = $verificationCode;

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'developershalcyon@gmail.com'; 
        $mail->Password = 'uhdv sagp oljc smwm'; // Use your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Enable verbose debug output
        $mail->SMTPDebug = 2; // Set to 2 for full debugging output

        // Recipients
        $mail->setFrom('developershalcyon@gmail.com', 'CTU Parking System');
        $mail->addAddress($email);
        $mail->Subject = 'Email Verification Code';
        $mail->Body = 'Your verification code is: ' . $verificationCode;

        // Send the email
        if ($mail->send()) {
            // Redirect to verify.php if the email is sent successfully
            header("Location: verify.php");
            exit; // Ensure no further code is executed
        } else {
            error_log('Email not sent: ' . $mail->ErrorInfo);
            echo json_encode(['success' => false, 'message' => 'Failed to send verification code.']);
        }
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No email session found.']);
}
