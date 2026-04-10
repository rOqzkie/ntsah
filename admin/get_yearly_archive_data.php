<?php
ob_start();
ini_set('display_errors', 0);
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_type']) || !isset($_SESSION['department_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userType = $_SESSION['user_type'];
$departmentId = $_SESSION['department_id'];

$startYear = isset($_GET['startYear']) ? (int)$_GET['startYear'] : 2020;
$endYear = isset($_GET['endYear']) ? (int)$_GET['endYear'] : date('Y');

$years = range($startYear, $endYear);
$thesisCounts = [];
$fsCounts = [];

foreach ($years as $year) {
    $sql = "SELECT type, COUNT(*) AS count
            FROM archive_list
            WHERE status = 1 AND year = ?
            ";

    $params = [$year];
    $types = "i";

    if ($userType == 2) {
        $sql .= " AND department_id = ?";
        $params[] = $departmentId;
        $types .= "i";
    }

    $sql .= " GROUP BY type";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $thesis = 0;
    $fs = 0;

    while ($row = $result->fetch_assoc()) {
        if ($row['type'] == 1) $thesis = (int)$row['count'];
        if ($row['type'] == 2) $fs = (int)$row['count'];
    }

    $thesisCounts[] = $thesis;
    $fsCounts[] = $fs;
}

ob_clean();
echo json_encode([
    'years' => array_map('strval', $years),
    'thesisCounts' => $thesisCounts,
    'fsCounts' => $fsCounts
]);
?>