<?php
ob_start();
ini_set('display_errors', 0);
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2 || !isset($_SESSION['department_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$departmentId = $_SESSION['department_id'];
$startYear = isset($_GET['startYear']) ? (int)$_GET['startYear'] : null;
$endYear = isset($_GET['endYear']) ? (int)$_GET['endYear'] : null;

$whereClause = "WHERE a.status = 1 AND a.department_id = $departmentId";

if ($startYear) $whereClause .= " AND a.year >= $startYear";
if ($endYear) $whereClause .= " AND a.year <= $endYear";

$sql = "SELECT d.name AS label, COUNT(a.id) AS count
        FROM archive_list a
        INNER JOIN department_list d ON a.department_id = d.id
        $whereClause
        GROUP BY d.id
        ORDER BY label ASC";

$result = $conn->query($sql);

$labels = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['label'];
    $counts[] = $row['count'];
}

ob_clean();
echo json_encode(['labels' => $labels, 'counts' => $counts]);
?>