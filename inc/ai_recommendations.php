<?php

header('Content-Type: application/json');

// OpenAI API Configuration
$openai_api_key = getenv('OPENAI_API_KEY') ?: '';
$api_url = 'https://api.openai.com/v1/completions';

// Database connection settings
$host = 'localhost'; // Update to your database host
$dbname = 'db_ntfsah'; // Update to your database name
$user = 'root'; // Update to your database username
$password = ''; // Update to your database password

// Connect to the database using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the query from the AJAX request
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Function to fetch projects from the database
function fetchProjects($pdo) {
    $stmt = $pdo->prepare("SELECT title, description FROM projects");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the projects as an associative array
}

// Fetch all projects from the database
$projects = fetchProjects($pdo);

// Function to fetch recommendations from OpenAI API
function getAIRecommendations($query, $projects, $api_url, $api_key) {
    // Prepare the list of project titles to send to OpenAI
    $project_titles = array_column($projects, 'title');
    $prompt = 'Based on the query "' . $query . '", and the following project titles: ' . implode(", ", $project_titles) . ', suggest 3 relevant projects.';

    // Prepare the data for the OpenAI API
    $data = [
        'model' => 'text-davinci-003', // Specify the model
        'prompt' => 'Based on the query "' . $query . '", suggest 3 relevant project ideas.',
        'temperature' => 0.7,  // Controls creativity
        'max_tokens' => 100
    ];

    // Initialize cURL session
    $ch = curl_init($api_url);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute cURL request and fetch response
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    // Parse and return the response
    return json_decode($response, true);
}

// Fetch recommendations from OpenAI
$recommendations_response = getAIRecommendations($query, $api_url, $openai_api_key);

// Extract the text output from the API response
$recommendations_text = isset($recommendations_response['choices'][0]['text']) ? $recommendations_response['choices'][0]['text'] : '';

// Convert the response text to an array (assuming each recommendation is separated by new lines)
$recommendations = explode("\n", trim($recommendations_text));

// Return recommendations as JSON
echo json_encode($recommendations);
?>