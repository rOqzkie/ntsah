<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
    $mail->SMTPAuth = true;
    $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site');
    $mail->Password = env('MAIL_PASSWORD', '');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('ntsah.site@ntsah.site', 'Admin');
    $mail->addAddress('euqoryad@gmail.com');

    $mail->Subject = 'SMTP Test Email';
    $mail->isHTML(true);
    $mail->Body = "<h3>Your student account has been successfully verified!</h3>
                               <p>You can now log in and access all features.</p>
                               <p><a href='https://ntsah.site/login.php'></a></p>";

    if ($mail->send()) {
        echo 'SMTP is working! Email sent.';
    } else {
        echo 'Email failed to send.';
    }
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
?>
