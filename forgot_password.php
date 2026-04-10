<?php
require_once('./config.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';
//require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    // Delete expired tokens
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE expires_at < NOW()");
    $stmt->execute();
    
    // Check if the email exists
    $stmt = $conn->prepare("SELECT id FROM student_list WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=?, expires_at=?");
        $stmt->bind_param("sssss", $email, $token, $expires, $token, $expires);
        $stmt->execute();
        
        // Send Reset Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site');
            $mail->Password = env('MAIL_PASSWORD', '');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom(env('MAIL_FROM', 'ntsah.site@ntsah.site'), 'NEMSU Archiving Hub');
            $mail->addAddress($email);
            
            $resetLink = "https://ntsah.site/reset_password.php?token=$token";
            
            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->Body    = "<p>Click the link below to reset your password:</p><p><a href='$resetLink'>$resetLink</a></p>";
            
            $mail->send();
            echo "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Email sending failed. Please try again later.";
        }
    } else {
        echo "Email not found.";
    }
} else {
    echo "Invalid request.";
}
?>