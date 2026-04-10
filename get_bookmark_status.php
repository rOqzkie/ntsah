<?php
session_start();
include './config.php';

$user_id = $_SESSION['user_id']; // Ensure users are authenticated
$doc_id = $_GET['id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS bookmarked FROM bookmarks WHERE user_id = ? AND document_id = ?");
$stmt->bind_param("ii", $user_id, $doc_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "bookmarked" => $result['bookmarked'] > 0
]);
?>