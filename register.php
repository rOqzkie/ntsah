<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php require_once('inc/header.php') ?>
<body class="hold-transition ">
    <script>
        start_loader()
    </script>
    <style>
    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        background-color: #D6E4F0;
    }
    .login-title {
        text-shadow: 2px 2px black;
        text-align: center;
    }
    #login {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .card.card-outline {
        width: 100%;
        max-width: 1000px;
        margin: auto;
    }
    .registration-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 20px;
    }
    .error-message {
        font-size: 12px;
        color: red;
        margin-top: 5px;
    }
    .form-group .input-group-append button {
        border: 1px solid #ced4da;
        background-color: white;
    }
    @media (max-width: 768px) {
        .card-header h5 {
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        #logo-img {
            margin-bottom: 15px;
            width: 100px;
        }
    }
    </style>
<div class="h-100 d-flex align-items-center w-100" id="login">
    <div class="w-100 d-flex justify-content-center align-items-center h-100 text-navy">
        <div class="card card-outline card-primary rounded-0 shadow col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="card-header">
                <h5 class="card-title text-center text-dark"><b>Student Registration Form</b></h5>
            </div>
            <div class="card-body">
                <form action="" id="registration-form">
                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Student ID No.</b></medium></span>
                                <input type="text" name="studid" id="studid" autofocus placeholder="Student ID No." class="form-control form-control-border" required>
                            </div>
                            <input type="hidden" name="type" value="2">
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Email</b></medium></span>
                                <input type="email" name="email" id="email" placeholder="Email" class="form-control form-control-border" onblur="validateEmail(this)" required>
                                <span id="email-error" class="error-message"></span>
                            </div>
                            <div class="form-group">
                                <span class="text-navy"><medium><b>College</b></medium></span>
                                <select name="college_id" id="college_id" class="form-control form-control-border select2" data-placeholder="Select College" required>
                                    <option value=""></option>
                                        <?php 
                                            $college = $conn->query("SELECT * FROM `college_list` where status = 1 order by `name` asc");
                                                while($row = $college->fetch_assoc()):
                                        ?>
                                    <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?> - <?= ucwords($row['description']) ?></option>
                                        <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Department</b></medium></span>
                                <select name="department_id" id="department_id" class="form-control form-control-border select2" data-placeholder="Select Department" required>
                                    <option value="" disabled></option>
                                        <?php 
                                            $department = $conn->query("SELECT * FROM `department_list` where status = 1 order by `name` asc");
                                            $dept_arr = [];
                                                while($row = $department->fetch_assoc()){
                                                    $row['name'] = ucwords($row['name']." - ".$row['description']);
                                                    $dept_arr[$row['college_id']][] = $row;
                                                }
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Program</b></medium></span>
                                <select name="curriculum_id" id="curriculum_id" class="form-control form-control-border select2" data-placeholder="Select Program" required>
                                    <option value="" disabled selected>Select Deparment First</option>
                                        <?php 
                                            $curriculum = $conn->query("SELECT * FROM `curriculum_list` where status = 1 order by `name` asc");
                                            $cur_arr = [];
                                                while($row = $curriculum->fetch_assoc()){
                                                    $row['name'] = ucwords($row['name']." - ".$row['description']);
                                                    $cur_arr[$row['department_id']][] = $row;
                                                }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Name</b></medium></span>
                                <input type="text" name="firstname" id="firstname" autofocus placeholder="Firstname" class="form-control form-control-border" required>
                                <input type="text" name="middlename" id="middlename" placeholder="Middlename (optional)" class="form-control form-control-border">
                                <input type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control form-control-border" required>
                            </div>
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Gender</b></medium></span>
                                <div class="form-group col-auto d-flex">
                                    <div class="custom-control custom-radio me-5">
                                        <input class="custom-control-input" type="radio" id="genderMale" name="gender" value="Male" required checked>
                                        <label for="genderMale" class="custom-control-label fw-normal">Male</label>&emsp;
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="genderFemale" name="gender" value="Female">
                                        <label for="genderFemale" class="custom-control-label fw-normal">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Password</b></medium></span>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-border" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" toggle="#password">
                                            👁
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="text-navy"><medium><b>Confirm Password</b></medium></span>
                                <div class="input-group">
                                    <input type="password" id="cpassword" placeholder="Confirm Password" class="form-control form-control-border" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" toggle="#cpassword">
                                            👁
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Checkbox -->
                        <div class="col-12 form-group mt-3">
                            <input type="checkbox" id="nda-read">
                            <label for="nda-read">
                                I have read and agree to the 
                                <a href="nda.php" target="_blank">Non-Disclosure Agreement</a>
                            </label>
                        </div>
                        <br>
                        <!-- Register Button -->
                        <div class="col-md-12 text-left">
                            <button id="register-btn" class="btn btn-default bg-success btn-flat w-100" disabled>Register</button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <b>Already have an account?</b><a href="login.php" class="btn btn-info btn-block">Log In</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
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
   // Registration Form Submit
    $('#registration-form').submit(function(e) {
        e.preventDefault();
        
        var _this = $(this);
        $(".pop-msg").remove();
        $('#password, #cpassword').removeClass("is-invalid");

        // Create error message container
        var el = $("<div>").addClass("alert pop-msg my-2").hide();

        // Password match check
        if ($("#password").val() !== $("#cpassword").val()) {
            el.addClass("alert-danger").text("Password does not match.");
            $('#password, #cpassword').addClass("is-invalid");
            $('#cpassword').after(el);
            el.show('slow');
            return false;
        }

        start_loader();

        $.ajax({
            url: _base_url_ + "classes/Users.php?f=save_student",
            method: 'POST',
            data: _this.serialize(),
            dataType: 'json',
            error: err => {
                console.error("AJAX error:", err);
                el.text("An error occurred while saving the data.")
                  .addClass("alert-danger");
                _this.prepend(el);
                el.show('slow');
                end_loader();
            },
            success: function(resp) {
    console.log("Server response:", resp); // 👈 Add this line
    if (resp.status === 'success') {
        const email = encodeURIComponent($('#email').val());
        window.location.href = "./verify_otp.php?email=" + email;
    } else {
        el.text(resp.msg || "An error occurred while saving the data.")
          .addClass("alert-danger");
        _this.prepend(el);
        el.show('slow');
    }
    end_loader();
    $('html, body').animate({ scrollTop: 0 }, 'fast');
}
        });
    });
  });

  function validateEmail(input) {
    // Regular expression for @nemsu.edu.ph domain
    const pattern = /^[a-zA-Z0-9._%+-]+@nemsu\.edu\.ph$/;

    // Get the trimmed email input value
    const email = input.value.trim();

    // Get the error message span
    const errorSpan = document.getElementById("email-error");

    // Validate email
    if (pattern.test(email)) {
        errorSpan.textContent = ""; // Clear error message
        input.style.border = "2px solid green"; // Indicate success
    } else {
        errorSpan.textContent = "Invalid email! Use @nemsu.edu.ph";
        errorSpan.style.color = "red";
        input.style.border = "2px solid red";
        input.value = ""; // Clear input
    }
}
document.addEventListener("DOMContentLoaded", function() {
    const ndaReadCheckbox = document.getElementById("nda-read");
    //const ndaAgreeCheckbox = document.getElementById("nda-agree");
    const registerButton = document.getElementById("register-btn");

    function toggleRegisterButton() {
        // Enable button only if both checkboxes are checked
        registerButton.disabled = !(ndaReadCheckbox.checked);
        //registerButton.disabled = !(ndaReadCheckbox.checked && ndaAgreeCheckbox.checked);
    }

    // Listen for changes on both checkboxes
    ndaReadCheckbox.addEventListener("change", toggleRegisterButton);
    //ndaAgreeCheckbox.addEventListener("change", toggleRegisterButton);
});
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".toggle-password").forEach(button => {
        button.addEventListener("click", function() {
            const input = document.querySelector(this.getAttribute("toggle"));
            if (input.type === "password") {
                input.type = "text";
                this.textContent = "🙈"; // Hide icon
            } else {
                input.type = "password";
                this.textContent = "👁"; // Show icon
            }
        });
    });
});
</script>