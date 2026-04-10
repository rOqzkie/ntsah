<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `archive_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows) {
        foreach ($qry->fetch_array() as $k => $v) {
            if (!is_numeric($k)) $$k = $v;
        }
    }
    if (isset($adviser_id)) {
        if ($adviser_id != $_settings->userdata('id')) {
            echo "<script> alert('You don\'t have access to this page'); location.replace('./'); </script>";
        }
    }
}
?>

<style>
.banner-img {
    object-fit: scale-down;
    object-position: center center;
    height: 30vh;
    width: 100%;
}
.form-group label {
        display: block;
        text-align: left;
    }
    .form-check {
        text-align: left;
    }
    .note-editable {
    color: black !important;
}
</style>

<div class="container mt-3">
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
</div>

<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title">
                <?= isset($id) ? "<b>Update Archive-{$archive_code} Details</b>" : "<b>Submit Thesis | Feasibility Study</b>" ?>
            </h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="archive-form">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">
                    
                    <div class="form-group">
                        <label for="title" class="control-label text-navy">Title of Study</label>
                        <input type="text" name="title" id="title" autofocus placeholder="Enter Title" class="form-control form-control-border" value="<?= isset($title) ? $title : "" ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label text-navy">Type</label>
                        <div class="d-flex gap-4">
                            <div class="form-check" style="margin-right: 20px;">
                                <input class="form-check-input" type="radio" name="type" id="thesis" value="1" <?php if($type == "1") echo 'checked'; ?> required>
                                <label class="form-check-label" for="thesis" style="color: black;">Thesis</label>&emsp;
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="feasibility" value="2" <?php if($type == "2") echo 'checked'; ?> required>
                                <label class="form-check-label" for="feasibility" style="color: black;">Feasibility Study</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="year" class="control-label text-navy">Year</label>
                        <select name="year" id="year" class="form-control form-control-border" required>
                            <?php for ($i = 0; $i < 51; $i++): ?>
                                <option <?= isset($year) && $year == date("Y", strtotime("-{$i} years")) ? "selected" : "" ?>>
                                    <?= date("Y", strtotime("-{$i} years")) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="abstract" class="control-label text-navy">Abstract</label>
                        <textarea rows="3" name="abstract" id="abstract" placeholder="abstract" class="form-control form-control-border summernote" required><?= isset($abstract) ? html_entity_decode($abstract) : "" ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="keywords" class="control-label text-navy">Keywords</label>
                        <textarea rows="3" name="keywords" id="keywords" placeholder="Enter Keywords with comma , as separator" class="form-control form-control-border summernote-list-only"><?= isset($keywords) ? html_entity_decode($keywords) : "" ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="members" class="control-label text-navy">Author(s)</label>
                        <textarea rows="3" name="members" id="members" placeholder="Enter authors with semicolon ; as separator" class="form-control form-control-border summernote-list-only" required><?= isset($members) ? html_entity_decode($members) : "" ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="pdf" class="control-label text-muted">Document (PDF File Only)</label>
                        <input type="file" id="pdf" name="pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                    </div>
                    
                    <div class="form-group text-center">
                        <button class="btn btn-default bg-navy btn-flat">Submit</button>
                        <a href="./?page=profile-adviser" class="btn btn-light border btn-flat">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ol', 'ul', 'paragraph', 'align']], // Added 'align' for text alignment
            ['view', ['undo', 'redo']]
        ]
    });
    
    $('#archive-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        $(".pop-msg").remove();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_archive",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert("An error occurred while saving the data");
                end_loader();
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    location.href = "./?page=view_archive&id=" + resp.id;
                } else {
                    alert(resp.msg || "An error occurred while saving the data");
                }
                end_loader();
            }
        });
    });
});
</script>