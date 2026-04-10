<?php
require_once("./config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit();
}

$user_id = $_SESSION['user_id'];
$archive_id = $_POST['archive_id'];

// Check if already saved
$check = $conn->query("SELECT * FROM user_library WHERE user_id = '$user_id' AND archive_id = '$archive_id'");
if ($check->num_rows > 0) {
    echo "already_saved";
    exit();
}

// Insert into user library
$save = $conn->query("INSERT INTO user_library (user_id, archive_id) VALUES ('$user_id', '$archive_id')");
if ($save) {
    echo "success";
} else {
    echo "error";
}
?>