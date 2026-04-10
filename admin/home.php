<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<style>
body.dark-mode {
    background-color: #121212 !important;
    color: #ffffff !important;
}

body.dark-mode .card {
    background-color: #1e1e1e;
    color: #ffffff;
    border-color: #333;
}

body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: #333;
    color: #fff;
    border-color: #555;
}

body.dark-mode .form-control::placeholder {
    color: #aaa;
}

body.dark-mode canvas {
    background-color: #1e1e1e;
}
</style>

<div class="container-fluid px-3 px-md-5 py-3">

  <!-- Dark Mode Toggle -->
  <div class="d-flex justify-content-end mb-4">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="darkModeToggle">
      <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
    </div>
  </div>

  <h1 class="text-center fw-bold mb-3">Welcome to <?= $_settings->info('name') ?></h1>
  <hr class="border-info mb-4">

  <div class="row g-4">

    <?php if($_settings->userdata('type') == 1): ?>

      <?php
      $dashboard_items = [
          ['label' => 'College(s)', 'icon' => 'building', 'bg' => 'secondary', 'link' => 'college', 'query' => "SELECT * FROM `college_list` where status = 1"],
          ['label' => 'Department(s)', 'icon' => 'th-list', 'bg' => 'info', 'link' => 'departments', 'query' => "SELECT * FROM `department_list` where status = 1"],
          ['label' => 'Program(s)', 'icon' => 'scroll', 'bg' => 'dark', 'link' => 'program', 'query' => "SELECT * FROM `curriculum_list` where `status` = 1"],
          ['label' => 'Department Chair(s)', 'icon' => 'users-cog', 'bg' => 'primary', 'link' => 'user/list', 'query' => "SELECT * FROM `users` where `status` = 1"],
          ['label' => 'Verified Student(s)', 'icon' => 'users', 'bg' => 'primary', 'link' => 'students', 'query' => "SELECT * FROM `student_list` where `status` = 1"],
          ['label' => 'Verified Adviser(s)', 'icon' => 'users', 'bg' => 'success', 'link' => 'adviser', 'query' => "SELECT * FROM `adviser_list` where `status` = 1"],
          ['label' => 'Verified Archive(s)', 'icon' => 'archive', 'bg' => 'success', 'link' => 'archives', 'query' => "SELECT * FROM `archive_list` where `status` = 1"],
          ['label' => 'Unverified Archive(s)', 'icon' => 'archive', 'bg' => 'dark', 'link' => 'archives', 'query' => "SELECT * FROM `archive_list` where `status` = 0"]
      ];

      foreach ($dashboard_items as $item):
      ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 border-0 shadow-sm position-relative">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="bg-<?= $item['bg'] ?> text-white p-3 rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
              <i class="fas fa-<?= $item['icon'] ?> fs-5"></i>
            </div>
            <div>
              <div class="fw-semibold"><?= $item['label'] ?></div>
              <div class="text-muted small"><?= $conn->query($item['query'])->num_rows ?> record(s)</div>
              <a href="<?= base_url ?>admin/?page=<?= $item['link'] ?>" class="stretched-link"></a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Chart Section -->
      <div class="col-12">
        <div class="card border-0 shadow-sm mt-4">
          <div class="card-body">
            <h5 class="text-center fw-bold mb-4">TOTAL NO. OF ARCHIVED THESIS AND FEASIBILITY STUDY</h5>
            <div class="row g-3 align-items-end mb-3">
              <div class="col-md-4">
                <label for="category" class="form-label">Filter By</label>
                <select id="category" class="form-select">
                  <option value="college">College</option>
                  <option value="department">Department</option>
                  <option value="program">Program</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="startYear" class="form-label">Start Year</label>
                <input type="number" id="startYear" class="form-control" placeholder="Start Year">
              </div>
              <div class="col-md-3">
                <label for="endYear" class="form-label">End Year</label>
                <input type="number" id="endYear" class="form-control" placeholder="End Year">
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="updateChart()">
                  <i class="fa fa-filter me-1"></i> Filter
                </button>
              </div>
            </div>
            <div style="overflow-x:auto;">
              <canvas id="archiveChart" class="w-100" style="height:600px;"></canvas>
            </div>
          </div>
        </div>
      </div>

    <?php endif; ?>

    <!-- Department Chair Dashboard -->
    <?php if($_settings->userdata('type') == 2): ?>
      <?php
      $department_id = $_SESSION['department_id'];
      $type2_items = [
        ['label' => 'Verified Archive(s)', 'icon' => 'archive', 'bg' => 'success', 'query' => "SELECT * FROM `archive_list` WHERE department_id = '$department_id' AND `status` = 1"],
        ['label' => 'Pending Archive(s)', 'icon' => 'archive', 'bg' => 'dark', 'query' => "SELECT * FROM `archive_list` WHERE department_id = '$department_id' AND `status` = 0"],
        ['label' => 'Verified Adviser(s)', 'icon' => 'users', 'bg' => 'primary', 'query' => "SELECT * FROM `adviser_list` WHERE department_id = '$department_id' AND `status` = 1"],
        ['label' => 'Pending Adviser(s)', 'icon' => 'users', 'bg' => 'warning', 'query' => "SELECT * FROM `adviser_list` WHERE department_id = '$department_id' AND `status` = 0"]
      ];

      foreach ($type2_items as $item):
      ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 border-0 shadow-sm position-relative">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="bg-<?= $item['bg'] ?> text-white p-3 rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
              <i class="fas fa-<?= $item['icon'] ?> fs-5"></i>
            </div>
            <div>
              <div class="fw-semibold"><?= $item['label'] ?></div>
              <div class="text-muted small"><?= $conn->query($item['query'])->num_rows ?> record(s)</div>
              <a href="<?= base_url ?>admin/?page=archives" class="stretched-link"></a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Chart (same layout) -->
      <div class="col-12">
        <div class="card border-0 shadow-sm mt-4">
          <div class="card-body">
            <h5 class="text-center fw-bold mb-4">TOTAL NO. OF ARCHIVED THESIS AND FEASIBILITY STUDY</h5>
            <div class="row g-3 align-items-end mb-3">
              <div class="col-md-4">
                <label for="category" class="form-label">Filter By</label>
                <select id="category" class="form-select">
                  <option value="college">College</option>
                  <option value="department">Department</option>
                  <option value="program">Program</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="startYear" class="form-label">Start Year</label>
                <input type="number" id="startYear" class="form-control" placeholder="Start Year">
              </div>
              <div class="col-md-3">
                <label for="endYear" class="form-label">End Year</label>
                <input type="number" id="endYear" class="form-control" placeholder="End Year">
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="updateChart()">
                  <i class="fa fa-filter me-1"></i> Filter
                </button>
              </div>
            </div>
            <div style="overflow-x:auto;">
              <canvas id="archiveChart" class="w-100" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>

    <?php endif; ?>

  </div>
  <?php
        // Session already started in config.php
        $usertype = $_SESSION['user_type'] ?? null;
        $department_id = $_SESSION['department_id'] ?? null; // Assuming department ID is stored in session

        // Default query (for Admin)
        $query = "SELECT title, views FROM archive_list WHERE status = 1 ORDER BY views DESC LIMIT 10";

        // Modify query if user is a Department Chair
        if ($usertype == 2 && $department_id) {
            $query = "SELECT title, views FROM archive_list WHERE status = 1 AND department_id = ? ORDER BY views DESC LIMIT 10";
        }

        // Prepare statement if department filtering is needed
        if ($usertype == 2) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $department_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($query);
        }

        $rank = 1;
        $prev_views = null;
    ?>
