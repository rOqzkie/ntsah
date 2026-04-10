<?php
require_once("./config.php");
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch folders from the database
$query = $conn->prepare("SELECT id, name FROM user_folders WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$folders = [];
while ($row = $result->fetch_assoc()) {
    $folders[] = $row;
}

// Return as JSON
echo json_encode($folders);
?>
