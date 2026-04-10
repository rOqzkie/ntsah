<?php

include './config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $abstract = $_POST['abstract'] ?? '';
    $archive_code = $_POST['archive_code'] ?? '';

    if (empty($abstract)) {
        echo json_encode(["status" => "error", "message" => "No abstract provided"]);
        exit;
    }

    // Generate study gaps & future research suggestions using LLaMA
    $generatedText = generateGapUsingLlama($abstract);

    if ($generatedText) {
        // Step 1: Clean and format the generated text
        $cleanedText = strip_tags(html_entity_decode($generatedText));
        $cleanedText = trim(preg_replace('/\s+/', ' ', $cleanedText)); // Normalize whitespace

        // Step 2: Extract Identified Study Gaps and Future Research Suggestions separately
        $gaps = "No study gaps identified.";
        $suggestions = "No future research suggestions provided."; // Default messages

        // **Regex Patterns to Strictly Capture Each Section**
        $patternGaps = '/Identified Study Gaps:\s*(.*?)(?=\nFuture Research Suggestions:|\Z)/si';
        $patternSuggestions = '/Future Research Suggestions:\s*(.*?)(?=\Z)/si';

        if (preg_match($patternGaps, $cleanedText, $matches)) {
            $gaps = trim($matches[1]);

            // **Remove any lingering "Future Research Suggestions" from the Gaps section**
            $gaps = preg_replace('/Future Research Suggestions:.*/si', '', $gaps);
        }

        if (preg_match($patternSuggestions, $cleanedText, $matches)) {
            $suggestions = trim($matches[1]);
        }

        // Step 3: Convert extracted text into bullet points
        $gapsList = preg_split("/\n+/", trim($gaps));
        $suggestionsList = preg_split("/\n+/", trim($suggestions));

        $formattedGaps = array_map('trim', array_filter($gapsList));
        $formattedSuggestions = array_map('trim', array_filter($suggestionsList));

        $finalGaps = !empty($formattedGaps) ? "<ul><li>" . implode("</li><li>", $formattedGaps) . "</li></ul>" : "<p>No study gaps identified.</p>";
        $finalSuggestions = !empty($formattedSuggestions) ? "<ul><li>" . implode("</li><li>", $formattedSuggestions) . "</li></ul>" : "<p>No future research suggestions provided.</p>";

        // Step 4: Save structured output in the database
        $finalText = "<strong>Identified Study Gaps:</strong>" . $finalGaps . "<br><strong>Future Research Suggestions:</strong>" . $finalSuggestions;

        $stmt = $conn->prepare("UPDATE archive_list SET gaps = ? WHERE id = ?");
        $stmt->bind_param("si", $finalText, $archive_code);
        $stmt->execute();

        echo json_encode(["status" => "success", "gap" => $finalText]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to generate the study gap"]);
    }
}

// Function to generate study gaps using LLaMA 3.1
function generateGapUsingLlama($abstract) {
    $api_key = env('TOGETHER_API_KEY'); // Together API key
    $url = "https://api.together.xyz/v1/completions";

    $data = [
        "model" => "meta-llama/Llama-3.3-70B-Instruct-Turbo",
        "prompt" => "Analyze the following abstract and provide:\n1. Three research gaps (missing aspects, limitations, or unexplored areas)\n2. Future research suggestions to address these gaps.\n\nFormat:\nIdentified Study Gaps:\n- [List gaps]\n\nFuture Research Suggestions:\n- [List suggestions]\n\n" . $abstract,
        "max_tokens" => 1000,
        "temperature" => 1.2
    ];

    $headers = [
        "Authorization: Bearer " . $api_key,
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['choices'][0]['text'] ?? "No significant study gaps found. Consider revising the abstract.";
}

?>