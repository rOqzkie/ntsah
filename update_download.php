<?php
/*include './config.php';

if (!isset($_POST['id'])) {
    die(json_encode(["success" => false, "message" => "No document ID provided"]));
}

$id = intval($_POST['id']);

// Update download count in the database
$query = $conn->prepare("UPDATE archive_list SET downloads = downloads + 1 WHERE id = ?");
$query->bind_param("i", $id);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Download count updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update download count"]);
}*/
?>
<?php
require './config.php'; // Adjust as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $docId = intval($_POST['id']);
    $updateQuery = "UPDATE archive_list SET downloads = downloads + 1 WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $docId);
    
    if ($stmt->execute()) {
        // Fetch updated count
        $result = $conn->query("SELECT downloads FROM archive_list WHERE id = $docId");
        $newCount = $result->fetch_assoc()['downloads'];
        echo json_encode(["success" => true, "new_count" => $newCount]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }
}
?>