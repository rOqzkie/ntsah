<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
    }
</style>

<div class="card card-outline">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>List of Student(s)</h5>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Email</th>
                        <!--<th>Verified NEMSU Workspace</th>-->
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        // FIX: Select fields properly instead of using "*"
                        $qry = $conn->query("SELECT 
                                                s.id, 
                                                s.avatar, 
                                                s.firstname, 
                                                s.lastname, 
                                                s.middlename, 
                                                s.email, 
                                                s.status,
                                                p.name as program 
                                             FROM 
                                                student_list s 
                                             INNER JOIN 
                                                curriculum_list p 
                                             ON 
                                                s.curriculum_id = p.id 
                                             ORDER BY 
                                                s.status, s.lastname, s.firstname ASC");

                        while($row = $qry->fetch_assoc()):
                            $full_name = ucwords($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename']);
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td class="text-center">
                            <img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar">
                        </td>
                        <td><?php echo $full_name ?></td>
                        <td><?php echo ucwords($row['program']) ?></td>
                        <td><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
                        <!--
                        <td class="text-center">
                            <?php if($row['is_verified'] == 1): ?>
                                <span class="badge badge-pill badge-success">Already Verified</span>
                            <?php else: ?>
                                <span class="badge badge-pill badge-danger">Pending Verification</span>
                            <?php endif; ?>
                        </td>
                        -->
                        <td class="text-center">
                            <?php if($row['status'] == 1): ?>
                                <span class="badge badge-pill badge-success">Verified</span>
                            <?php else: ?>
                                <span class="badge badge-pill badge-primary">For Verification</span>
                            <?php endif; ?>
                        </td>
                        <td align="center">
                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                    <span class="fa fa-eye text-dark"></span> View
                                </a>
                                <div class="dropdown-divider"></div>
                                <?php if($row['status'] != 1 ) : ?>
                                <a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['email'] ?>">
                                    <span class="fa fa-check text-primary"></span> Verify
                                </a>
                                <div class="dropdown-divider"></div>
                                <?php endif; ?>
                                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['email'] ?>">
                                    <span class="fa fa-trash text-danger"></span> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.delete_data').click(function(){
        _conf("Are you sure to delete <b>" + $(this).attr('data-name') + "</b> from Student List permanently?", "delete_user", [$(this).attr('data-id')]);
    });

    $('.verify_user').click(function(){
        _conf("Are you sure to verify <b>" + $(this).attr('data-name') + "</b>?", "verify_user", [$(this).attr('data-id')]);
    });

    $('.view_details').click(function(){
        uni_modal('Student Details', "students/view_details.php?id=" + $(this).attr('data-id'), 'mid-large');
    });

    $('.table').dataTable({
		language: {
			searchPlaceholder: "Search students...",
			search: ""
		}
	});
});

function delete_user(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Users.php?f=delete_student",
        method: "POST",
        data: {id: id},
        dataType: "json",
        error: err => {
            console.log(err);
            alert_toast("An error occurred.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.reload();
            } else {
                alert_toast("An error occurred.", 'error');
                end_loader();
            }
        }
    });
}

function verify_user(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Users.php?f=verify_student",
        method: "POST",
        data: {id: id},
        dataType: "json",
        error: err => {
            console.log(err);
            alert_toast("An error occurred.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                alert_toast("Student Verified. Email Sent!", 'success');
                location.reload();
            } else {
                alert_toast("An error occurred.", 'error');
                end_loader();
            }
        }
    });
}
</script>