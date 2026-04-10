<?php 
$sql = "SELECT s.*, d.name as department, c.name as curriculum, CONCAT(lastname,', ',firstname,' ',middlename) as fullname, p.name as position 
        FROM adviser_list s 
        INNER JOIN department_list d ON s.department_id = d.id 
        INNER JOIN curriculum_list c ON s.curriculum_id = c.id 
        INNER JOIN position_list p ON s.position_id = p.id 
        WHERE s.id = '{$_settings->userdata('id')}'";

$user = $conn->query($sql);

if (!$user) {
    die("Query failed: " . $conn->error . "<br>SQL: " . $sql);
}

$data = $user->fetch_array();
if ($data) {
    foreach ($data as $k => $v) {
        $$k = $v;
    }
} else {
    die("No adviser found with ID: {$_settings->userdata('id')}");
}
?>
<style>
    .student-img{
		object-fit:scale-down;
		object-position:center center;
        height:200px;
        width:200px;
	}
	.container, .content, .form-group, .card-title, label, input, button, small {
    text-align: left !important;
</style>
<div class="container mt-3">
    <!--
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
    -->
</div>
<div class="content py-4">
    <div class="card card-outline shadow rounded-0">
        <div class="card-header rounded-5 bg-primary text-white d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title text-white"><i class="fa fa-edit"></i><b> Update Details</b></h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="update-form">
                    <input type="hidden" name="id" value="<?= $_settings->userdata('id') ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="firstname" class="control-label text-navy">FirstName</label>
                                <input type="text" name="firstname" id="firstname" autofocus placeholder="Firstname" class="form-control form-control-border" value="<?= isset($firstname) ?$firstname : "" ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="middlename" class="control-label text-navy">MiddleName</label>
                                <input type="text" name="middlename" id="middlename" placeholder="Middlename (optional)" class="form-control form-control-border" value="<?= isset($middlename) ?$middlename : "" ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="lastname" class="control-label text-navy">LastName</label>
                                <input type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control form-control-border" value="<?= isset($lastname) ?$lastname : "" ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="position" class="control-label text-navy">Academic Rank</label>
                                <select name="position_id" id="position_id" class="form-control form-control-border" required>
                                    <?php $positions = $conn->query("SELECT * FROM position_list WHERE status = 1 ORDER BY name ASC"); ?>
                                    <option value="" disabled selected>Select Academic Rank</option>
                                    <?php while ($row = $positions->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= (isset($position_id) && $position_id == $row['id']) ? 'selected' : '' ?>>
                                    <?= $row['name'] ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <small class='text-muted'>Leave the Academic Rank blank if it is your current rank.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-auto">
                        <label for="" class="control-label text-navy">Gender</label>
                        </div>
                        <div class="form-group col-auto">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="genderMale" name="gender" value="Male" required  <?= isset($gender) && $gender == "Male" ? "checked" : "" ?>>
                                <label for="genderMale" class="custom-control-label text-dark">Male</label>
                            </div>
                        </div>
                        <div class="form-group col-auto">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="genderFemale" name="gender" value="Female" <?= isset($gender) && $gender == "Female" ? "checked" : "" ?>>
                                <label for="genderFemale" class="custom-control-label text-dark">Female</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email" class="control-label text-navy">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email" class="form-control form-control-border" required value="<?= isset($email) ?$email : "" ?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label text-navy">New Password</label>
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-border">
                            </div>

                            <div class="form-group">
                                <label for="cpassword" class="control-label text-navy">Confirm New Password</label>
                                <input type="password" id="cpassword" placeholder="Confirm Password" class="form-control form-control-border">
                            </div>
                            <small class='text-muted'>Leave the New Password and Confirm New Password Blank if you don't wish to change your password.</small>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="img" class="control-label text-muted">Choose Image</label>
                                <input type="file" id="img" name="img" class="form-control form-control-border" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
                            </div>

                            <div class="form-group text-center">
                                <img src="<?= validate_image(isset($avatar) ? $avatar : "") ?>" alt="My Avatar" id="cimg" class="img-fluid student-img bg-gradient-dark border rounded-circle">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group text-dark">
                                <label for="oldpassword">Please Enter your Current Password</label>
                                <input type="password" name="oldpassword" id="oldpassword" placeholder="Current Password" class="form-control form-control-border" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <button class="btn btn-default bg-navy btn-flat"> Update</button>
                                <a href="./?page=profile-adviser" class="btn btn-light border btn-flat"> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
	}
    $(function(){
        // Update Form Submit
        $('#update-form').submit(function(e){
            e.preventDefault()
            var _this = $(this)
                $(".pop-msg").remove()
                $('#password, #cpassword').removeClass("is-invalid")
            var el = $("<div>")
                el.addClass("alert pop-msg my-2")
                el.hide()
            if($("#password").val() != $("#cpassword").val()){
                el.addClass("alert-danger")
                el.text("Password does not match.")
                $('#password, #cpassword').addClass("is-invalid")
                $('#cpassword').after(el)
                el.show('slow')
                return false;
            }
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=save_adviser",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType:'json',
                error:err=>{
                    console.log(err)
                    el.text("An error occured while saving the data")
                    el.addClass("alert-danger")
                    _this.prepend(el)
                    el.show('slow')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href= "./?page=profile-adviser"
                    }else if(!!resp.msg){
                        el.text(resp.msg)
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }else{
                        el.text("An error occured while saving the data")
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0},'fast')
                }
            })
        })
    })
</script>