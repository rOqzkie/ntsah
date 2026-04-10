<?php
session_start();
include './config.php';

$user_id = $_SESSION['user_id'] ?? 0; // Adjust based on your authentication system
$document_id = $_POST['id'] ?? 0;

if ($document_id == 0 || $user_id == 0) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Check if the user has already liked the document
$check_like_sql = "SELECT id FROM likes WHERE user_id = ? AND document_id = ?";
$stmt = $conn->prepare($check_like_sql);
$stmt->bind_param("ii", $user_id, $document_id);
$stmt->execute();
$like_result = $stmt->get_result();

if ($like_result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already liked"]);
    exit;
}

// Insert the like record
$insert_like_sql = "INSERT INTO likes (user_id, document_id) VALUES (?, ?)";
$stmt = $conn->prepare($insert_like_sql);
$stmt->bind_param("ii", $user_id, $document_id);
$stmt->execute();

// Get the updated like count
$count_sql = "SELECT COUNT(*) AS total_likes FROM likes WHERE document_id = ?";
$stmt = $conn->prepare($count_sql);
$stmt->bind_param("i", $document_id);
$stmt->execute();
$like_result = $stmt->get_result()->fetch_assoc();
$total_likes = $like_result['total_likes'];

// Update the archive_list table
$update_sql = "UPDATE archive_list SET likes = ? WHERE id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ii", $total_likes, $document_id);
$stmt->execute();

echo json_encode(["success" => true, "likes" => $total_likes]);
?>