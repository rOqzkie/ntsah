<?php
require_once("./config.php");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit();
}

$user_id = $_SESSION['user_id'];
$archive_id = $_POST['archive_id'];
$folder_id = $_POST['folder_id'] ?? null;
$new_folder_name = trim($_POST['new_folder'] ?? '');

// If the user provided a new folder name, create it first
if (!empty($new_folder_name)) {
    // Check if the folder already exists
    $check_query = $conn->prepare("SELECT id FROM user_folders WHERE user_id = ? AND name = ?");
    $check_query->bind_param("is", $user_id, $new_folder_name);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        // Folder already exists, get its ID
        $folder_id = $check_result->fetch_assoc()['id'];
    } else {
        // Create the new folder
        $insert_folder = $conn->prepare("INSERT INTO user_folders (user_id, name) VALUES (?, ?)");
        $insert_folder->bind_param("is", $user_id, $new_folder_name);
        if ($insert_folder->execute()) {
            $folder_id = $conn->insert_id; // Get the newly created folder's ID
        } else {
            echo "folder_creation_failed";
            exit();
        }
    }
}

// Ensure a valid folder ID is available
if (!$folder_id) {
    echo "invalid_folder";
    exit();
}

// Check if the thesis/FS is already in the folder
$check_item = $conn->prepare("SELECT id FROM user_folder_items WHERE user_id = ? AND folder_id = ? AND archive_id = ?");
$check_item->bind_param("iii", $user_id, $folder_id, $archive_id);
$check_item->execute();
$check_item_result = $check_item->get_result();

if ($check_item_result->num_rows > 0) {
    echo "already_saved";
    exit();
}

// Save the thesis/FS in the selected folder
$insert_item = $conn->prepare("INSERT INTO user_folder_items (user_id, folder_id, archive_id) VALUES (?, ?, ?)");
$insert_item->bind_param("iii", $user_id, $folder_id, $archive_id);

if ($insert_item->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
