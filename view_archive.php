<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT a.*, di.name as discipline, c.description as college_name, concat(firstname,' ',middlename,' ', lastname) as adviser_name, email as email_address FROM `archive_list` a LEFT JOIN college_list c ON a.college_id = c.id LEFT JOIN adviser_list an ON a.adviser_id = an.id LEFT JOIN discipline_list di ON a.discipline_id = di.id where a.id = '{$_GET['id']}'");
    if($qry->num_rows){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
    $submitted = "N/A";
    if(isset($adviser_id)){
        $adviser = $conn->query("SELECT * FROM adviser_list where id = '{$adviser_id}'");
        if($adviser->num_rows > 0){
            $res = $adviser->fetch_array();
            $submitted = $res['email'];
        }
    }
}
?>
<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    // Update view count
    $conn->query("UPDATE archive_list SET views = views + 1 WHERE id = {$id}");
}
?>
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch usage metrics
    $qry = $conn->query("SELECT (views + likes + downloads) AS total_usage, views, downloads, likes FROM archive_list WHERE id = {$id}");
    $metrics = $qry->fetch_assoc();
    
    $views = $metrics['views'] ?? 0;
    $downloads = $metrics['downloads'] ?? 0;
    $likes = $metrics['likes'] ?? 0;
    $total_usage = $metrics['total_usage'] ?? 0;
}
?>
<html>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    #document_field{
        min-height:80vh
    }
    .content fieldset {
        text-align: left; /* Aligns the entire fieldset content */
    }
    .content fieldset legend {
        text-align: left;
        width: auto; /* Prevents legend from stretching */
        font-weight: bold; /* Optional: makes the legend stand out */
    }
    .content fieldset div {
        text-align: left;
    }
    .content h2 {
        text-align: center;
    }
    .content small.text-muted {
        display: block;
        text-align: center;
    }
    .toggle-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: bold;
        padding: 10px 5px;
        border-bottom: 1px solid #ccc;
    }

    .toggle-content {
        display: none; /* Initially hidden */
        padding: 10px 5px;
    }

    .arrow {
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .rotated {
        transform: rotate(180deg);
    }
    .content.card {
        width: 100%; /* Ensures it takes full width */
        max-width: none; /* Removes any max-width restriction */
    }

    .container {
        width: 100%;
        max-width: none;
        padding: 0 20px; /* Adjust padding as needed */
    }

    .row {
        width: 100%;
        margin: 0 auto; /* Centers the row */
    }
    #pdf-container {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 10px;
        width: 50%;
        box-sizing: border-box;
        scroll-behavior: smooth; /* 👈 Smooth scrolling */
    }

    #pdf-container canvas {
        display: block;
        margin: 10px auto;
        width: 50% !important;      /* Full width of container */
        height: auto !important;     /* Keep aspect ratio */
        max-width: 50%;             /* Ensure it doesn't overflow */
    }

    @media (max-width: 768px) {
        #pdf-container {
            max-height: 250px; /* Adjusted height for smaller devices */
            padding: 8px;
        }
    }

    @media (max-width: 480px) {
        #pdf-container {
            max-height: 200px;
        }
    }
    #usageMetricsChart {
        min-height: 300px;
        height: 40vh;
    }
    @media (max-width: 768px) {
        #usageMetricsChart {
            height: 30vh;
        }
    }
