<?php 
require_once('./config.php');
if (session_status() === PHP_SESSION_NONE) session_start();

// Restrict access to logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
    /* === Banner === */
    .banner-img-holder {
        height: 25vh !important;
        width: 100%;
        overflow: hidden;
    }
    .banner-img {
        object-fit: scale-down;
        height: 100%;
        width: 100%;
        transition: transform 0.3s ease-in;
    }

    /* === General === */
    .welcome-content img {
        margin: 0.5em;
    }
    .container,
    .row {
        width: 100%;
        max-width: none;
        margin: 0 auto;
        padding: 0 20px;
    }
    .container-fluid {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* === Climate Section === */
    .climate-collection {
        background: #116A75;
        color: white;
        text-align: center;
        padding: 20px;
    }
    .climate-collection button {
        background: #ffc107;
        border: none;
        padding: 10px 20px;
        color: white;
        cursor: pointer;
    }

    /* === Featured Authors Sections === */
    .featured-authors-thesis,
    .featured-authors-fs {
        text-align: left;
        padding: 30px;
    }

    .author-list {
        display: flex;
        justify-content: center;
        align-items: stretch;
        gap: 25px;
        flex-wrap: wrap;
    }

    .author-card {
        background: linear-gradient(135deg, #116A75, #116A75);
        border-radius: 15px;
        padding: 25px;
        width: 30%;
        min-height: 420px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 3px 6px 18px rgba(0, 0, 0, 0.3);
        color: white;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        position: relative;
    }

    .author-card:hover {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 5px 8px 22px rgba(0, 0, 0, 0.4);
        background: linear-gradient(135deg, #003366, #003366);
    }

    .author-image img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
    }

    .author-name {
        font-size: 16px;
        font-weight: bold;
        color: gold;
        margin-top: 12px;
        text-align: center;
        min-height: 45px;
    }

    .author-info-thesis,
    .author-info-fs {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        flex-grow: 1;
        width: 100%;
    }

    .thesis-title {
        font-size: 18px;
        font-weight: bold;
        color: white;
        text-align: center;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 8px;
        min-height: 55px;
        margin-top: 15px;
    }

    .rank-label {
        font-size: 14px;
        font-weight: bold;
        background: rgba(255, 215, 0, 0.8);
        color: black;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
        margin-top: auto;
    }

    .follow-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        width: 100px;
        color: white;
        transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
        text-align: center;
        margin-top: auto;
    }

    .follow-btn:hover {
        background: white;
        color: #004F9F;
    }

    /* === Chart === */
    .bgraph {
        max-width: 100%;
        width: 100%;
        height: 100%;
        margin: auto;
        overflow-x: auto;
        padding: 10px;
    }

    .chart-container {
        width: 100%;
        max-width: 100%;
    }

    canvas {
        max-width: 100%;
        height: auto !important;
    }

    /* === Card Layouts === */
    .card.card-outline {
        max-width: 90vw;
        width: 100%;
        margin: auto;
    }

    .card-body {
        position: relative;
        width: 100%;
        height: auto;
        min-height: 300px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .content.card {
        width: 100%;
        max-width: none;
    }

    /* === Links === */
    .btn.btn-link {
        color: white !important;
        text-decoration: none;
    }

    .btn.btn-link:hover {
        color: #f0f0f0 !important;
        text-decoration: underline;
    }

    /* === Responsive Adjustments === */
    @media (max-width: 768px) {
        .author-card {
            width: 100%;
            min-height: auto;
            padding: 15px;
        }
        .author-image img {
            width: 80px;
            height: 80px;
        }
        .author-name {
            font-size: 14px;
        }
        .thesis-title {
            font-size: 16px;
            padding: 8px;
        }
        .climate-collection {
            padding: 15px;
        }
        .climate-collection button {
            padding: 8px 16px;
            font-size: 14px;
        }
        .featured-authors-thesis,
        .featured-authors-fs {
            padding: 20px;
        }
        .author-list {
            flex-direction: column;
            align-items: center;
        }
        .follow-btn {
            width: 100%;
            font-size: 14px;
        }
        .card.card-outline {
            max-width: 95vw;
        }
        .banner-img-holder {
            height: 18vh;
        }
        .bgraph {
            padding: 5px;
        }
    }

    /* === Landscape Orientation === */
    @media (orientation: landscape) {
        .container {
            flex-direction: row;
        }
    }
</style>

<?php
$rank_labels = ["🥇 First", "🥈 Second", "🥉 Third"];
?>
<div class="row">
    <!--<div class="card card-outline card-maroon shadow rounded-0">-->
    <div class="card card-outline card-maroon col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card-body rounded-0">
        <!-- Your content -->
            <section class="climate-collection">
                <h2>Discover Groundbreaking Theses & Feasibility Studies – Start Exploring Now!</h2>
                    <p>Fuel Your Curiosity: Browse Theses & Feasibility Studies Today!...</p>
                        <a href="./?page=archives">
                            <button><b>Explore Archives</b> <i class="fas fa-file-archive"></i></button>
                        </a>
            </section>
            <div class="welcome-content">
                <section class="featured-authors-thesis">
                    <h2 style="color: #000000; font-family: 'Times New Roman', sans-serif;">Featured Thesis</h2>
                    <hr>
                    <div class="author-list">
                    <?php
                        $query = "SELECT *, (views + likes + downloads) AS total_engagement FROM archive_list WHERE status = 1 and type = 1 ORDER BY total_engagement DESC LIMIT 3";
                        $result = $conn->query($query);
                        $rank = 0;
                        while ($row = $result->fetch_assoc()) :
                            $uniqueId = "thesis_" . $row['id']; // Unique identifier
                        ?>
                        <div class="author-card">
                            <div class="author-info-thesis">
                            <h3 id="members_<?= $uniqueId ?>">
                            <?php 
                                $authors = explode(";", html_entity_decode($row['authors'])); 
                                $displayMembers = array_slice($authors, 0, 1); 
                                echo ucwords(implode("; ", $displayMembers)); 
                            ?>
                            <?php if (count($authors) > 2): ?>
                            <span id="dots_<?= $uniqueId ?>">...</span>
                                <span id="more_<?= $uniqueId ?>" style="display: none;">
                                    <?php echo "; " . ucwords(implode("; ", array_slice($authors, 1))); ?>
                                </span>
                                <button onclick="toggleMembers('<?= $uniqueId ?>')" class="btn btn-link">Show More</button>
                            <?php endif; ?>
                            </h3>
                            <p class="thesis-title"><?php echo ucwords(strtolower($row['title'])); ?></p>
                            <div class="rank-label"> 
                                <?php echo $rank_labels[$rank] . " - " . $row['views'] . " views & " . $row['likes'] . " likes & " . $row['downloads'] . " downloads"; ?>
                                <br>
                                <?php echo "(Total: " . $row['total_engagement'].")"; ?>
                            </div>
                            <hr>
                            <a href="./?page=view_archive&id=<?= $row['id'] ?>">
                                <button class="follow-btn">View</button>
                            </a>
                            </div>
                        </div>
                        <?php 
                            $rank++;
                            endwhile;
                        ?>
                    </div>
                </section>
                <section class="featured-authors-fs">
                    <h2 style="color: #000000; font-family: 'Times New Roman', sans-serif;">Featured Feasibility Study</h2>
                    <hr>
                    <div class="author-list">
                    <?php
                        $query = "SELECT *, (views + likes + downloads) AS total_engagement FROM archive_list WHERE status = 1 and type = 2 ORDER BY total_engagement DESC LIMIT 3";
                        $result = $conn->query($query);
                        $rank = 0;
                        while ($row = $result->fetch_assoc()) :
                            $uniqueId = "fs_" . $row['id']; // Unique identifier
                        ?>
                        <div class="author-card">
                            <div class="author-info-fs">
                            <h3 id="members_<?= $uniqueId ?>">
                            <?php 
                                $authors = explode(";", html_entity_decode($row['authors'])); 
                                $displayMembers = array_slice($authors, 0, 1); 
                                echo ucwords(implode("; ", $displayMembers)); 
                            ?>
                            <?php if (count($authors) > 2): ?>
                                <span id="dots_<?= $uniqueId ?>">...</span>
                                <span id="more_<?= $uniqueId ?>" style="display: none;">
                                    <?php echo "; " . ucwords(implode("; ", array_slice($authors, 1))); ?>
                                </span>
                                <button onclick="toggleMembers('<?= $uniqueId ?>')" class="btn btn-link">Show More</button>
                            <?php endif; ?>
                            </h3>
                            <p class="thesis-title"><?php echo ucwords(strtolower($row['title'])); ?></p>
                            <div class="rank-label"> 
                                <?php echo $rank_labels[$rank] . " - " . $row['views'] . " views & " . $row['likes'] . " likes & " . $row['downloads'] . " downloads"; ?>
                                <br>
                                <?php echo "(Total: " . $row['total_engagement'].")"; ?>
                            </div>
                            <hr>
                            <a href="./?page=view_archive&id=<?= $row['id'] ?>">
                                <button class="follow-btn">View</button>
                            </a>
                            </div>
                        </div>
                        <?php 
                            $rank++;
                            endwhile; 
                        ?>
                    </div>
                </section>
            </div>
            
            <div class="bgraph container-fluid">
                <!--
                <div class="container">
                    <div class="bg-light shadow rounded-4 p-4 mb-4">
                        <h5 class="text-center fw-bold mb-4">
                            TOTAL NO. OF ARCHIVED THESIS AND FEASIBILITY STUDY
                        </h5>

                        
                        <div id="loadingSpinner" class="text-center my-3 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                       
                        <form class="row gy-2 gx-3 justify-content-center">
                            <div class="col-12 col-sm-6 col-md-4">
                                <select id="category" class="form-select">
                                    <option value="college">By College</option>
                                    <option value="department">By Department</option>
                                    <option value="program">By Program</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <input type="number" id="startYear" class="form-control" placeholder="Start Year" min="1900">
                            </div>

                            <div class="col-6 col-md-2">
                                <input type="number" id="endYear" class="form-control" placeholder="End Year" min="1900">
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="button" class="btn btn-primary w-100" onclick="updateChart()">
                                    <i class="fa fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </form>

                        
                        <div class="pt-4">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="archiveChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                <div class="container"> 
                    <div class="bg-light shadow rounded-4 p-4 mb-4">
                        <h5 class="text-center fw-bold mb-4">
                            TOTAL NO. OF ARCHIVED THESIS AND FEASIBILITY STUDY
                        </h5>

                        <!-- Spinner -->
                        <div id="loadingSpinner" class="text-center my-3 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <!-- Filter Form -->
                        <form class="row gy-2 gx-3 justify-content-center">
                            <div class="col-12 col-sm-6 col-md-4">
                                <select id="category" class="form-select">
                                    <option value="college">By College</option>
                                    <option value="department">By Department</option>
                                    <option value="program">By Program</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <select id="discipline" class="form-select">
                                    <option value="">All Disciplines</option>
                                    <?php

                                        $query = "SELECT name FROM discipline_list ORDER BY name ASC";
                                        $result = mysqli_query($conn, $query);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $name = htmlspecialchars($row['name']);
                                                echo "<option value=\"$name\">$name</option>";
                                            }
                                        } else {
                                            echo "<option disabled>No disciplines found</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <input type="number" id="startYear" class="form-control" placeholder="Start Year" min="1900">
                            </div>

                            <div class="col-6 col-md-2">
                                <input type="number" id="endYear" class="form-control" placeholder="End Year" min="1900">
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="button" class="btn btn-primary w-100" onclick="updateChart()">
                                    <i class="fa fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </form>

                        <!-- Chart Canvas -->
                        <div class="pt-4">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="archiveChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info-box bg-light shadow mb-4">
                    <div class="info-box-content p-3">
                        <h5 class="text-center font-weight-bold mb-3">Top 5 Archive Usage Metrics</h5>
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                        <!-- Toggle Buttons -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                            <button class="btn btn-primary flex-fill text-nowrap" onclick="loadChart('views')">Most Viewed</button>
                            <button class="btn btn-secondary flex-fill text-nowrap" onclick="loadChart('downloads')">Most Downloaded</button>
                        </div>
                        <!-- Chart Container -->
                        <div class="pt-4">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="metricsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/*
let archiveChart;

document.addEventListener("DOMContentLoaded", () => {
  updateChart();
});

function showSpinner(show) {
  const spinner = document.getElementById('loadingSpinner');
  spinner.classList.toggle('d-none', !show);
}

function fetchAndRenderChart(category = "college", startYear = "", endYear = "") {
  showSpinner(true);

  fetch(`./get_archive_data.php?category=${category}&startYear=${startYear}&endYear=${endYear}`)
    .then(res => res.json())
    .then(data => {
      const ctx = document.getElementById('archiveChart').getContext('2d');
      const counts = data.counts.map(count => Number(count) || 0);
      const colors = [
        'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'
      ];

      if (archiveChart) archiveChart.destroy();

      archiveChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Submitted Archives',
            data: counts,
            backgroundColor: colors.slice(0, data.labels.length),
            borderColor: colors.slice(0, data.labels.length).map(c => c.replace('0.7', '1')),
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: context => `${context.dataset.label}: ${context.parsed.y}`
              }
            },
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          }
        }
      });
    })
    .catch(err => {
      console.error('Error loading chart data:', err);
      alert('Failed to load chart data. Please try again.');
    })
    .finally(() => {
      showSpinner(false);
    });
}

