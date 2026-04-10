<style>
    .content.card {
        width: 100%;
        max-width: none;
    }

    .container {
        width: 100%;
        padding: 0 15px;
    }

    .archive-card {
        margin-bottom: 1rem;
    }

    .archive-header h5 {
        font-size: 1.1rem;
    }

    .archive-meta small {
        font-size: 0.85rem;
    }

    .abstract p {
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .archive-meta {
            flex-direction: column;
        }

        .archive-actions {
            flex-wrap: wrap;
        }
    }
</style>
<!--
<div class="container mt-3">
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
</div>
-->
<div class="content py-2">
    <div class="row">
        <div class="col-md-3 bg-info">
            <hr>
            <h5>Year Range</h5>
                <input type="number" id="yearFrom" class="form-control mb-2" placeholder="From" value="<?= isset($_GET['year_from']) ? $_GET['year_from'] : '' ?>">
                <input type="number" id="yearTo" class="form-control mb-2" placeholder="To" value="<?= isset($_GET['year_to']) ? $_GET['year_to'] : '' ?>">
                <button id="applyYearFilter" class="btn btn-primary btn-sm w-100 mb-2">Apply</button>
                <button id="clearYearFilter" class="btn btn-secondary btn-sm w-100">Clear</button>
        </div>
        <div class="col-md-9">
            <div class="card card-outline card-primary shadow rounded-0">
                <div class="card-body rounded-0">
                    <h2 class="card-body rounded-0 bg-primary text-white d-flex justify-content-between align-items-center">Archive(s)</h2>
                    <hr class="bg-navy">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <label for="sort" class="mr-2">Sort By:</label>
                            <select id="sort" class="form-control form-control-sm d-inline w-auto">
                                <option id="relevance" value="relevance">Relevance</option>
                                <option id="newest" value="newest">Newest</option>
                                <option id="oldest" value="oldest">Oldest</option>
                                <option id="most_views" value="most_views">Most Views</option>
                                <option id="title_az" value="title_az">Title A-Z</option>
                                <option id="title_za" value="title_za">Title Z-A</option>
                            </select>
                        </div>
                    </div>
                    
                    <?php 
                    $limit = 10;
                    $page = isset($_GET['p'])? $_GET['p'] : 1; 
                    $offset = 10 * ($page - 1);
                    $paginate = " limit {$limit} offset {$offset}";
                    $isSearch = isset($_GET['q']) ? "&q={$_GET['q']}" : "";
                    $search = "";
                    
                    if(isset($_GET['q'])){
                        $keyword = $conn->real_escape_string($_GET['q']);
                        $highlight = "<span class='bg-warning font-weight-bold'>{$keyword}</span>";
                        $search = " AND (title LIKE '%{$keyword}%' OR abstract LIKE '%{$keyword}%' 
                                    OR authors LIKE '%{$keyword}%' 
                                    OR curriculum_id IN (SELECT id FROM curriculum_list WHERE name LIKE '%{$keyword}%' OR description LIKE '%{$keyword}%')
                                    OR discipline_id IN (SELECT id FROM discipline_list WHERE name LIKE '%{$keyword}%' OR description LIKE '%{$keyword}%')
                                    OR curriculum_id IN (SELECT id FROM curriculum_list WHERE department_id IN 
                                        (SELECT id FROM department_list WHERE name LIKE '%{$keyword}%' OR description LIKE '%{$keyword}%'))) ";
                    }
                    $yearFilter = "";
                    if (isset($_GET['year_from']) && is_numeric($_GET['year_from'])) {
                        $yearFrom = intval($_GET['year_from']);
                        $yearFilter .= " AND year >= {$yearFrom}";
                    }
                    if (isset($_GET['year_to']) && is_numeric($_GET['year_to'])) {
                        $yearTo = intval($_GET['year_to']);
                        $yearFilter .= " AND year <= {$yearTo}";
                    }
                    $sortOrder = "unix_timestamp(year) DESC"; // Default sorting: newest
                    if (isset($_GET['sort'])) {
                        switch ($_GET['sort']) {
                            case 'relevance':
                                $sortOrder = "CASE WHEN title LIKE '%{$keyword}%' THEN 1 ELSE 0 END DESC, title ASC";
                                break;
                            case 'newest':
                                $sortOrder = "year DESC";
                                break;
                            case 'oldest':
                                $sortOrder = "year ASC";
                                break;
                            case 'most_views':
                                $sortOrder = "views DESC";
                                break;
                            case 'title_az':
                                $sortOrder = "title ASC";
                                break;
                            case 'title_za':
                                $sortOrder = "title DESC";
                                break;
                        }
                    }
                    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
                    $whereClause = "";
                    if ($filter == "thesis") {
                        $whereClause = "AND type = 1";
                    } elseif ($filter == "feasibility") {
                        $whereClause = "AND type = 2";
                    }
                    
                    $totalarchives = "SELECT * FROM archive_list WHERE `status` = 1 ";
                    $query = "SELECT a.*, c.description AS college_name 
                                FROM archive_list a 
                                LEFT JOIN college_list c ON a.college_id = c.id 
                                WHERE a.status = 1 {$search} {$yearFilter} {$whereClause} 
                                ORDER BY {$sortOrder} {$paginate}";
                    $archives = $conn->query($query);
                    $totarch = $conn->query($totalarchives);
                    $total_count = $totarch->num_rows;
                    ?>
                    
                    <?php if(!empty($isSearch)): ?>
                        <h3 class="text-center"><b>Search Result for "<?= $keyword ?>"</b></h3>
                    <?php endif; ?>

                    <div class="list-group">
                        <?php while($row = $archives->fetch_assoc()):
                            $row['title'] = preg_replace_callback("/" . preg_quote($keyword, '/') . "/i", function ($match) {
                                return "<span class='bg-warning font-weight-bold'>{$match[0]}</span>";
                            }, $row['title']);

                            $row['authors'] = preg_replace_callback("/" . preg_quote($keyword, '/') . "/i", function ($match) {
                                return "<span class='bg-warning font-weight-bold'>{$match[0]}</span>";
                            }, $row['authors']);

                            $row['abstract'] = preg_replace_callback("/" . preg_quote($keyword, '/') . "/i", function ($match) {
                                return "<span class='bg-warning font-weight-bold'>{$match[0]}</span>";
                            }, strip_tags(html_entity_decode($row['abstract'])));
                        ?>
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <a href="./?page=view_archive&id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                                        <h5 class="text-primary font-weight-bold mb-1 text-justify"><?= $row['title'] ?></h5>
                                    </a>
                                    <small class="text-muted d-block text-justify">
                                        Authors: <b class="text-dark"><?= html_entity_decode($row['authors']) ?></b>
                                    </small>
                                    <small class="text-muted d-block text-justify">
                                        Submitted to: <b>NEMSU Archiving Hub, 2025</b>
                                    </small>
                                    <small class="text-muted d-block text-justify">
                                        Archives from : <b><?= $row['college_name'] ?></b>
                                    </small>
                                    <small class="text-muted d-block text-justify">Year Submitted: <?= $row['year'] ?></small>
                                    <div class="d-flex">
                                        <small class="text-muted">Views: <b class="text-success"><?= $row['views'] ?></b></small>&emsp;
                                        <small class="text-muted likes-count">Likes: <b class="text-success"><?= $row['likes'] ?></b></small>&emsp;
                                        <small class="text-muted">
                                            Downloads: <b class="text-success download-count-<?= $row['id'] ?>"><?= $row['downloads'] ?></b>
                                        </small>
                                    </div>
                                    <div class="mt-3 p-3 border rounded bg-light">
                                        <!-- Top Section -->
                                        <div class="d-flex align-items-center gap-4">
                                            <button class="btn btn-link p-0 ml-2" onclick="toggleAbstract(this)">
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                            <small class="text-muted font-weight-bold ml-2">Abstract</small>
                                            <?php if($_settings->userdata('type') == 3): ?>
                                            <a href="#" class="download-btn ml-4" data-id="<?= $row['id'] ?>">
                                                <i class="fas fa-file-pdf text-danger" style="font-size: 24px;" title="Download PDF"></i>
                                            </a>
                                             <?php endif; ?>
                                            <!-- Bookmark Button -->
                                            <?php if($_settings->userdata('type') == 2): ?>
                                            <a href="#" class="bookmark-btn ml-4" data-id="<?= $row['id'] ?>">
                                                <i class="fa fa-bookmark text-secondary" style="font-size: 24px;"></i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="#" class="like-btn ml-4" data-id="<?= $row['id'] ?>">
                                                <i class="fa fa-thumbs-up text-secondary" style="font-size: 24px;"></i>
                                            </a>
                                            <!-- Folder Icon with Modal Trigger -->
                                            <a href="#" class="save-to-library ml-4" data-id="<?= $row['id'] ?>" data-title="<?= $row['title'] ?>">
                                                <i class="fa fa-folder text-warning" style="font-size: 24px;" title="Save to Folder"></i>
                                            </a>
                                            <!-- Modal for Creating/Selecting Folder -->
                                            <div class="modal fade" id="folderModal" tabindex="-1" aria-labelledby="folderModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="folderModalLabel">Save to Library</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label for="folder-select">Select Folder:</label>
                                                            <select id="folder-select" class="form-control">
                                                                <option value="">-- Choose Folder --</option>
                                                            </select>
                                                            <hr>
                                                            <label for="new-folder">Or Create a New Folder:</label>
                                                                <input type="text" id="new-folder" class="form-control" placeholder="Enter folder name">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary" id="save-folder">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $is_adviser = ($_settings->userdata('type') == 3); ?>
                                        <!-- Abstract Content -->
                                        <div class="abstract mt-3 d-none">
                                            <p class="text-justify" style="<?= $is_adviser ? '' : 'filter: blur(3px);' ?>"><?= $row['abstract'] ?></p>
                                            <?php if (!$is_adviser): ?>
                                            <div class="text-center mt-2">
                                                <p class="bg-info text-dark p-2 rounded">
                                                    Please See Thesis | Feasibility Study Adviser to View Full Abstract
                                                </p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="card-footer clearfix rounded-0">
                <div class="col-12">
                    <div class="row">
                        <?php
                            $totalRecordsQuery = "SELECT COUNT(*) AS total FROM archive_list WHERE status = 1 {$search} {$yearFilter} {$whereClause}";
                            $result = $conn->query($totalRecordsQuery);
                            $totalRecords = $result->fetch_assoc()['total'];
                            $pages = ceil($totalRecords / $limit);
                        ?>
                        <div class="col-md-6 text-left">
                            <span class="text-muted">Displaying <?= $archives->num_rows ?> of <?= $totalRecords ?> Item(s) for <?= $keyword ?> from <?= $total_count ?> total Archives</span>
                        </div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item">
                                    <a class="page-link" href="./?page=projects<?= $isSearch ?>&p=<?= $page - 1 ?>" <?= $page == 1 ? 'disabled' : '' ?>>«</a>
                                </li>
                                <?php for($i = 1; $i<= $pages; $i++): ?>
                                    <li class="page-item">
                                        <a class="page-link <?= $page == $i ? 'active' : '' ?>" href="./?page=projects<?= $isSearch ?>&p=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item">
                                    <a class="page-link" href="./?page=projects<?= $isSearch ?>&p=<?= $page + 1 ?>" <?= $page == $pages ? 'disabled' : '' ?>>»</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("applyYearFilter").addEventListener("click", function () {
            let yearFrom = document.getElementById("yearFrom").value;
            let yearTo = document.getElementById("yearTo").value;
            let urlParams = new URLSearchParams(window.location.search);
            
            if (yearFrom) urlParams.set("year_from", yearFrom);
            if (yearTo) urlParams.set("year_to", yearTo);
            
            window.location.search = urlParams.toString();
        });
    });
    document.getElementById('clearYearFilter').addEventListener('click', function () {
        document.getElementById('yearFrom').value = '';
        document.getElementById('yearTo').value = '';
    });
    function toggleAbstract(button) {
        let abstractDiv = button.closest('div').nextElementSibling;
        let icon = button.querySelector('i');

        if (abstractDiv.classList.contains('d-none')) {
            abstractDiv.classList.remove('d-none');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            abstractDiv.classList.add('d-none');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".bookmark-btn").forEach(button => {
            let docId = button.getAttribute("data-id");
            let icon = button.querySelector("i");

            // Fetch bookmark status from the server
            fetch(`get_bookmark_status.php?id=${docId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.bookmarked) {
                        icon.classList.add("text-warning");
                        icon.classList.remove("text-secondary");
                    }
                })
                .catch(error => console.error("Error fetching bookmark status:", error));

            // Toggle bookmark on click
            button.addEventListener("click", function (event) {
                event.preventDefault();

                let isBookmarked = icon.classList.contains("text-warning");
                let action = isBookmarked ? "remove" : "add";

                // Optimistically update UI
                icon.classList.toggle("text-warning", !isBookmarked);
                icon.classList.toggle("text-secondary", isBookmarked);

                // Send request to update bookmark status in the database
                fetch("update_bookmark.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${docId}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert("Failed to update bookmark. Please try again.");
                        // Rollback UI changes if request fails
                        icon.classList.toggle("text-warning", isBookmarked);
                        icon.classList.toggle("text-secondary", !isBookmarked);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred. Please try again later.");
                    // Rollback UI changes if there’s an error
                    icon.classList.toggle("text-warning", isBookmarked);
                    icon.classList.toggle("text-secondary", !isBookmarked);
                });
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        let sortSelect = document.getElementById("sort");
        let urlParams = new URLSearchParams(window.location.search);
    
        // Preserve the selected sorting option
        if (urlParams.has("sort")) {
            sortSelect.value = urlParams.get("sort");
        }

        // Event listener to update the sorting when changed
        sortSelect.addEventListener("change", function () {
            urlParams.set("sort", this.value);
            window.location.search = urlParams.toString();
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("input[name='filter']").forEach(radio => {
            radio.addEventListener("change", function() {
                let filter = this.value;
                let urlParams = new URLSearchParams(window.location.search);
                if (filter === "all") {
                    urlParams.delete("filter"); // Remove filter when 'All' is selected
                } else {
                    urlParams.set("filter", filter);
                }
                window.location.search = urlParams.toString();
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".like-btn").forEach(button => {
            let docId = button.getAttribute("data-id");
            let icon = button.querySelector("i");
            let likeCountElement = button.closest(".list-group-item").querySelector(".likes-count b");

            // Fetch like status from the server
            fetch(`get_like_status.php?id=${docId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.liked) {
                        button.classList.add("liked");
                        icon.classList.add("text-primary");
                        icon.classList.remove("text-secondary");
                    }
                    likeCountElement.textContent = data.likes; // Set the current like count
                })
                .catch(error => console.error("Error fetching like status:", error));

            button.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent default anchor behavior

                if (this.classList.contains("liked")) {
                    alert("You have already liked this title.");
                    return;
                }

                // Optimistically update UI before sending request
                this.classList.add("liked");
                icon.classList.add("text-primary");
                icon.classList.remove("text-secondary");

                let currentLikes = parseInt(likeCountElement.textContent);
                likeCountElement.textContent = currentLikes + 1;

                // Send request to update likes in the database
                fetch("update_likes.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${docId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Rollback UI changes if request fails
                        alert("Failed to like. Please try again.");
                        this.classList.remove("liked");
                        icon.classList.remove("text-primary");
                        icon.classList.add("text-secondary");
                        likeCountElement.textContent = currentLikes; // Reset count
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred. Please try again later.");
                    // Rollback UI changes if there's an error
                    this.classList.remove("liked");
                    icon.classList.remove("text-primary");
                    icon.classList.add("text-secondary");
                    likeCountElement.textContent = currentLikes; // Reset count
                });
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () { 
        document.querySelectorAll(".download-btn").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                let docId = this.getAttribute("data-id");

                fetch(`get_document.php?id=${docId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let filePath = data.file_path.replace(/\?.*$/, '');

                            // Create a temporary download link
                            let downloadLink = document.createElement("a");
                            downloadLink.href = filePath;
                            downloadLink.setAttribute("download", filePath.split('/').pop());
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);

                            // Send request to update download count
                            fetch("update_download.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `id=${docId}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the displayed download count
                                    let countElement = document.querySelector(`.download-count-${docId}`);
                                    if (countElement) {
                                        countElement.textContent = data.new_count;
                                    }
                                } else {
                                    console.error("Failed to update download count:", data.message);
                                }
                            })
                            .catch(error => console.error("Error updating download count:", error));
                        } else {
                            alert("File not found: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error fetching file:", error));
            });
        });
    });
    $(document).ready(function() {
    $(".save-to-library").click(function(e) {
        e.preventDefault();
        let archiveId = $(this).data("id");
        let title = $(this).data("title");
        
        // Populate folders in dropdown
        $.ajax({
            url: "get_folders.php",
            type: "GET",
            success: function(data) {
                let folders = JSON.parse(data);
                $("#folder-select").html('<option value="">-- Choose Folder --</option>');
                folders.forEach(folder => {
                    $("#folder-select").append(`<option value="${folder.id}">${folder.name}</option>`);
                });
            }
        });
        
        // Show modal
        $("#folderModal").modal("show");

        // Handle saving
        $("#save-folder").off("click").on("click", function() {
            let folderId = $("#folder-select").val();
            let newFolderName = $("#new-folder").val().trim();
            
            $.ajax({
                url: "save_to_folder.php",
                type: "POST",
                data: { archive_id: archiveId, folder_id: folderId, new_folder: newFolderName },
                success: function(response) {
                    if (response === "success") {
                        alert("Saved successfully!");
                        $("#folderModal").modal("hide");
                    } else {
                        alert("Error: " + response);
                    }
                }
            });
        });

        // Handle modal close
        $("#folderModal .close, #folderModal .btn-secondary").on("click", function() {
            $("#folderModal").modal("hide");
        });
    });
});
</script>