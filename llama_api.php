<?php

// Load environment variables
if (!function_exists('env')) {
    require_once(__DIR__ . '/classes/EnvLoader.php');
}

function getSimilarityFromLLaMA($conn, $id1, $id2, $abstract1, $abstract2) {
    // Ensure archive1_id < archive2_id to keep cache unique
    $archive1_id = min($id1, $id2);
    $archive2_id = max($id1, $id2);

    // Check cache first
    $stmt = $conn->prepare("SELECT score FROM llama_similarity_cache WHERE archive1_id = ? AND archive2_id = ?");
    $stmt->bind_param("ii", $archive1_id, $archive2_id);
    $stmt->execute();
    $stmt->bind_result($cachedScore);
    if ($stmt->fetch()) {
        return floatval($cachedScore);
    }
    $stmt->close();

    // Call LLaMA API
    $apiUrl = 'https://api.together.xyz/v1/chat/completions';
    $apiKey = env('LLAMA_API_KEY');

    $prompt = "Compare the semantic similarity between these abstracts:\n\nAbstract 1: \"$abstract1\"\n\nAbstract 2: \"$abstract2\"\n\nRespond with a similarity score between 0 and 1 only.";

    $data = [
        "model" => "meta-llama/Llama-3.3-70B-Instruct-Turbo",
        "messages" => [["role" => "user", "content" => $prompt]],
        "temperature" => 0.2
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);

    if ($result === FALSE) return 0;

    $json = json_decode($result, true);
    $response = $json['choices'][0]['message']['content'] ?? "0";
    $score = floatval(trim($response));

    // Save to cache
    $insert = $conn->prepare("INSERT INTO llama_similarity_cache (archive1_id, archive2_id, score) VALUES (?, ?, ?)");
    $insert->bind_param("iid", $archive1_id, $archive2_id, $score);
    $insert->execute();

    return $score;
}

?>