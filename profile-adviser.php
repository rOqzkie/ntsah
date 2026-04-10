<?php 
$user = $conn->query("SELECT s.*, col.name AS college, d.name AS department, c.name AS curriculum, CONCAT(lastname, ', ', firstname, ' ', middlename) AS fullname, p.name AS position 
                      FROM adviser_list s 
                      INNER JOIN college_list col ON s.college_id = col.id 
                      INNER JOIN department_list d ON s.department_id = d.id 
                      INNER JOIN curriculum_list c ON s.curriculum_id = c.id 
                      INNER JOIN position_list p ON s.position_id = p.id 
                      WHERE s.id = '{$_settings->userdata('id')}'");

foreach($user->fetch_array() as $k => $v){
    $$k = $v;
}
?>

<style>
    .student-img {
        object-fit: cover;
        height: 180px;
        width: 180px;
        border: 5px solid #0d6efd;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

<div class="content py-4">
    <div class="card shadow-lg rounded-5 border-0">
        <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top">
            <h5 class="mb-0"><i class="fa fa-user-tie me-2"></i><strong>Your Adviser Dashboard</strong></h5>
            <div class="d-flex flex-wrap gap-2 ms-auto">
                <a href="./?page=my_library" class="btn btn-outline-light btn-sm" data-bs-toggle="tooltip" title="View your saved libraries">📙 My Libraries</a>&nbsp;
                <a href="./?page=my_archives" class="btn btn-outline-light btn-sm" data-bs-toggle="tooltip" title="View your uploaded archives"><i class="fa fa-archive me-1"></i>My Archive/s</a>&nbsp;
                <a href="./?page=manage_account-adviser" class="btn btn-outline-light btn-sm" data-bs-toggle="tooltip" title="Edit your account information"><i class="fa fa-edit me-1"></i>Update Account</a>
            </div>
        </div>

        <div class="card-body bg-light rounded-bottom">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile Info</button>
                </li>
                <!--
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">Recent Activity</button>
                </li>
                -->
                <!--
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="photo-tab" data-bs-toggle="tab" data-bs-target="#photo" type="button" role="tab">Change Profile Picture</button>
                </li>
                -->
            </ul>

            <div class="tab-content" id="profileTabsContent">
                <!-- Profile Info Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-4 col-md-5 text-center">
                            <img src="<?= validate_image($avatar) ?>" alt="Adviser Image" class="img-fluid rounded-circle student-img">
                            <p class="mt-3 fw-bold text-primary"><?= ucwords($fullname) ?></p>
                        </div>
                        <div class="col-lg-8 col-md-7 text-left">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-dark">Gender:</dt>
                                <dd class="col-sm-7"><?= ucwords($gender) ?></dd>

                                <dt class="col-sm-5 text-dark">Email Address:</dt>
                                <dd class="col-sm-7"><?= $email ?></dd>

                                <dt class="col-sm-5 text-dark">Academic Rank:</dt>
                                <dd class="col-sm-7"><?= $position ?></dd>

                                <dt class="col-sm-5 text-dark">College:</dt>
                                <dd class="col-sm-7"><?= ucwords($college) ?></dd>

                                <dt class="col-sm-5 text-dark">Department:</dt>
                                <dd class="col-sm-7"><?= ucwords($department) ?></dd>

                                <dt class="col-sm-5 text-dark">Program:</dt>
                                <dd class="col-sm-7"><?= ucwords($curriculum) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Tab -->
                <div class="tab-pane fade" id="activity" role="tabpanel">
                    <ul class="list-group list-group-flush">
                        <!-- Sample activities, replace with DB logs -->
                        <li class="list-group-item"><i class="fa fa-file-upload text-success me-2"></i>Uploaded thesis titled <strong>“AI in Agriculture”</strong></li>
                        <li class="list-group-item"><i class="fa fa-edit text-primary me-2"></i>Updated account information</li>
                        <li class="list-group-item"><i class="fa fa-comments text-warning me-2"></i>Commented on student proposal</li>
                    </ul>
                </div>

                <!-- Change Profile Picture Tab -->
                <div class="tab-pane fade" id="photo" role="tabpanel">
                    <form action="update_profile_picture.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Upload New Profile Picture</label>
                            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload me-1"></i>Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS for Tabs and Tooltips -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
</script>