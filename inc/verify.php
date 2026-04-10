<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP Settings
    $mail->isSMTP();
    $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
    $mail->SMTPAuth = true;
    $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site');
    $mail->Password = env('MAIL_PASSWORD', '');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL
    $mail->Port = 465;

    // Email settings
    $mail->setFrom('ntsah.site@ntsah.site', 'Admin');
    $mail->addAddress('rmday@nemsu.edu.ph'); // Replace with recipient email

    $mail->Subject = 'Test Email from PHPMailer';
    $mail->isHTML(true);
    $mail->Body = '<h3>This is a test email.</h3>';

    if ($mail->send()) {
        echo 'Email has been sent!';
    } else {
        echo 'Email failed to send.';
    }
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
?>