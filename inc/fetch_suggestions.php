<?php

header('Content-Type: application/json');
require '../config.php'; // Ensure database connection

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!$query || strlen($query) < 2) {
    echo json_encode([]); // Return empty if no valid query
    exit;
}

// 🟢 OPTION 1: Fetch Suggestions from MySQL Database
$suggestions = [];
$stmt = $conn->prepare("SELECT DISTINCT keyword FROM search_history WHERE keyword LIKE ? ORDER BY frequency DESC LIMIT 5");
$searchTerm = "%$query%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['keyword'];
}
$stmt->close();

// 🟢 OPTION 2: Fetch AI-Generated Suggestions from LLaMA API
if (count($suggestions) < 5) { // Fetch AI suggestions if DB results are few
    $llama_api_key = env('LLAMA_API_KEY');
    $llama_url = "https://api.together.ai/models/meta-llama/Llama-3.3-70B-Instruct-Turbo-Free"; // Replace with actual LLaMA API endpoint

    $payload = json_encode([
        "model" => "meta-llama/Llama-3.3-70B-Instruct-Turbo-Free",
        "prompt" => "Suggest 5 search queries related to: '$query'",
        "max_tokens" => 50,
        "temperature" => 0.5,
    ]);

    $ch = curl_init($llama_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $llama_api_key"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $llama_response = json_decode($response, true);
    
    if (isset($llama_response['choices'][0]['text'])) {
        $ai_suggestions = explode("\n", trim($llama_response['choices'][0]['text']));
        $suggestions = array_merge($suggestions, array_filter($ai_suggestions));
    }
}

// Return suggestions as JSON
echo json_encode(array_slice($suggestions, 0, 10));
?>