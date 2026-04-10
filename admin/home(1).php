<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr class="border-info">
<div class="row">
    <?php if($_settings->userdata('type') == 1): ?>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                    <a href="<?php echo base_url ?>admin/?page=college" class="nav-link nav-college">
                    <span class="info-box-text"><b>College(s)</b></span>
                        <span class="info-box-number text-right">
                        <?php 
                            echo $conn->query("SELECT * FROM `college_list` where status = 1")->num_rows;
                        ?>
                        </span>
                    </a>
                </div>
                <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=departments" class="nav-link nav-departments">
            <span class="info-box-text"><b>Department(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `department_list` where status = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-gradient-dark elevation-1"><i class="fas fa-scroll"></i></span>
            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=program" class="nav-link nav-program">
            <span class="info-box-text"><b>Program(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `curriculum_list` where `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-users-cog"></i></span>
            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
            <span class="info-box-text"><b>Dept. Chair(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `users` where `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=students" class="nav-link nav-students">
            <span class="info-box-text"><b>Verified Student(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `student_list` where `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=adviser" class="nav-link nav-adviser">
            <span class="info-box-text"><b>Verified Adviser(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `adviser_list` where `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-archive"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=archives" class="nav-link nav-archives">
            <span class="info-box-text"><b>Verified Archive(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `archive_list` where `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-archive"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=archives" class="nav-link nav-archives">
            <span class="info-box-text"><b>Unverified Archive(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `archive_list` where `status` = 0")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="info-box bg-light shadow">
        <div class="info-box-content">
            <span class="info-box-text"><center><b>TOTAL NO. OF ARCHIVED THESIS AND FEASIBILITY STUDY</b></center></span>
            
            <!-- Filters -->
            <div class="row p-3">
                <div class="col-md-4">
                    <select id="category" class="form-control">
                        <option value="college">By College</option>
                        <option value="department">By Department</option>
                        <option value="program">By Program</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" id="startYear" class="form-control" placeholder="Start Year">
                </div>
                <div class="col-md-3">
                    <input type="number" id="endYear" class="form-control" placeholder="End Year">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="updateChart()"><i class="fa fa-filter" style="font-size:20px;color:white;"></i> Filter</button>
                </div>
            </div>

            <div class="card-body">
                <canvas id="archiveChart"></canvas>
            </div>
        </div>
    </div>
    </div>
    <?php endif; ?>
    <?php if($_settings->userdata('type') == 2): ?>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-archive"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=archives" class="nav-link nav-archives">
            <span class="info-box-text"><b>Verified Archive(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    $user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
                    $department_id = $_SESSION['department_id']; // Assuming department_id is stored in session
                    echo $conn->query("SELECT * FROM `archive_list` where department_id = '$department_id' and `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-archive"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=archives" class="nav-link nav-archives">
            <span class="info-box-text"><b>For Verification Approval Archive(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    $user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
                    $department_id = $_SESSION['department_id']; // Assuming department_id is stored in session
                    echo $conn->query("SELECT * FROM `archive_list` where department_id = '$department_id' and `status` = 0")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=adviser" class="nav-link nav-adviser">
            <span class="info-box-text"><b>Verified Adviser(s)</b></span>
            <span class="info-box-number text-right">
                <?php
                    $user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
                    $department_id = $_SESSION['department_id']; // Assuming department_id is stored in session
                    echo $conn->query("SELECT * FROM `adviser_list` where department_id = '$department_id' and `status` = 1")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=adviser" class="nav-link nav-adviser">
            <span class="info-box-text"><b>For Verification Approval Adviser(s)</b></span>
            <span class="info-box-number text-right">
                <?php 
                    $user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
                    $department_id = $_SESSION['department_id']; // Assuming department_id is stored in session
                    echo $conn->query("SELECT * FROM `adviser_list` where department_id = '$department_id' and `status` = 0")->num_rows;
                ?>
            </span>
            </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="info-box bg-light shadow">
        <div class="info-box-content">
            <span class="info-box-text"><center><b>TOTAL NO. OF ARCHIVED THESIS AND FEASIBILITY STUDY</b></center></span>
            
            <!-- Filters -->
            <div class="row p-3">
                <div class="col-md-4">
                    <select id="category" class="form-control">
                        <option value="department">By Department</option>
                        <option value="program">By Program</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" id="startYear" class="form-control" placeholder="Start Year">
                </div>
                <div class="col-md-3">
                    <input type="number" id="endYear" class="form-control" placeholder="End Year">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="updateChart()"><i class="fa fa-filter" style="font-size:20px;color:white;"></i> Filter</button>
                </div>
            </div>

            <div class="card-body">
                <canvas id="archiveChart"></canvas>
            </div>
        </div>
    </div>
    </div>
    <?php endif; ?>
    <?php
        // Ensure session is started to access usertype
        session_start();
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
    <!-- Existing Info Boxes -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="info-box bg-light shadow">
            <div class="info-box-content">
                <span class="info-box-text"><center><b>TOP 10 MOST VIEWED ARCHIVE</b></center></span>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><center>#</center></th>
                                <th>Title of Study</th>
                                <th><center>No. of Views</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : 
                                if ($prev_views !== null && $row['views'] != $prev_views) {
                                    $rank++;
                                }
                                $prev_views = $row['views'];
                                if ($rank > 10) break;
                            ?>
                            <tr>
                                <td><center><?php echo $rank; ?></center></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><center><?php echo number_format($row['views']); ?></center></td>
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

document.addEventListener("DOMContentLoaded", function () {
    fetchAndRenderChart();
});

function fetchAndRenderChart(category = "college", startYear = "", endYear = "") {
    fetch(`./get_archive_data.php?category=${category}&startYear=${startYear}&endYear=${endYear}`)
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('archiveChart').getContext('2d');
            const counts = data.counts.map(count => Number(count) || 0);
            const colors = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];
            
            if (archiveChart) {
                archiveChart.destroy();
            }
            
            archiveChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Submitted Archives',
                        data: counts,
                        backgroundColor: colors.slice(0, data.labels.length),
                        borderColor: colors.map(color => color.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMin: 0,
                            max: Math.max(...counts, 1),
                            ticks: {
                                stepSize: 1,
                                callback: function (value) {
                                    return Number.isInteger(value) ? value : null;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

function updateChart() {
    const category = document.getElementById('category').value;
    const startYear = document.getElementById('startYear').value;
    const endYear = document.getElementById('endYear').value;
    fetchAndRenderChart(category, startYear, endYear);
}
</script>