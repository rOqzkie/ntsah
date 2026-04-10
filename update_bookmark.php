<?php
session_start();
include './config.php';

$user_id = $_SESSION['user_id']; // Ensure user authentication
$doc_id = $_POST['id'];
$action = $_POST['action'];

if ($action === "add") {
    // Insert bookmark
    $stmt = $conn->prepare("INSERT IGNORE INTO bookmarks (user_id, document_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $doc_id);
    $success = $stmt->execute();
} else {
    // Remove bookmark
    $stmt = $conn->prepare("DELETE FROM bookmarks WHERE user_id = ? AND document_id = ?");
    $stmt->bind_param("ii", $user_id, $doc_id);
    $success = $stmt->execute();
}

echo json_encode(["success" => $success]);
?>