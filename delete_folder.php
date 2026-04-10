<?php
require_once("./config.php");
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    echo "error";
    exit();
}

$user_id = $_SESSION['user_id'];
$folder_id = $_POST['id'];

// Delete all archive entries linked to this folder
$delete_archives = $conn->prepare("DELETE FROM user_folder_items WHERE folder_id = ? AND user_id = ?");
$delete_archives->bind_param("ii", $folder_id, $user_id);
$delete_archives->execute();

// Delete the folder itself
$delete_folder = $conn->prepare("DELETE FROM user_folders WHERE id = ? AND user_id = ?");
$delete_folder->bind_param("ii", $folder_id, $user_id);

if ($delete_folder->execute()) {
    echo "success";
} else {
    echo "error";
}
?>