<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adviser Login</title>
    <?php require_once('inc/header.php') ?>
    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            /*background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");*/
            background-color: #ECEFF1;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .login-container {
            display: flex;
            flex-wrap: wrap;
            max-width: 900px;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .login-left, .login-right {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .login-left {
            background: rgba(0, 0, 0, 0.7);
            color: white;
        }
        
        .login-left img {
            height: 100px;
            width: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .login-right {
            background: white;
        }
        
        .login-right form {
            width: 100%;
            max-width: 300px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                align-items: center;
            }
            .login-left, .login-right {
                width: 100%;
            }
        }
        .transition-hover {
            transition: background-color 0.3s, transform 0.2s;
        }

        .transition-hover:hover {
            background-color: #0d6efd; /* Bootstrap primary */
            transform: scale(1.03);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container mt-3">
            <a href="dashboard.php" class="btn btn-info btn-sm mb-3 d-inline-flex align-items-center gap-2 shadow-sm transition-hover" style="color: white;">
                <i class="fas fa-angle-double-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="login-left">
            <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo">
            <h1><?php echo $_settings->info('name') ?></h1>
        </div>
        <div class="login-right">
            <div class="card card-outline card-info shadow">
                <div class="card-header text-center">
                    <h5><b>Adviser Login Form</b></h5>
                </div>
                <div class="card-body">
                    <form id="slogin-form">
                        <div class="form-group">
                            <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password">
                                        👁
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="#" data-toggle="modal" data-target="#forgotPasswordModal">Forgot Password?</a>
                    </div>
                    <div class="text-center mt-3">
                        <b>No Account?</b> <a href="register-adviser.php" class="btn btn-success btn-block">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="forgot-password-form">
                        <div class="form-group">
                            <input type="email" name="email" id="forgot-email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>
    <script>
  $(document).ready(function(){
    end_loader();
    // Registration Form Submit
    $('#slogin-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
            $(".pop-msg").remove()
            $('#password, #cpassword').removeClass("is-invalid")
        var el = $("<div>")
            el.addClass("alert pop-msg my-2")
            el.hide()
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Login.php?f=adviser_login",
            method:'POST',
            data:_this.serialize(),
            dataType:'json',
            error:err=>{
                console.log(err)
                el.text("An error occured while saving the data")
                el.addClass("alert-danger")
                _this.prepend(el)
                el.show('slow')
                end_loader();
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.href= "./"
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
  $(document).ready(function(){
        $('#forgot-password-form').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'forgot_password-adviser.php',
                method: 'POST',
                data: { email: $('#forgot-email').val() },
                success: function(response){
                    alert(response);
                    $('#forgotPasswordModal').modal('hide');
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
    const toggleButton = document.querySelector(".toggle-password");
    const passwordInput = document.getElementById("password");

    toggleButton.addEventListener("click", function() {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.textContent = "🙈"; // Hide icon
        } else {
            passwordInput.type = "password";
            toggleButton.textContent = "👁"; // Show icon
        }
    });
});
</script>
</body>
</html>