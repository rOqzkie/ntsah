<?php
// Show errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./config.php');
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');
require_once('PHPMailer/src/Exception.php');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'] ?? $_GET['email'] ?? '';
$message = '';
$cooldown = 300; // 5 minutes in seconds
$lastSentTimestamp = 0;

// 🟡 Fetch latest OTP sent time for the given email
/*if (!empty($email)) {
    $stmt = $conn->prepare("SELECT created_at FROM otp_codes WHERE email = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($lastSent);
    $stmt->fetch();
    $stmt->close();

    if (!empty($lastSent)) {
        $lastSentTimestamp = strtotime($lastSent);
    }
}*/
if (!empty($email)) {
    $stmt = $conn->prepare("SELECT UNIX_TIMESTAMP(created_at) FROM otp_codes WHERE email = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($lastSentTimestamp);
    $stmt->fetch();
    $stmt->close();

    if (!$lastSentTimestamp) {
        $lastSentTimestamp = 0;
    }
}

// 🟢 OTP Verification Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'], $_POST['email'])) {
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp = ? AND created_at >= NOW() - INTERVAL 10 MINUTE");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mark user as verified
        $update = $conn->prepare("UPDATE student_list SET is_verified = 1 WHERE email = ?");
        //$update = $conn->prepare("UPDATE student_list WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();

        // Remove OTP
        $delete = $conn->prepare("DELETE FROM otp_codes WHERE email = ?");
        $delete->bind_param("s", $email);
        $delete->execute();

        header("Location: login.php?verified=1");
        exit();
    } else {
        $message = "Invalid or expired OTP.";
    }
}

// 🟢 Resend OTP Logic
if (isset($_GET['resend']) && $email) {
    $canResend = true;

    if ($lastSentTimestamp && (time() - $lastSentTimestamp) < $cooldown) {
        $wait = $cooldown - (time() - $lastSentTimestamp);
        $message = "Please wait {$wait} seconds before requesting a new OTP.";
        $canResend = false;
    }

    if ($canResend) {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $stmt = $conn->prepare("INSERT INTO otp_codes (email, otp) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $stmt->close();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site');
            $mail->Password = env('MAIL_PASSWORD', '');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('ntsah.site@ntsah.site', 'NEMSU Archiving Hub');
            $mail->addAddress($email);
            $mail->Subject = 'Your OTP Code';
            $mail->isHTML(true);
            $mail->Body = "<p>Your OTP code is <strong>{$otp}</strong>. It will expire in 10 minutes.</p>";

            $mail->send();
            $message = "A new OTP has been sent to your email.";
            $lastSentTimestamp = time();
        } catch (Exception $e) {
            $message = "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .disabled-link {
            pointer-events: none;
            color: #adb5bd;
            text-decoration: none;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-lg w-100" style="max-width: 420px;">
        <div class="card-body p-4">
            <h4 class="card-title text-center mb-4">OTP Verification</h4>

            <form method="post" action="verify_otp.php">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                <div class="mb-3">
                    <label for="otp" class="form-label">Enter the 6-digit OTP sent to your email</label>
                    <input type="text" name="otp" id="otp" class="form-control" required pattern="\d{6}" maxlength="6" placeholder="Enter OTP">
                </div>

                <button type="submit" class="btn btn-success w-100">Verify OTP</button>
            </form>

            <div class="text-center mt-3">
                <a id="resendBtn" class="text-decoration-none" href="verify_otp.php?resend=1&email=<?= urlencode($email) ?>" aria-disabled="false">Resend OTP</a>
                <div id="countdown" class="small text-muted mt-1"></div>
            </div>

            <?php if ($message): ?>
                <div class="alert mt-3 <?= (str_contains($message, 'successfully') || str_contains($message, 'sent')) ? 'alert-success' : 'alert-danger' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resendBtn = document.getElementById('resendBtn');
        const countdownEl = document.getElementById('countdown');
        const cooldownSeconds = 300; // 5 minutes
        const lastSent = <?= json_encode($lastSentTimestamp) ?>;
        const now = Math.floor(Date.now() / 1000);
        let timeLeft = cooldownSeconds - (now - lastSent);

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes}:${secs.toString().padStart(2, '0')}`;
        }

        function updateCountdown() {
            if (timeLeft <= 0) {
                resendBtn.classList.remove('disabled-link');
                resendBtn.setAttribute('aria-disabled', 'false');
                countdownEl.textContent = '';
                clearInterval(timer);
            } else {
                resendBtn.classList.add('disabled-link');
                resendBtn.setAttribute('aria-disabled', 'true');
                countdownEl.textContent = `You can resend OTP in ${formatTime(timeLeft)}`;
                timeLeft--;
            }
        }

        if (timeLeft > 0) {
            updateCountdown();
            var timer = setInterval(updateCountdown, 1000);
        }
    });
</script>
</body>
</html>