<div class="col-12">
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white text-center">
      <h6 class="mb-0"><strong>Top 10 Most Viewed Archive</strong></h6>
    </div>
    <div class="card-body p-2">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle mb-0">
          <thead class="table-light text-center">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Title of Study</th>
              <th style="width: 150px;">No. of Views</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rank = 1;
            $prev_views = null;
            while ($row = $result->fetch_assoc()) :
              if ($prev_views !== null && $row['views'] != $prev_views) {
                $rank++;
              }
              $prev_views = $row['views'];
              if ($rank > 10) break;
            ?>
              <tr>
                <td class="text-center fw-bold"><?php echo $rank; ?></td>
                <td title="<?php echo htmlspecialchars($row['title']); ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </td>
                <td class="text-center"><?php echo number_format($row['views']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let archiveChart;

function updateChart() {
  const category = document.getElementById('category').value;
  const startYear = parseInt(document.getElementById('startYear').value) || 2020;
  const endYear = parseInt(document.getElementById('endYear').value) || new Date().getFullYear();

  fetch(`./get_archive_data.php?category=${category}&startYear=${startYear}&endYear=${endYear}`)
    .then(response => response.json())
    .then(data => {
      if (!data.years || !data.thesisCounts || !data.fsCounts) {
        console.error('Invalid data format from PHP:', data);
        return;
      }

      const ctx = document.getElementById('archiveChart').getContext('2d');

      const chartData = {
        labels: data.years,
        datasets: [
          {
            label: 'Thesis',
            backgroundColor: '#007bff',
            borderColor: '#007bff',
            data: data.thesisCounts,
            borderWidth: 1
          },
          {
            label: 'Feasibility Study',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            data: data.fsCounts,
            borderWidth: 1
          }
        ]
      };

      const chartOptions = {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
            }
          },
          title: {
            display: false
          }
        },
        scales: {
          x: {
            ticks: {
              color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
            }
          }
        }
      };

      if (archiveChart) archiveChart.destroy();
      archiveChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: chartOptions
      });
    })
    .catch(error => console.error('Error fetching archive data:', error));
}

document.addEventListener('DOMContentLoaded', updateChart);

document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('darkModeToggle');
    const isDarkMode = localStorage.getItem('darkMode') === 'true';

    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        toggle.checked = true;
    }

    toggle.addEventListener('change', function () {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });
});
</script>