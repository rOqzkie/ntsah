<?php
require './config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=UTF-8');

// Validate input
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='text-danger'>Invalid archive ID.</p>";
    exit;
}

$id = (int)$_GET['id'];

// Fetch current archive
$archiveStmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
$archiveStmt->bind_param("i", $id);
$archiveStmt->execute();
$archiveResult = $archiveStmt->get_result();

if ($archiveResult->num_rows === 0) {
    echo "<p class='text-danger'>Archive not found.</p>";
    exit;
}

$archive = $archiveResult->fetch_assoc();
$discipline = $archive['discipline_id'];
$keywords = array_filter(array_map('trim', explode(',', strtolower($archive['keywords']))));

// Fetch archives with the same discipline
$stmt = $conn->prepare("SELECT * FROM archive_list WHERE id != ? AND discipline_id = ?");
$stmt->bind_param("is", $id, $discipline);
$stmt->execute();
$result = $stmt->get_result();

$similar = [];

while ($row = $result->fetch_assoc()) {
    $rowKeywords = array_filter(array_map('trim', explode(',', strtolower($row['keywords']))));
    $common = array_intersect($keywords, $rowKeywords);

    $basicScore = count($common) / max(count($keywords), 1);
    $llamaScore = getSimilarityFromLLaMA($conn, $id, $row['id'], $archive['abstract'], $row['abstract']);

    $finalScore = ($basicScore + $llamaScore) / 2;

    if ($finalScore > 0.1) {
        $similar[] = [
            'id' => $row['id'],
            'title' => htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'),
            'year' => htmlspecialchars($row['year'], ENT_QUOTES, 'UTF-8'),
            'score' => $finalScore,
            'similarity' => round($finalScore * 100, 2) . '%'
        ];
    }
}
// Sort by score ascending
usort($similar, function ($a, $b) {
    return $b['score'] <=> $a['score']; // descending
});
// Output
if (empty($similar)) {
    echo "<p class='text-muted'>No similar studies found.</p>";
} else {
    echo "<ul class='list-group'>";
    /*foreach ($similar as $s) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
            <a href='view_archive.php?id={$s['id']}' target='_blank' class='text-decoration-none text-dark'>
                <strong>{$s['title']}</strong> ({$s['year']})
            </a>
            <span class='badge bg-success'>{$s['similarity']}</span>
          </li>";
    }*/
    foreach ($similar as $s) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
            <span><strong>{$s['title']}</strong> ({$s['year']})</span>
            <span class='badge bg-success'>{$s['similarity']}</span>
          </li>";
    }
    echo "</ul>";
}

// LLaMA Similarity Function
function getSimilarityFromLLaMA($conn, $id1, $id2, $abstract1, $abstract2) {
    $archive1_id = min($id1, $id2);
    $archive2_id = max($id1, $id2);

    // Check cache
    $stmt = $conn->prepare("SELECT score FROM llama_similarity_cache WHERE archive1_id = ? AND archive2_id = ?");
    $stmt->bind_param("ii", $archive1_id, $archive2_id);
    $stmt->execute();
    $stmt->bind_result($cachedScore);

    if ($stmt->fetch()) {
        $stmt->close();
        return floatval($cachedScore);
    }
    $stmt->close();

    // Call LLaMA API
    $apiKey = env('LLAMA_API_KEY');
    $apiUrl = 'https://api.together.xyz/v1/chat/completions';

    $prompt = "Compare the semantic similarity between these abstracts:\n\nAbstract 1: \"$abstract1\"\n\nAbstract 2: \"$abstract2\"\n\nRespond with a similarity score between 0 and 1 only.";

    $data = [
        "model" => "meta-llama/Llama-3.3-70B-Instruct-Turbo",
        "messages" => [["role" => "user", "content" => $prompt]],
        "temperature" => 0.2
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
            'timeout' => 10
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($apiUrl, false, $context);

    if (!$response) return 0;

    $json = json_decode($response, true);
    $message = $json['choices'][0]['message']['content'] ?? "0";

    // Parse and clean score
    $score = floatval(trim(preg_replace('/[^0-9.]/', '', $message)));
    if ($score < 0 || $score > 1) $score = 0;

    // Cache result
    $insert = $conn->prepare("INSERT INTO llama_similarity_cache (archive1_id, archive2_id, score) VALUES (?, ?, ?)");
    $insert->bind_param("iid", $archive1_id, $archive2_id, $score);
    $insert->execute();

    return $score;
}
?>