<?php
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $stmt = $conn->prepare("SELECT * FROM `archive_list` WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows) {
        foreach ($result->fetch_array(MYSQLI_ASSOC) as $k => $v) {
            $$k = $v;
        }
    }

    if (isset($adviser_id) && $adviser_id != $_settings->userdata('id')) {
        echo "<script> alert('You don\'t have access to this page'); location.replace('./'); </script>";
        exit;
    }
}
?>

<style>
.form-group label,
label.control-label,
.form-check-label {
    display: block;
    text-align: left !important;
    margin-bottom: 0.5rem;
}

.form-check {
    text-align: left !important;
}

input.form-control,
select.form-control,
textarea.form-control {
    text-align: left;
}

/* Select2 specific adjustments */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    text-align: left;
}

.select2-results__option {
    text-align: left;
}

/* Responsive fix */
@media (max-width: 767.98px) {
    .select2-container {
        width: 100% !important;
    }
}
</style>
<!--
<div class="container mt-3">
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
</div>
-->
<div class="content py-4">
    <div class="card card-outline">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title text-white">
                <?= isset($id) ? "<b>Update Archive-{$archive_code} Details</b>" : "<b>Submit Thesis | Feasibility Study</b>" ?>
            </h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="archive-form">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">
                    <input type="hidden" name="title" id="extracted_title">
                    <input type="hidden" name="abstract" id="extracted_abstract">
                    <input type="hidden" name="keywords" id="extracted_keywords">
                    <input type="hidden" name="authors" id="extracted_authors">
                    <input type="hidden" name="year" id="extracted_year">

                    <div class="row">
                        <!-- Project Type -->
                        <div class="col-md-6 mb-3">
                            <label class="control-label text-dark fw-bold mb-2">Academic Project Type</label>
                            <div class="d-flex align-items-center ps-2">
                                <div class="form-check" style="margin-right: 20px">
                                    <input class="form-check-input" type="radio" name="type" id="thesis" value="1" <?= $type == "1" ? 'checked' : '' ?> required>
                                    <label class="form-check-label text-dark ms-1" for="thesis">Thesis</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="feasibility" value="2" <?= $type == "2" ? 'checked' : '' ?> required>
                                    <label class="form-check-label text-dark ms-1" for="feasibility">Feasibility Study</label>
                                </div>
                            </div>
                        </div>

                        <!-- Discipline -->
                        <div class="col-md-6 mb-3">
                            <label class="control-label text-navy">Research Discipline</label>
                            <select name="discipline_id" id="discipline_id" class="form-control select2" data-placeholder="Select Research Discipline" required>
                                <option value=""></option>
                                <?php 
                                    $college = $conn->query("SELECT * FROM `discipline_list` WHERE status = 1 ORDER BY `name` ASC");
                                        while($row = $college->fetch_assoc()):
                                ?>
                                <option value="<?= $row['id'] ?>" <?= (isset($discipline_id) && $discipline_id == $row['id']) ? "selected" : "" ?>>
                                    <?= ucwords($row['name']) ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Adviser -->
                        <div class="col-md-6 mb-3">
                            <label for="thesis_adviser" class="control-label text-navy">Thesis Adviser</label>
                            <input type="text" name="thesis_adviser" id="thesis_adviser" class="form-control" placeholder="Enter Thesis Adviser" value="<?= isset($thesis_adviser) ? $thesis_adviser : "" ?>" required>
                        </div>

                        <!-- PDF Upload -->
                        <div class="col-md-6 mb-3">
                            <label for="pdf" class="control-label text-navy">Document (PDF File Only)</label>
                            <input type="file" id="pdf" name="pdf" class="form-control" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3" id="metadata-preview" style="display:none;">
                        <h6 class="mb-1"><strong>Preview of Extracted Metadata:</strong></h6>
                        <p><strong>Title:</strong> <span id="preview_title"></span></p>
                        <p><strong>Abstract:</strong> <span id="preview_abstract"></span></p>
                        <p><strong>Keywords:</strong> <span id="preview_keywords"></span></p>
                        <p><strong>Authors:</strong> <span id="preview_authors"></span></p>
                        <p><strong>Year:</strong> <span id="preview_year"></span></p>
                    </div>
                    <div class="text-center mt-3">
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
            ['para', ['ol', 'ul', 'paragraph', 'align']],
            ['view', ['undo', 'redo']]
        ]
    });

    $('.select2').select2({
        width: "100%"
    });

    // Form submission handler
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
                if (resp.status === 'success') {
                    location.href = "./?page=view_archive&id=" + resp.id;
                } else {
                    alert(resp.msg || "An error occurred while saving the data");
                }
                end_loader();
            }
        });
    });

    // PDF file change handler for metadata extraction
    $('#pdf').on('change', function(e) {
        var file = e.target.files[0];
        if (!file || file.type !== 'application/pdf') {
            alert("Please upload a valid PDF file.");
            return;
        }

        var formData = new FormData();
        formData.append('pdf', file);

        start_loader();

        $.ajax({
            url: _base_url_ + "extract_metadata.php",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
    try {
        let data = res;

        $('#extracted_title').val(data.title || '');
        $('#extracted_abstract').val(data.abstract || '');
        $('#extracted_keywords').val((data.keywords || []).join(', '));
        $('#extracted_authors').val((data.authors || []).join(', '));
        $('#extracted_year').val(data.year || '');

        $('#preview_title').text(data.title || 'N/A');
        $('#preview_abstract').text(data.abstract || 'N/A');
        $('#preview_keywords').text((data.keywords || []).join(', '));
        $('#preview_authors').text((data.authors || []).join(', '));
        $('#preview_year').text(data.year || 'N/A');

        $('#metadata-preview').slideDown();
    } catch (err) {
        alert("Failed to extract metadata. Please ensure the PDF is valid.");
        console.error(err);
    }
    end_loader();
},
            error: function(xhr) {
                alert("Error uploading PDF:\n" + xhr.responseText);
                end_loader();
            }
        });
    });
});
</script>