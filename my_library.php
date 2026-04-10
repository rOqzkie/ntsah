<?php
require_once("./config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$folders_query = $conn->prepare("
    SELECT f.id, f.name, COUNT(fi.archive_id) AS archive_count 
    FROM user_folders f
    LEFT JOIN user_folder_items fi ON f.id = fi.folder_id
    WHERE f.user_id = ?
    GROUP BY f.id
    ORDER BY f.created_at DESC
");
$folders_query->bind_param("i", $user_id);
$folders_query->execute();
$folders = $folders_query->get_result();
?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .folder-card {
        transition: transform 0.2s ease-in-out;
    }
    .folder-card:hover {
        transform: translateY(-5px);
    }
    .delete-folder {
        z-index: 10;
    }
</style>

<div class="container mt-3">
    <!--
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <a href="./" class="btn btn-secondary btn-sm">← Back to Home</a>
        <?php if ($_settings->userdata('type') == 2): ?>
            <a href="./?page=profile" class="btn btn-secondary btn-sm">← Back to Profile</a>
        <?php endif; ?>
        <?php if ($_settings->userdata('type') == 3): ?>
            <a href="./?page=profile-adviser" class="btn btn-secondary btn-sm">← Back to Profile</a>
        <?php endif; ?>
    </div>
    -->
</div>

<div class="container my-4">
    <h2 class="bg-primary text-white">📚 My Library</h2>
    <hr>
    <?php if ($folders->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php while ($folder = $folders->fetch_assoc()): ?>
            <div class="col">
                <div class="card folder-card h-100 shadow-sm position-relative">
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-folder" 
                            data-id="<?= $folder['id'] ?>" 
                            title="Delete Folder">
                        🗑
                    </button>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($folder['name']) ?></h5>
                        <p class="text-muted mb-3">📄 <?= $folder['archive_count'] ?> items</p>
                        <a href="./?page=view_folder&id=<?= $folder['id'] ?>" class="btn btn-primary mt-auto">View Folder</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4" role="alert">
            <!--📭 Your library is empty. Start adding folders to organize your archives!-->
            <p class="mb-2">📭  Your library is empty. Start adding folders to organize your archives!</p>
            <a href="./?page=archives" class="btn btn-primary btn-sm">Browse Archives</a>
        </div>
    <?php endif; ?>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $(".delete-folder").click(function(){
        var folderId = $(this).data("id");
        var element = $(this).closest(".col");

        if (confirm("Are you sure you want to delete this folder and all its contents?")) {
            $.ajax({
                url: "delete_folder.php",
                type: "POST",
                data: { id: folderId },
                success: function(response) {
                    if (response.trim() === "success") {
                        element.fadeOut(500, function(){ $(this).remove(); });
                    } else {
                        alert("Failed to delete the folder.");
                    }
                }
            });
        }
    });
});
</script>