<?php
include "./config.php"; // Ensure this connects to your database

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $searchTerm = "%$query%";

    $stmt = $conn->prepare("
        SELECT DISTINCT title AS suggestion FROM archive_list WHERE title LIKE ? 
        UNION 
        SELECT DISTINCT members AS suggestion FROM archive_list WHERE members LIKE ? 
        UNION 
        SELECT DISTINCT keywords AS suggestion FROM archive_list WHERE keywords LIKE ?
        LIMIT 10
    ");
    
    if (!$stmt) {
        die("Query Error: " . $conn->error); // Debugging: Check for SQL errors
    }

    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Result Error: " . $conn->error); // Debugging: Check for execution errors
    }

    if ($result->num_rows > 0) {
        echo '<ul class="suggestions-list">';
        while ($row = $result->fetch_assoc()) {
            echo '<li class="suggestion-item">' . html_entity_decode($row['suggestion']) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="no-suggestions">No suggestions found</p>';
    }

    $stmt->close();
} else {
    echo "Error: No query received"; // Debugging: Check if the AJAX request sends data
}
?>