function updateChart() {
  const category = document.getElementById('category').value;
  const startYear = document.getElementById('startYear').value;
  const endYear = document.getElementById('endYear').value;
  fetchAndRenderChart(category, startYear, endYear);
}
*/
let archiveChart;

document.addEventListener("DOMContentLoaded", () => {
  updateChart();
});

function showSpinner(show) {
  const spinner = document.getElementById('loadingSpinner');
  spinner.classList.toggle('d-none', !show);
}

function fetchAndRenderChart(category = "college", startYear = "", endYear = "", discipline = "") {
  showSpinner(true);

  const url = `./get_archive_data.php?category=${encodeURIComponent(category)}&startYear=${encodeURIComponent(startYear)}&endYear=${encodeURIComponent(endYear)}&discipline=${encodeURIComponent(discipline)}`;

  fetch(url)
    .then(res => res.json())
    .then(data => {
      const ctx = document.getElementById('archiveChart').getContext('2d');
      const counts = data.counts.map(count => Number(count) || 0);
      const colors = [
        'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
        'rgba(199, 199, 199, 0.7)', 'rgba(83, 102, 255, 0.7)',
        'rgba(255, 102, 153, 0.7)', 'rgba(0, 204, 204, 0.7)',
        'rgba(255, 204, 102, 0.7)'
      ];

      if (archiveChart) archiveChart.destroy();

      archiveChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Submitted Archives',
            data: counts,
            backgroundColor: colors.slice(0, data.labels.length),
            borderColor: colors.slice(0, data.labels.length).map(c => c.replace('0.7', '1')),
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: context => `${context.dataset.label}: ${context.parsed.y}`
              }
            },
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          }
        }
      });
    })
    .catch(err => {
      console.error('Error loading chart data:', err);
      alert('Failed to load chart data. Please try again.');
    })
    .finally(() => {
      showSpinner(false);
    });
}

