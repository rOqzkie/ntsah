<?php
require_once("./config.php");
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$folder_id = $_GET['id'];

$folder_query = $conn->prepare("SELECT name FROM user_folders WHERE id = ? AND user_id = ?");
$folder_query->bind_param("ii", $folder_id, $user_id);
$folder_query->execute();
$folder_result = $folder_query->get_result();

if ($folder_result->num_rows === 0) {
    die("Folder not found.");
}

$folder = $folder_result->fetch_assoc();

$archives_query = $conn->prepare("
    SELECT a.id, a.title, a.authors, a.year, a.abstract
    FROM user_folder_items fi
    JOIN archive_list a ON fi.archive_id = a.id
    WHERE fi.folder_id = ? AND fi.user_id = ?
");
$archives_query->bind_param("ii", $folder_id, $user_id);
$archives_query->execute();
$archives = $archives_query->get_result();
?>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .archive-item {
        transition: transform 0.2s;
    }

    .archive-item:hover {
        transform: scale(1.01);
        background-color: #f8f9fa;
    }

    .delete-archive {
        z-index: 10;
    }

    @media (max-width: 576px) {
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>

<div class="container my-4">
    <h2 class="text-dark mb-3">📁 <?= htmlspecialchars($folder['name']) ?></h2>
    <a href="./?page=my_library" class="btn btn-secondary btn-sm mb-4">⬅ Back to Library</a>

    <div class="list-group">
        <?php while ($archive = $archives->fetch_assoc()): ?>
            <div class="list-group-item archive-item position-relative d-flex flex-column" id="archive-<?= $archive['id'] ?>">
                <button class="btn btn-danger btn-sm position-absolute delete-archive" 
                        data-id="<?= $archive['id'] ?>" 
                        style="top: 10px; right: 10px;" 
                        title="Remove Study">🗑</button>
                
                <a href="./?page=view_archive&id=<?= $archive['id'] ?>" class="text-decoration-none text-reset">
                    <h5 class="fw-semibold text-primary"><?= htmlspecialchars($archive['title']) ?></h5>
                    <small class="text-muted d-block">
                        📖 <?= html_entity_decode($archive['authors']) ?> | 🗓️ <?= $archive['year'] ?>
                    </small>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $(".delete-archive").click(function(){
        var archiveId = $(this).data("id");
        var element = $("#archive-" + archiveId);

        if(confirm("Are you sure you want to remove this study from the folder?")) {
            $.ajax({
                url: "delete_archive.php",
                type: "POST",
                data: { id: archiveId },
                success: function(response) {
                    if (response.trim() === "success") {
                        element.fadeOut(500, function(){ $(this).remove(); });
                    } else {
                        alert("Failed to delete. Please try again.");
                    }
                }
            });
        }
    });
});
</script>