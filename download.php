<?php
session_start();
include './config.php';

$doc_id = $_GET['id'] ?? null;

if (!$doc_id) {
    die("Error: No document ID provided.");
}

// Fetch the file path from `archive_list`
$stmt = $conn->prepare("SELECT document_path FROM archive_list WHERE id = ?");
$stmt->bind_param("i", $doc_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    die("Error: Document not found in database.");
}

// Remove query parameters (?v=xxxxx)
$file = strtok($result['document_path'], '?');

// Construct full path
$file_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/pdf/" . basename($file);

// Debugging output before forcing download
if (!file_exists($file_path)) {
    die("Error: File not found at " . htmlspecialchars($file_path));
}

// Force file download
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
flush();
readfile($file_path);
exit;
?>