function updateChart() {
  const category = document.getElementById('category').value;
  const startYear = document.getElementById('startYear').value;
  const endYear = document.getElementById('endYear').value;
  const discipline = document.getElementById('discipline')?.value || '';
  fetchAndRenderChart(category, startYear, endYear, discipline);
}
function toggleMembers(uniqueId) {
    var dots = document.getElementById("dots_" + uniqueId);
    var moreText = document.getElementById("more_" + uniqueId);
    var btnText = dots.nextElementSibling.nextElementSibling; // The button

    if (moreText.style.display === "none") {
        moreText.style.display = "inline";
        dots.style.display = "none";
        btnText.innerText = "Show Less";
    } else {
        moreText.style.display = "none";
        dots.style.display = "inline";
        btnText.innerText = "Show More";
    }
}

let metricsChart = null; // Holds Chart instance

document.addEventListener("DOMContentLoaded", () => {
    loadChart('views'); // Load "Most Viewed" by default on page load
});

async function loadChart(orderBy) {
    // Show loading spinner
    document.getElementById("loadingSpinner").style.display = "block";

    try {
        const response = await fetch('metrics.php?order=' + orderBy);
        const data = await response.json();

        const labels = data.map(item => item.title);
        const dataset = data.map(item => orderBy === 'views' ? item.views : item.downloads);
        const labelName = orderBy === 'views' ? "Views" : "Downloads";
        const backgroundColor = orderBy === 'views' ? "rgba(75, 192, 192, 0.6)" : "rgba(255, 99, 132, 0.6)";

        // Destroy existing chart if any
        if (metricsChart !== null) {
            metricsChart.destroy();
        }

        const ctx = document.getElementById("metricsChart").getContext("2d");
        metricsChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: labelName,
                    data: dataset,
                    backgroundColor: backgroundColor
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: (context) => `${context.parsed.x} ${labelName}`
                        }
                    },
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: `Top 5 ${labelName} Archives`
                    }
                }
            }
        });

    } catch (error) {
        console.error("Error loading chart data:", error);
    } finally {
        // Hide loading spinner
        document.getElementById("loadingSpinner").style.display = "none";
    }
}
</script>