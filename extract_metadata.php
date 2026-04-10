<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();

require_once 'vendor/autoload.php';
use Smalot\PdfParser\Parser;

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function fallbackExtract($text) {
    preg_match('/^(.{5,120}?)\n/i', $text, $title);

    // Extract abstract up to a proper Keywords heading on the next line
    preg_match('/\bAbstract\b[:\-]?\s*(.*?)\n(?=\s*(Keywords?|Key Words|Index Terms)\s*[:\-])/is', $text, $abstractMatch);
    $abstract = trim($abstractMatch[1] ?? '');

    // Keywords
    preg_match('/\bKeywords?\b\s*[:\-]?\s*(.*?)\n/i', $text, $keywords);

    // Authors
    preg_match('/\b(By:|Authors?:)\s*(.*?)\n/i', $text, $authors);

    // Year
    preg_match_all('/\b(19|20)\d{2}\b/', $text, $years);
    $year = end($years[0]) ?? '';

    return [
        'title' => trim($title[1] ?? ''),
        'abstract' => trim($abstract),
        'keywords' => array_filter(array_map('trim', explode(',', $keywords[1] ?? ''))),
        'authors' => array_filter(array_map('trim', preg_split('/,| and /', $authors[2] ?? ''))),
        'year' => trim($year)
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    if ($_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'File upload error.']);
        exit;
    }

    $pdfPath = $_FILES['pdf']['tmp_name'];

    try {
        $parser = new Parser();
        $pdfText = $parser->parseFile($pdfPath)->getText();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'PDF parsing failed: ' . $e->getMessage()]);
        exit;
    }

    //$pdfText = mb_substr(trim($pdfText), 0, 10000);
    $pdfText = mb_convert_encoding($pdfText, 'UTF-8', 'UTF-8');
    $pdfText = preg_replace('/[[:^print:]]/', '', $pdfText);

    $prompt = <<<PROMPT
Extract the following academic metadata from the text below. Format the response as a JSON object with keys:
- "title": string
- "abstract": string (include all lines of the abstract excluding the line when keywords appears)
- "keywords": array of strings
- "authors": array of strings
- "year": 4-digit year

Text:
$pdfText
PROMPT;

    $postData = [
        "model" => "meta-llama/Llama-3.3-70B-Instruct-Turbo",
        "messages" => [["role" => "user", "content" => $prompt]],
        "temperature" => 0.2,
        "max_tokens" => 2024
    ];

    $ch = curl_init('https://api.together.xyz/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . env('TOGETHER_API_KEY')
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData)
    ]);

    $response = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Handle failed request
    if ($response === false || $httpStatus !== 200) {
        $fallback = fallbackExtract($pdfText);
        http_response_code(207);
        echo json_encode([
            'error' => $response === false ? 'cURL error: ' . $curlError : 'LLM request failed',
            'title' => $fallback['title'],
            'abstract' => $fallback['abstract'],
            'keywords' => $fallback['keywords'],
            'authors' => $fallback['authors'],
            'year' => $fallback['year']
        ]);
        exit;
    }

    // Parse LLaMA response
    $data = json_decode($response, true);
    $llmContent = $data['choices'][0]['message']['content'] ?? '';

    // Extract JSON block
    preg_match('/\{.*\}/s', $llmContent, $jsonMatch);
    $jsonString = $jsonMatch[0] ?? '';

    $metadata = json_decode($jsonString, true);

    // If invalid JSON, fallback
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($metadata)) {
        $fallback = fallbackExtract($pdfText);
        http_response_code(207);
        echo json_encode([
            'error' => 'Invalid JSON from LLaMA. Using fallback extraction.',
            'title' => $fallback['title'],
            'abstract' => $fallback['abstract'],
            'keywords' => $fallback['keywords'],
            'authors' => $fallback['authors'],
            'year' => $fallback['year']
        ]);
        exit;
    }

    // Normalize keys
    foreach (['keywords', 'authors'] as $arrKey) {
        if (isset($metadata[$arrKey]) && is_string($metadata[$arrKey])) {
            $metadata[$arrKey] = array_filter(array_map('trim', explode(',', $metadata[$arrKey])));
        }
    }

    // Final output
    echo json_encode([
        'title' => $metadata['title'] ?? '',
        'abstract' => $metadata['abstract'] ?? '',
        'keywords' => $metadata['keywords'] ?? [],
        'authors' => $metadata['authors'] ?? [],
        'year' => $metadata['year'] ?? ''
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No valid PDF uploaded']);
}
?>