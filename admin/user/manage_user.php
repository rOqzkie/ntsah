<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
// Fetch data for select options
$colleges = $conn->query("SELECT * FROM college_list ORDER BY name ASC");
$departments = $conn->query("SELECT * FROM department_list ORDER BY name ASC");
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
				<div class="form-group col-6">
					<label for="name">First Name</label>
					<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname']: '' ?>" required>
				</div>
				<div class="form-group col-6">
					<label for="name">Middle Name</label>
					<input type="text" name="middlename" id="middlename" class="form-control" value="<?php echo isset($meta['middlename']) ? $meta['middlename']: '' ?>">
				</div>
				<div class="form-group col-6">
					<label for="name">Last Name</label>
					<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required>
				</div>
				<div class="form-group col-6">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
				</div>
				<!-- College Selection -->
				<!--
                <div class="form-group col-6">
                    <label for="college_id">College</label>
                    <select name="college_id" id="college_id" class="custom-select select2" required>
                        <option value="">Select College</option>
                        <?php while($row = $colleges->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo isset($meta['college_id']) && $meta['college_id'] == $row['id'] ? 'selected' : ''; ?>>
                                <?php echo $row['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                -->
                <div class="form-group col-6">
                    <span class="text-navy"><medium><b>College</b></medium></span>
                    <select name="college_id" id="college_id" class="form-control form-control-border select2" data-placeholder="Select College" required>
                        <option value=""></option>
                        <?php 
                            $college = $conn->query("SELECT * FROM `college_list` WHERE status = 1 ORDER BY `name` ASC");
                            while ($row = $college->fetch_assoc()):
                        ?>
                            <option value="<?= $row['id'] ?>" 
                                <?= isset($meta['college_id']) && $meta['college_id'] == $row['id'] ? 'selected' : '' ?>>
                                <?= ucwords($row['name']) ?> - <?= ucwords($row['description']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Department Selection -->
                <!--
                <div class="form-group col-6">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="custom-select select2" required>
                        <option value="">Select Department</option>
                        <?php while($row = $departments->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo isset($meta['department_id']) && $meta['department_id'] == $row['id'] ? 'selected' : ''; ?>>
                                <?php echo $row['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                -->
                <div class="form-group col-6">
                    <span class="text-navy"><medium><b>Department</b></medium></span>
                    <select name="department_id" id="department_id" class="form-control form-control-border select2" data-placeholder="Select Department" required>
                        <option value=""></option>
                        <?php 
                            $department = $conn->query("SELECT * FROM `department_list` WHERE status = 1 ORDER BY `name` ASC");
                            $dept_arr = [];
                            while ($row = $department->fetch_assoc()) {
                                $row['name'] = ucwords($row['name']." - ".$row['description']);
                                $dept_arr[$row['college_id']][] = $row;
                            }
                        ?>
                    </select>
                </div>
				<div class="form-group col-6">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" <?php echo isset($meta['id']) ? "": 'required' ?>>
                    <?php if(isset($_GET['id'])): ?>
					<small class="text-info"><i>Leave this blank if you dont want to change the password.</i></small>
                    <?php endif; ?>
				</div>
				<input type="hidden" name="type" id="type" value="<?php echo isset($meta['type']) ? $meta['type']: '2' ?>">
				<div class="form-group col-6">
					<label for="" class="control-label">Avatar</label>
					<div class="custom-file">
		              <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
		              <label class="custom-file-label" for="customFile">Choose file</label>
		            </div>
				</div>
				<div class="form-group col-6 d-flex justify-content-center">
					<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Save</button>
					<a class="btn btn-sm btn-secondary" href="./?page=user/list">Cancel</a>
				</div>
			</div>
		</div>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
    var dept_arr = $.parseJSON('<?= json_encode($dept_arr) ?>'); // Store all departments in JS

    $(document).ready(function () {
        $('.select2').select2({ width: "100%" }); // Initialize select2

        function populateDepartments(college_id, selected_dept_id = null) {
            var $deptSelect = $('#department_id');
            $deptSelect.html('<option value="">Select Department</option>'); // Reset dropdown

            if (dept_arr[college_id]) {
                Object.keys(dept_arr[college_id]).map(k => {
                    var opt = $("<option>")
                        .attr('value', dept_arr[college_id][k].id)
                        .text(dept_arr[college_id][k].name);
                    
                    // Preselect department if editing user
                    if (selected_dept_id && dept_arr[college_id][k].id == selected_dept_id) {
                        opt.attr('selected', 'selected');
                    }

                    $deptSelect.append(opt);
                });
            }
            $deptSelect.trigger("change"); // Trigger update
        }

        // When a college is selected, update department dropdown
        $('#college_id').change(function () {
            populateDepartments($(this).val());
        });

        // Auto-select department when editing user
        <?php if (isset($meta['college_id']) && isset($meta['department_id'])): ?>
            populateDepartments('<?= $meta['college_id'] ?>', '<?= $meta['department_id'] ?>');
        <?php endif; ?>
    });
	$(function(){
		$('.select2').select2({
			width:'resolve'
		})
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage-user').submit(function(e){
		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					location.href = './?page=user/list';
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					$("html, body").animate({ scrollTop: 0 }, "fast");
				}
                end_loader()
			}
		})
	})
</script>