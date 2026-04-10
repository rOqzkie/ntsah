<?php
ini_set('display_errors', 0);
include './config.php';
header('Content-Type: application/json');

$category = isset($_GET['category']) ? $_GET['category'] : 'college';
$startYear = isset($_GET['startYear']) ? (int)$_GET['startYear'] : null;
$endYear = isset($_GET['endYear']) ? (int)$_GET['endYear'] : null;

$labels = [];
$counts = [];

$whereClause = "WHERE a.status = 1";

// Year filtering
if ($startYear && $endYear) {
    $whereClause .= " AND a.year BETWEEN $startYear AND $endYear";
} elseif ($startYear) {
    $whereClause .= " AND a.year >= $startYear";
} elseif ($endYear) {
    $whereClause .= " AND a.year <= $endYear";
}

// Build the SQL based on category
// archive_list has curriculum_id (-> curriculum_list) and department_id, but NOT college_id directly.
// Join through curriculum_list to reach college.
if ($category === 'college') {
    $sql = "SELECT c.name AS label, COUNT(a.id) AS count 
            FROM archive_list a
            INNER JOIN curriculum_list cu ON a.curriculum_id = cu.id
            INNER JOIN college_list c ON cu.college_id = c.id
            $whereClause
            GROUP BY c.id
            ORDER BY label ASC";
} elseif ($category === 'department') {
    $sql = "SELECT d.name AS label, COUNT(a.id) AS count 
            FROM archive_list a
            INNER JOIN department_list d ON a.department_id = d.id
            $whereClause
            GROUP BY d.id
            ORDER BY label ASC";
} elseif ($category === 'program') {
    $sql = "SELECT p.name AS label, COUNT(a.id) AS count 
            FROM archive_list a
            INNER JOIN curriculum_list p ON a.curriculum_id = p.id
            $whereClause
            GROUP BY p.id
            ORDER BY label ASC";
} else {
    echo json_encode(['error' => 'Invalid category']);
    exit;
}

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['label'];
    $counts[] = $row['count'];
}

echo json_encode(['labels' => $labels, 'counts' => $counts]);
?>