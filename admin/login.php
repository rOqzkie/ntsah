<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
<?php require_once('inc/header.php') ?>
<body class="hold-transition">
<script> start_loader() </script>

<style>
    html, body {
        height: 100% !important;
        width: 100% !important;
        margin: 0;
        padding: 0;
    }
    body {
        background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-title {
        text-shadow: 2px 2px black;
        padding-top: 10px;
        padding-bottom: 20px;
        font-size: 1.2rem;
    }
    #logo-img {
        height: 150px;
        width: 150px;
        object-fit: scale-down;
        object-position: center;
        border-radius: 50%;
    }
    .login-container {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 400px;
    }
    @media (max-width: 768px) {
        .login-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="container d-flex flex-column align-items-center justify-content-center login-wrapper">
    <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" id="logo-img" class="mb-3">

    <div class="login-container card card-outline card-info">
        <div class="card-header text-center">
            <h4 class="text-purple mb-0"><b>Admin Login</b></h4>
        </div>
        <div class="card-body">
            <form id="login-frm" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-info btn-block">Sign In <i class="fas fa-sign-in-alt"></i></button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        end_loader();
    });

    document.getElementById('togglePassword').addEventListener('click', function () {
        let passwordInput = document.getElementById('password');
        let icon = this.querySelector('i');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
</body>
</html>