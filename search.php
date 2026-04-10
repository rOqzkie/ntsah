<?php
$servername = "localhost";
$username = "u363904469_ntfsah";
$password = "DB_ntfsah2025";
$dbname = "u363904469_db_ntfsah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$yearStart = isset($_GET['year_start']) ? (int)$_GET['year_start'] : 1910;
$yearEnd = isset($_GET['year_end']) ? (int)$_GET['year_end'] : 2025;
$openAccess = isset($_GET['open_access']) ? (int)$_GET['open_access'] : 0;
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'relevance';

$sql = "SELECT * FROM papers WHERE title LIKE ? AND year BETWEEN ? AND ?";
if ($openAccess) {
    $sql .= " AND open_access = 1";
}

if ($sortBy == 'year') {
    $sql .= " ORDER BY year DESC";
} else {
    $sql .= " ORDER BY relevance DESC";
}

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchQuery . "%";
$stmt->bind_param("sii", $searchTerm, $yearStart, $yearEnd);
$stmt->execute();
$result = $stmt->get_result();

$papers = [];
while ($row = $result->fetch_assoc()) {
    $papers[] = $row;
}
$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($papers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Search</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="script.js"></script>
</head>
<body>
    <div class="container">
        <aside class="filters">
            <h3>Show</h3>
            <label><input type="radio" name="openAccess" value="0" checked> All Results</label>
            <label><input type="radio" name="openAccess" value="1"> Open Access Only</label>
            <h3>Year</h3>
            <label><input type="radio" name="yearMode" value="range" checked> Range</label>
            <label><input type="radio" name="yearMode" value="single"> Single Year</label>
            <input type="number" id="yearStart" value="1910"> - <input type="number" id="yearEnd" value="2025">
            <button id="applyFilters">Apply</button>
        </aside>
        <main>
            <input type="text" id="searchBox" placeholder="Search...">
            <select id="sortBy">
                <option value="relevance">Relevance</option>
                <option value="year">Year</option>
            </select>
            <div id="results"></div>
        </main>
    </div>
</body>
</html>

<script>
document.getElementById("applyFilters").addEventListener("click", function() {
    const query = document.getElementById("searchBox").value;
    const yearStart = document.getElementById("yearStart").value;
    const yearEnd = document.getElementById("yearEnd").value;
    const openAccess = document.querySelector("input[name='openAccess']:checked").value;
    const sortBy = document.getElementById("sortBy").value;
    
    fetch(`search.php?q=${query}&year_start=${yearStart}&year_end=${yearEnd}&open_access=${openAccess}&sort_by=${sortBy}`)
        .then(response => response.json())
        .then(data => {
            const resultsDiv = document.getElementById("results");
            resultsDiv.innerHTML = "";
            data.forEach(paper => {
                const paperElement = document.createElement("div");
                paperElement.classList.add("paper");
                paperElement.innerHTML = `
                    <h2><a href="#">${paper.title}</a></h2>
                    <p>${paper.authors}</p>
                    <p><strong>${paper.year}</strong> | ${paper.publisher}</p>
                `;
                resultsDiv.appendChild(paperElement);
            });
        });
});
</script>

<style>
body {
    font-family: Arial, sans-serif;
}
.container {
    display: flex;
    padding: 20px;
}
.filters {
    width: 250px;
    background: #f8f8f8;
    padding: 15px;
}
main {
    flex-grow: 1;
    padding: 15px;
}
.paper {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}
</style>