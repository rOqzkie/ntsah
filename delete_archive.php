<?php
require_once("./config.php");
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    echo "error";
    exit();
}

$user_id = $_SESSION['user_id'];
$archive_id = $_POST['id'];

// Delete the archive from user_folder_items
$delete_query = $conn->prepare("DELETE FROM user_folder_items WHERE archive_id = ? AND user_id = ?");
$delete_query->bind_param("ii", $archive_id, $user_id);

if ($delete_query->execute()) {
    echo "success";
} else {
    echo "error";
}
?>