</style>
<!--
<div class="container mt-3">
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
</div>
-->
<div class="card card-outline">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <?php if($type == 1): ?>
            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Thesis Details</h4>
        <?php else: ?>
            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Feasibility Study Details</h4>
        <?php endif; ?>
    </div>
    <div class="col-12">
        <div class="card shadow rounded-5">
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <?php $is_adviser = ($_settings->userdata('type') == 3); ?>
                    <!-- Title -->
                    <h5 class="font-weight-bold text-dark"> <?= isset($title) ? $title : "Untitled" ?> </h5>
                    
                    <!-- Authors -->
                    <p class="text-dark">
                        <strong><?= isset($authors) ? html_entity_decode($authors) : "Unknown Authors" ?></strong>
                    </p>
                    <div class="bg-light p-3 rounded shadow-sm">
                        <p class="mb-2 fw-bold text-dark">
                            <i class="fas fa-user-tie text-primary me-2"></i>
                            <?= isset($thesis_adviser) ? html_entity_decode($thesis_adviser) : '<span class="text-muted">Unknown Thesis Adviser</span>' ?>
                        </p>

                        <!-- Year and College on the same line -->
                        <div class="justify-content-between align-items-center mb-2">
                            <i class="fas fa-building me-2 text-info"></i>
                            <?= isset($college_name) ? html_entity_decode($college_name) : '<span class="text-muted">No College available.</span>' ?>&emsp; - &emsp;
                            <i class="fa fa-calendar-alt me-2 text-success"></i>
                            <span class="badge bg-success">
                                <?= isset($year) ? $year : '----' ?>
                            </span>
                        </div>

                        <!-- Discipline -->
                        <p class="mb-0 text-muted">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>
                            <?= isset($discipline) ? html_entity_decode($discipline) : '<span class="text-muted">No Research Discipline available.</span>' ?>
                        </p>
                    </div>
                    <hr>
                    <!-- PDF Download Button -->
                    <?php if($_settings->userdata('type') == 3): ?>
                    <a href="<?= isset($document_path) ? base_url.$document_path : "#" ?>" class="btn btn-danger" target="_blank">
                        <i class="fa fa-file-pdf" title="Download PDF"></i> Download PDF
                    </a>
                    <?php endif; ?>
                    <?php
                        $archive = $conn->query("SELECT * FROM archive_list WHERE id = {$_GET['id']}")->fetch_assoc();
                        $archiveId = $archive['id'];
                    ?>
                    <!-- Trigger Button -->
                    <?php if (isset($archiveId) && is_numeric($archiveId)): ?>
                        <button class="btn btn-primary" onclick="showSimilarStudies(<?= (int)$archiveId ?>)">Show Similar Studies</button>
                    <?php endif; ?>
                    <!-- Modal -->
                    <div class="modal fade" id="similarModal" tabindex="-1" aria-labelledby="similarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="similarModalLabel">Similar Studies</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="similarResults" style="text-align: left; color: black;">
                                    <p>Loading similar studies...</p>
                                </div>
                                <div class="modal-footer">
                                    <!-- Optional footer close button -->
                                    <button type="button" class="btn btn-secondary" onclick="closeSimilarModal()">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if(isset($adviser_id) && $_settings->userdata('login_type') == "3" && $adviser_id == $_settings->userdata('id')): ?>
                        <div class="form-group">
                            <a href="./?page=submit-archive&id=<?= isset($id) ? $id : "" ?>" class="btn btn-flat btn-default bg-navy btn-sm"><i class="fa fa-edit"></i> Edit</a>
                            <button type="button" data-id = "<?= isset($id) ? $id : "" ?>" class="btn btn-flat btn-danger btn-sm delete-data"><i class="fa fa-trash"></i> Delete</button>
                        </div>
                    <hr>
                    <?php endif; ?>
                    <!-- Abstract Section -->
                    <fieldset>
                        <large class="text-navy font-weight-bold" style="font-family: Arial;">Abstract</large>
                        <div class="pl-4 text-dark" style="text-align: justify;">
                            <large>
                                <?php
                                    if (isset($abstract) && !empty($abstract)) {
                                        // Decode HTML entities and keep basic formatting
                                        $decoded = html_entity_decode($abstract);
                                        $cleaned = strip_tags($decoded, '<em><strong><i><b>');

                                        // Split into sentences
                                        $sentences = preg_split('/(?<=[.?!])\s+/', $cleaned, -1, PREG_SPLIT_NO_EMPTY);
                                        $sentence_count = count($sentences);

                                        if ($sentence_count >= 3) {
                                            // Show first 2, blur the rest
                                            $visible = array_slice($sentences, 0, 2);
                                            $blurred = array_slice($sentences, 2);

                                            echo implode(' ', $visible) . ' ';
                                            if (!$is_adviser) {
                                                echo '<span style="filter: blur(5px); -webkit-filter: blur(5px);">' . implode(' ', $blurred) . '</span>';
                                            } else {
                                                echo implode(' ', $blurred);
                                            }

                                        } elseif ($sentence_count === 2) {
                                            // Show first sentence, blur the second
                                            echo $sentences[0] . ' ';
                                            if (!$is_adviser) {
                                                echo '<span style="filter: blur(5px); -webkit-filter: blur(5px);">' . $sentences[1] . '</span>';
                                            } else {
                                                echo $sentences[1];
                                            }

                                        } elseif ($sentence_count === 1) {
                                            // One sentence: blur last 10 words
                                            $words = preg_split('/\s+/', $sentences[0]);
                                            $word_count = count($words);

                                            if ($word_count <= 10) {
                                                if (!$is_adviser) {
                                                    echo '<span style="filter: blur(5px); -webkit-filter: blur(5px);">' . implode(' ', $words) . '</span>';
                                                } else {
                                                    echo implode(' ', $words);
                                                }
                                            } else {
                                                $visible_words = array_slice($words, 0, $word_count - 10);
                                                $blurred_words = array_slice($words, $word_count - 10);

                                                echo implode(' ', $visible_words) . ' ';
                                                if (!$is_adviser) {
                                                    echo '<span style="filter: blur(5px); -webkit-filter: blur(5px);">' . implode(' ', $blurred_words) . '</span>';
                                                } else {
                                                    echo implode(' ', $blurred_words);
                                                }
                                            }
                                        }
                                    } else {
                                        echo "No abstract available.";
                                    }
                                ?>
                            </large>
                        </div>

                        <?php if (!$is_adviser): ?>
                        <div class="text-center mt-2">
                            <p class="bg-info text-dark p-2 rounded">
                                Please See Thesis | Feasibility Study Adviser to View Full Abstract
                            </p>
                        </div>
                        <?php endif; ?>
                    </fieldset>
                    <!--
                    <fieldset>
                        <legend class="text-navy font-weight-bold" style="font-family: Arial;">Submitted to</legend>
                        <div class="pl-4 text-dark text-justify" style="text-align: justify;">
                            <small>NEMSU Main Campus Archiving Hub</small>&emsp;&emsp;<i class="fas fa-map-marker-alt"> <small>Brgy. Rosario, Tandag City, Surigao del Sur</small></i>
                        </div>
                    </fieldset>
                    -->
                    <!-- Submitted by -->
                    <fieldset>
                        <large class="text-navy font-weight-bold" style="font-family: Arial;">Uploaded by</large>
                        <div class="pl-4 text-dark text-justify" style="text-align: justify;">
                            <small><i class="fas fa-user"> <?= isset($adviser_name) ? html_entity_decode($adviser_name) : "No Program Adviser available." ?></i></small>
                            <small><i class="fas fa-envelope"> <?= isset($email_address) ? html_entity_decode($email_address) : "No Email Address available." ?></i></small>
                        </div>
                    </fieldset>
                    <!-- Gaps -->
                    <fieldset style="border: none; padding: 0;">
                        <div class="toggle-header" id="toggleGap">
                            <large class="text-navy font-weight-bold" style="font-family: Arial;">Gaps</large>
                            <span class="arrow">&#128899;</span> <!-- Unicode down arrow -->
                        </div>
                        <div id="gapContainer" class="toggle-content">
                            <div class="pl-4 text-dark" style="text-align: justify;">
                                <?php if(isset($gaps) && !empty($gaps)): ?>
                                <large><?= html_entity_decode($gaps) ?></large>
                                <?php else: ?>
                                <p>Generate Gap(s).</p>
                                <?php endif; ?>
                            </div>
                            <?php if($_settings->userdata('type') == 3): ?>
                            <button id="generateGapBtn" class="btn btn-primary mt-3">
                                <i class="fas fa-lightbulb"></i> Generate Gap
                            </button>
                            <?php endif; ?>
                        </div>
                    </fieldset>
                    <?php if (!empty($keywords)) : ?>
                    <fieldset style="border: none; padding: 0;">
                        <div class="toggle-header" id="toggleKeywords">
                            <large class="text-dark">Keywords</large>
                            <span class="arrow">&#128899;</span> <!-- Unicode down arrow -->
                        </div>
                        <div id="keywordsContainer" class="toggle-content">
                            <div class="pl-4 text-dark">
                                <large><?= html_entity_decode($keywords) ?></large>
                            </div>
                        </div>
                    </fieldset>
                    <?php endif; ?>

                    <!-- Usage Metrics Graph -->
                    <fieldset style="border: none; padding: 0;">
                        <div class="toggle-header" id="toggleUsageMetrics">
                            <large class="text-dark">Usage Metrics</large>
                            <span class="arrow">&#128899;</span> <!-- Unicode down arrow -->
                        </div>
                        <div id="usageMetricsContainer" class="toggle-content">
                            <!--
                            <div class="container mt-4">
                                <div class="card shadow p-3 text-dark">
                                    <h5 class="text-center">Usage Metrics</h5>
                                    <hr>
                                    <h7 class="text-left">Total Usage: <?= $total_usage; ?> (Views, Downloaded PDF, and Likes)</h7>
                                    <canvas id="usageChart"></canvas>
                                </div>
                            </div>
                            -->
                            <div class="container-fluid mt-4">
                                <div class="card shadow-sm rounded-4">
                                    <div class="card-header bg-dark text-white">
                                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Usage Metrics</h5>
                                    </div>
                                    <h7 class="text-dark">&nbsp;Total Usage: <?= $total_usage; ?> (Views, Downloaded PDF, and Likes)</h7>
                                    <div class="card-body">
                                        <canvas id="usageMetricsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    
                    <br>
                    <fieldset>
                        <large class="text-navy font-weight-bold">Document Preview</large>

                        <div id="pdf-container-wrapper" style="position: relative;">
                            <div id="pdf-container" style="height: 700px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; width: 100%; box-sizing: border-box;"></div>

                            <?php if (!$is_adviser): ?>
                                <div class="text-center mt-2">
                                    <p class="bg-info text-dark p-2 rounded">
                                        Please See Thesis | Feasibility Study Adviser to View Full PDF Document
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    $(function(){
        $('.delete-data').click(function(){
            _conf("Are you sure to delete <b>Archive-<?= isset($archive_code) ? $archive_code : "" ?></b>","delete_archive")
        })
    })
    function delete_archive(){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_archive",
			method:"POST",
			data:{id: "<?= isset($id) ? $id : "" ?>"},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace("./");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}

