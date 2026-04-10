<?php
require_once('./config.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify token validity
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];
    } else {
        die("Invalid or expired token.");
    }
} else {
    die("No token provided.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if (empty($email)) {
        die("Error: Email not found for this token.");
    }
    
    echo "Debug: Form submitted successfully."; // Add this for debugging
    
    $new_password = md5($_POST['password']);

    $stmt = $conn->prepare("UPDATE student_list SET password = ? WHERE email = ?");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $new_password, $email);
    if ($stmt->execute()) {
        echo "Password reset successfully.<a href='login.php'>Login here</a>";
    } else {
        echo "Error updating password.";
    }
    
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <label for="password">New Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
    <!--
    <form method="POST">
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" id="reset-btn">Reset Password</button>
    </form>
    -->
</body>
</html>