<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
    /*
    if($user->num_rows > 0){
        $meta = $user->fetch_assoc();  // Use fetch_assoc() instead of looping through fetch_array()
    }
    */
}
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
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
				<div class="form-group col-6">
                    <span class="text-navy"><medium><b>Gender</b></medium></span>
                    <div class="form-group col-auto">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="genderMale" name="gender" value="Male" required <?php echo (isset($meta['gender']) && $meta['gender'] == 'Male') ? 'checked' : ''; ?>>
                            <label for="genderMale" class="custom-control-label">Male</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="genderFemale" name="gender" value="Female" <?php echo (isset($meta['gender']) && $meta['gender'] == 'Female') ? 'checked' : ''; ?>>
                            <label for="genderFemale" class="custom-control-label">Female</label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-6">
                    <span class="text-navy"><medium><b>College</b></medium></span>
                        <select name="college_id" id="college_id" class="form-control form-control-border select2" data-placeholder="Select College" required>
                            <option value=""></option>
                            <?php 
                                $college = $conn->query("SELECT * FROM `college_list` WHERE status = 1 ORDER BY `name` ASC");
                                while ($row = $college->fetch_assoc()):
                            ?>
                            <option value="<?= $row['id'] ?>" <?= isset($meta['college_id']) && $meta['college_id'] == $row['id'] ? 'selected' : '' ?>><?= ucwords($row['name']) ?> - <?= ucwords($row['description']) ?></option>
                            <?php endwhile; ?>
                        </select>
                </div>
                <div class="form-group col-6">
                    <span class="text-navy"><medium><b>Department</b></medium></span>
                        <select name="department_id" id="department_id" class="form-control form-control-border select2" data-placeholder="Select Department" required>
                            <option value="" disabled></option>
                            <?php 
                                $department = $conn->query("SELECT * FROM `department_list` WHERE status = 1 ORDER BY `name` ASC");
                                $dept_arr = [];
                                while ($row = $department->fetch_assoc()) {
                                    $row['name'] = ucwords($row['name'] . " - " . $row['description']);
                                    $dept_arr[$row['college_id']][] = $row;
                                }

                                foreach ($dept_arr as $college_id => $departments): 
                                    foreach ($departments as $dept):
                            ?>
                            <option value="<?= $dept['id'] ?>" <?= isset($meta['department_id']) && $meta['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= $dept['name'] ?></option>
                            <?php 
                                    endforeach; 
                                endforeach; 
                            ?>
                        </select>
                </div>
                <div class="form-group col-6">
                    <span class="text-navy"><medium><b>Program</b></medium></span>
                        <select name="curriculum_id" id="curriculum_id" class="form-control form-control-border select2" data-placeholder="Select Program" required>
                            <option value="" disabled>Select Department First</option>
                            <?php 
                                $curriculum = $conn->query("SELECT * FROM `curriculum_list` WHERE status = 1 ORDER BY `name` ASC");
                                $cur_arr = [];
                                while ($row = $curriculum->fetch_assoc()) {
                                    $row['name'] = ucwords($row['name'] . " - " . $row['description']);
                                    $cur_arr[$row['department_id']][] = $row;
                                }

                                foreach ($cur_arr as $department_id => $programs): 
                                    foreach ($programs as $program):
                            ?>
                            <option value="<?= $program['id'] ?>" <?= isset($meta['curriculum_id']) && $meta['curriculum_id'] == $program['id'] ? 'selected' : '' ?>><?= $program['name'] ?></option>
                            <?php 
                                    endforeach; 
                                endforeach; 
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
				<input type="hidden" name="type" value="<?= isset($meta['type']) ? $meta['type'] : '2' ?>">
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
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>
<script>
    var cur_arr = $.parseJSON('<?= json_encode($cur_arr) ?>');
    var dept_arr = $.parseJSON('<?= json_encode($dept_arr) ?>');
    $(document).ready(function(){
        end_loader();
    $('.select2').select2({
        width:"100%"
    })
    $('#college_id').change(function(){
        var cid = $(this).val()
        $('#department_id').html("")
        if(!!dept_arr[cid]){
            Object.keys(dept_arr[cid]).map(k=>{
                var opt = $("<option>")
                    opt.attr('value',dept_arr[cid][k].id)
                    opt.text(dept_arr[cid][k].name)
                $('#department_id').append(opt)
            })
        }
        $('#department_id').trigger("change")
    })
    $('#department_id').change(function(){
        var did = $(this).val()
        $('#curriculum_id').html("")
        if(!!cur_arr[did]){
            Object.keys(cur_arr[did]).map(k=>{
                var opt = $("<option>")
                    opt.attr('value',cur_arr[did][k].id)
                    opt.text(cur_arr[did][k].name)
                $('#curriculum_id').append(opt)
            })
        }
        $('#curriculum_id').trigger("change")
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