$(document).ready(function() {
    $("#generateGapBtn").click(function() {
        var abstract = <?= json_encode($abstract ?? ""); ?>;
        var archive_code = <?= json_encode($id ?? ""); ?>;

        if (!abstract.trim()) {
            alert("No abstract available to analyze.");
            return;
        }

        $("#generateGapBtn").prop("disabled", true).text("Generating...");

        $.ajax({
            url: "generate_gap.php",
            type: "POST",
            data: { abstract: abstract, archive_code: archive_code },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#gapContainer .pl-4").html("<large>" + response.gap + "</large>");
                    
                    // Show gap section if hidden
                    $("#gapContainer").show();

                    // 🔹 Hide the button after successful generation
                    $("#generateGapBtn").prop("disabled", true).hide();
                } else {
                    alert("Failed to generate the study gap.");
                    $("#generateGapBtn").prop("disabled", false).text("Generate Study Gap");
                }
            },
            error: function() {
                alert("An error occurred while processing.");
                $("#generateGapBtn").prop("disabled", false).text("Generate Study Gap");
            }
        });
    });

    // 🔹 Hide button if a gap already exists
    var existingGap = <?= json_encode($gaps ?? ""); ?>;
    if (existingGap.trim() !== "") {
        $("#generateGapBtn").hide();
    }
});

