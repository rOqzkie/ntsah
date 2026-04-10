<?php
session_start();
include './config.php';

$user_id = $_SESSION['user_id'] ?? 0; // Adjust this based on your authentication system
$document_id = $_GET['id'] ?? 0;

if ($document_id == 0) {
    echo json_encode(["success" => false, "message" => "Invalid document ID"]);
    exit;
}

// Check if the user has already liked the document
$check_like_sql = "SELECT COUNT(*) AS liked FROM likes WHERE user_id = ? AND document_id = ?";
$stmt = $conn->prepare($check_like_sql);
$stmt->bind_param("ii", $user_id, $document_id);
$stmt->execute();
$like_result = $stmt->get_result()->fetch_assoc();
$liked = $like_result['liked'] > 0 ? true : false;

// Get total like count
$count_sql = "SELECT COUNT(*) AS total_likes FROM likes WHERE document_id = ?";
$stmt = $conn->prepare($count_sql);
$stmt->bind_param("i", $document_id);
$stmt->execute();
$like_result = $stmt->get_result()->fetch_assoc();
$total_likes = $like_result['total_likes'];

echo json_encode(["liked" => $liked, "likes" => $total_likes]);
?>