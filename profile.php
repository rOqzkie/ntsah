<?php 
$user = $conn->query("SELECT s.*,col.name as college, d.name as department, c.name as curriculum,CONCAT(lastname,', ',firstname,' ',middlename) as fullname FROM student_list s inner join college_list col on s.college_id = col.id inner join department_list d on s.department_id = d.id inner join curriculum_list c on s.curriculum_id = c.id where s.id ='{$_settings->userdata('id')}'");
foreach($user->fetch_array() as $k =>$v){
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
<div class="container mt-3">
    <!--
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
    -->
</div>
<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title text-dark"><b>Your Information:</b></h5>
            <div class="card-tools">
                <a href="./?page=my_library" class="btn btn-default bg-info btn-flat">📙 My Libraries</a>
                <a href="./?page=my_bookmarks" class="btn btn-default bg-secondary btn-flat"><i class="fa fa-bookmark"></i> My Bookmark/s</a>
                <a href="./?page=manage_account" class="btn btn-default bg-navy btn-flat"><i class="fa fa-edit"></i> Update Account</a>
            </div>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-4 col-md-5 text-center">
                            <img src="<?= validate_image($avatar) ?>" alt="Adviser Image" class="img-fluid rounded-circle student-img">
                            <p class="mt-3 fw-bold text-primary"><?= ucwords($fullname) ?></p>
                        </div>
                        <div class="col-lg-8 col-md-7 text-left">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-dark">Gender:</dt>
                                <dd class="col-sm-7 text-dark"><?= ucwords($gender) ?></dd>

                                <dt class="col-sm-5 text-dark">Email Address:</dt>
                                <dd class="col-sm-7 text-dark"><?= $email ?></dd>

                                <dt class="col-sm-5 text-dark">College:</dt>
                                <dd class="col-sm-7 text-dark"><?= ucwords($college) ?></dd>

                                <dt class="col-sm-5 text-dark">Department:</dt>
                                <dd class="col-sm-7 text-dark"><?= ucwords($department) ?></dd>

                                <dt class="col-sm-5 text-dark">Program:</dt>
                                <dd class="col-sm-7 text-dark"><?= ucwords($curriculum) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>