// 🔹 Toggle Gaps Section
document.getElementById("toggleGap").addEventListener("click", function() {
    var gapContainer = document.getElementById("gapContainer");
    var arrow = document.querySelector(".arrow");

    if (gapContainer.style.display === "none" || gapContainer.style.display === "") {
        gapContainer.style.display = "block";
        arrow.classList.add("rotated"); // Rotate arrow
    } else {
        gapContainer.style.display = "none";
        arrow.classList.remove("rotated"); // Reset arrow
    }
});
$(document).ready(function () {
    function setupToggle(toggleId, contentId) {
        var toggle = document.getElementById(toggleId);
        var content = document.getElementById(contentId);

        if (toggle && content) {
            toggle.addEventListener("click", function () {
                var arrow = toggle.querySelector(".arrow");

                if (content.style.display === "none" || content.style.display === "") {
                    content.style.display = "block";
                    arrow.classList.add("rotated"); // Rotate arrow down
                } else {
                    content.style.display = "none";
                    arrow.classList.remove("rotated"); // Rotate arrow back
                }
            });
        }
    }

    // ✅ Setup toggles for Keywords and Usage Metrics
    setupToggle("toggleKeywords", "keywordsContainer");
    setupToggle("toggleUsageMetrics", "usageMetricsContainer");

    // ✅ Initialize the chart only if it exists
    if ($("#usageChart").length) {
        const ctx = document.getElementById("usageChart").getContext("2d");

        const data = {
            labels: ["Views", "Downloads", "Likes"],
            datasets: [{
                label: "Usage Metrics",
                data: [<?= $views ?>, <?= $downloads ?>, <?= $likes ?>], // Values from PHP
                backgroundColor: ["#36A2EB", "#FF6384", "#FFCE56"],
                borderColor: ["#2F7CBF", "#D94C63", "#E5B438"],
                borderWidth: 1
            }]
        };

        new Chart(ctx, {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
const url = "<?= base_url . $document_path ?>";
    const isAdviser = <?= $is_adviser ? 'true' : 'false' ?>;

    const container = document.getElementById("pdf-container");
    const notice = document.getElementById("adviser-notice");

    const renderPDF = async () => {
        const pdf = await pdfjsLib.getDocument(url).promise;

        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            const page = await pdf.getPage(pageNum);
            const scale = 1.3;
            const viewport = page.getViewport({ scale });

            const canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            await page.render({
                canvasContext: context,
                viewport: viewport
            }).promise;

            canvas.setAttribute("data-page", pageNum);
            
            // 👇 Ensure single-column layout
            canvas.style.display = "block";
            canvas.style.margin = "10px auto";
            canvas.style.width = "100%";
            canvas.style.height = "auto";

            if (!isAdviser && pageNum > 1) {
                canvas.style.filter = "blur(5px)";
            }

            container.appendChild(canvas);
        }
    };

    // Show button on scroll past page 1
    if (!isAdviser) {
        container.addEventListener("scroll", () => {
            const canvases = container.querySelectorAll("canvas");
            const secondPage = canvases[1];

            if (secondPage) {
                const secondPageTop = secondPage.offsetTop;
                const scrollTop = container.scrollTop;
                const containerHeight = container.clientHeight;

                // If second page is in view
                if (scrollTop + containerHeight >= secondPageTop + 30) {
                    notice.style.display = "block";
                } else {
                    notice.style.display = "none";
                }
            }
        });
    }

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    renderPDF();
let similarModalInstance;

    function showSimilarStudies(archiveId) {
        if (!archiveId || isNaN(archiveId)) {
            alert('Invalid archive ID.');
            return;
        }

        const modalElement = document.getElementById('similarModal');
        similarModalInstance = new bootstrap.Modal(modalElement);
        similarModalInstance.show();

        fetch('get_similar_studies.php?id=' + archiveId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('similarResults').innerHTML = data;
            })
            .catch(err => {
                document.getElementById('similarResults').innerHTML = '<p class="text-danger">Error loading similar studies.</p>';
            });
    }

    function closeSimilarModal() {
        if (similarModalInstance) {
            similarModalInstance.hide();
        } else {
            const modalElement = document.getElementById('similarModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('usageMetricsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Views', 'Downloads', 'Likes'],
                datasets: [{
                    label: 'Total Count',
                    data: [<?= $views ?>, <?= $downloads ?>, <?= $likes ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',  // Views - Blue
                        'rgba(75, 192, 192, 0.7)',  // Downloads - Green
                        'rgba(255, 99, 132, 0.7)'   // Likes - Red
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw.toLocaleString()}`;
                            }
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
    });
</script>