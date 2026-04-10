<?php
ini_set('display_errors', 0);

include './config.php';

$allowedOrders = ['views', 'downloads'];
$orderBy = isset($_GET['order']) && in_array($_GET['order'], $allowedOrders) ? $_GET['order'] : 'views';

$sql = "SELECT archive_code, title, views, downloads FROM archive_list ORDER BY $orderBy DESC LIMIT 5";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    if (strlen($row['title']) > 30) {
        $row['title'] = mb_substr($row['title'], 0, 20) . '...';
    }
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
exit;
?>