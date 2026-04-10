<?php
header('Content-Type: application/json');
include './config.php'; // Ensure this file correctly connects to your database

$category = $_POST['category'] ?? 'college';
$startYear = $_POST['startYear'] ?? null;
$endYear = $_POST['endYear'] ?? null;

$allowedCategories = ['college', 'department', 'program', 'discipline'];
if (!in_array($category, $allowedCategories)) {
    echo json_encode(['labels' => [], 'counts' => []]);
    exit;
}

// Mapping category to table joins
$categoryMap = [
    'college' => ['table' => 'college_list', 'column' => 'college_id'],
    'department' => ['table' => 'department_list', 'column' => 'department_id'],
    'program' => ['table' => 'curriculum_list', 'column' => 'curriculum_id'],
    'discipline' => ['table' => 'discipline_list', 'column' => 'discipline_id']
];

$tableInfo = $categoryMap[$category];
$joinTable = $tableInfo['table'];
$foreignKey = $tableInfo['column'];

// SQL with JOIN
$sql = "SELECT jt.name AS label, COUNT(*) AS count
        FROM archive_list al
        LEFT JOIN {$joinTable} jt ON al.{$foreignKey} = jt.id
        WHERE 1";

$params = [];

if (!empty($startYear)) {
    $sql .= " AND al.year >= ?";
    $params[] = $startYear;
}
if (!empty($endYear)) {
    $sql .= " AND al.year <= ?";
    $params[] = $endYear;
}

$sql .= " GROUP BY jt.name ORDER BY count DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format output
$labels = [];
$counts = [];

foreach ($results as $row) {
    $labels[] = $row['label'] ?? 'Unknown';
    $counts[] = (int)$row['count'];
}

echo json_encode(['labels' => $labels, 'counts' => $counts]);
?>