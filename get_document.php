<?php
require_once './config.php';

if (!isset($_GET['id'])) {
    die(json_encode(["success" => false, "message" => "No document ID provided"]));
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT document_path FROM archive_list WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result()->fetch_assoc();

if (!$result) {
    die(json_encode(["success" => false, "message" => "No file found for this ID"]));
}

// Remove ?v=xxx from file path
$file_path = strtok($result['document_path'], '?'); // Strips the version query
$absolute_path = __DIR__ . '/' . $file_path; // Absolute path

if (!file_exists($absolute_path)) {
    die(json_encode(["success" => false, "message" => "File does not exist: " . htmlspecialchars($absolute_path)]));
}

echo json_encode(["success" => true, "file_path" => $file_path]);
?>