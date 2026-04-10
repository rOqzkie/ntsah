<?php
ob_start();
ini_set('display_errors', 0);
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_type']) || !isset($_SESSION['department_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$userType = $_SESSION['user_type'];
$departmentId = $_SESSION['department_id'];

$category = $_GET['category'] ?? 'college';
$startYear = isset($_GET['startYear']) ? (int)$_GET['startYear'] : null;
$endYear = isset($_GET['endYear']) ? (int)$_GET['endYear'] : null;

// Build WHERE clause
$whereClause = "WHERE a.status = 1";
$params = [];

// Apply year filters
if ($startYear) {
    $whereClause .= " AND a.year >= ?";
    $params[] = $startYear;
}
if ($endYear) {
    $whereClause .= " AND a.year <= ?";
    $params[] = $endYear;
}
if ($userType == 2) {
    $whereClause .= " AND a.department_id = ?";
    $params[] = $departmentId;
}

// Additional filter by category
switch ($category) {
    case 'college':
        $whereClause .= " AND a.college_id IS NOT NULL";
        break;
    case 'department':
        $whereClause .= " AND a.department_id IS NOT NULL";
        break;
    case 'program':
        $whereClause .= " AND a.curriculum_id IS NOT NULL";
        break;
    default:
        echo json_encode(['error' => 'Invalid category']);
        exit;
}

// Query: Count per year and type
$sql = "SELECT a.year, a.type, COUNT(*) as total
        FROM archive_list a
        $whereClause
        GROUP BY a.year, a.type
        ORDER BY a.year ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->get_result();

// Organize results
$thesisCounts = [];
$fsCounts = [];
$yearSet = [];

while ($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $type = $row['type'];
    $count = $row['total'];

    $yearSet[$year] = true;

    if ($type == 1) {
        $thesisCounts[$year] = $count;
    } elseif ($type == 2) {
        $fsCounts[$year] = $count;
    }
}

// Ensure all years are present with 0 if missing
$years = range($startYear ?? 2020, $endYear ?? date('Y'));
$finalThesis = [];
$finalFS = [];

foreach ($years as $year) {
    $finalThesis[] = $thesisCounts[$year] ?? 0;
    $finalFS[] = $fsCounts[$year] ?? 0;
}

ob_clean();
echo json_encode([
    'years' => array_map('strval', $years),
    'thesisCounts' => $finalThesis,
    'fsCounts' => $finalFS
]);
?>