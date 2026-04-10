<?php
session_start();
include './config.php';

$doc_id = $_GET['id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS downloads FROM downloads WHERE document_id = ?");
$stmt->bind_param("i", $doc_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "downloads" => $result['downloads']
]);
?>