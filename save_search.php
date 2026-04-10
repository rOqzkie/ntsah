<?php
require './config.php';

if (isset($_GET['q']) && strlen(trim($_GET['q'])) > 1) {
    $query = trim($_GET['q']);

    // Check if the keyword already exists
    $stmt = $conn->prepare("SELECT id FROM search_history WHERE keyword = ?");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If it exists, increase the frequency count
        $stmt = $conn->prepare("UPDATE search_history SET frequency = frequency + 1 WHERE keyword = ?");
        $stmt->bind_param("s", $query);
    } else {
        // Otherwise, insert as a new entry
        $stmt = $conn->prepare("INSERT INTO search_history (keyword, frequency) VALUES (?, 1)");
        $stmt->bind_param("s", $query);
    }
    
    $stmt->execute();
    $stmt->close();
}
?>