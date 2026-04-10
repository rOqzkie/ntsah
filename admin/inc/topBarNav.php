<style>
    .user-img {
        height: 30px;
        width: 30px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 4px rgba(0,0,0,0.2);
    }

    .btn-rounded {
        border-radius: 50px;
    }

    .notification-bell i {
        font-size: 1.5rem;
        color: #FFFFFF;
        transition: color 0.3s ease, transform 0.2s ease;
    }

    .notification-bell:hover i {
        color: #dc3545;
        transform: scale(1.2);
    }

    .notification-bell {
        position: relative;
    }

    .notification-bell .badge {
        position: absolute;
        top: 0px;
        right: -5px;
        font-size: 10px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 3px 6px;
    }

    .dropdown-menu {
        min-width: 260px;
    }

    .dropdown-item i {
        width: 20px;
    }

    @media (max-width: 576px) {
        .navbar-nav .dropdown-toggle span.ml-3 {
            display: none;
        }
    }
</style>

<nav class="main-header navbar navbar-expand navbar-primary border-bottom shadow-sm text-sm">
    <!-- Left -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo base_url ?>" class="nav-link font-weight-bold text-white">
                <?php echo (!isMobileDevice()) ? $_settings->info('name') : $_settings->info('short_name'); ?>
            </a>
        </li>
    </ul>

    <!-- Right -->
    <ul class="navbar-nav ml-auto align-items-center">
        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link notification-bell" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell" id="notificationIcon"></i>
                <span class="badge badge-danger" id="notificationCount">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
                <span class="dropdown-item dropdown-header font-weight-bold text-primary">Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url ?>admin/?page=archives" class="dropdown-item">
                    <i class="fas fa-folder-open text-warning"></i> Archive pending for approval 
                    <span class="badge badge-warning float-right" id="pendingArchive">0</span>
                </a>
                <?php if($_settings->userdata('type') == 1): ?>
                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url ?>admin/?page=students" class="dropdown-item">
                    <i class="fas fa-user-check text-success"></i> Students pending verification 
                    <span class="badge badge-success float-right" id="pendingStudents">0</span>
                </a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url ?>admin/?page=adviser" class="dropdown-item">
                    <i class="fas fa-user-tie text-primary"></i> Advisers pending verification 
                    <span class="badge badge-primary float-right" id="pendingAdvisers">0</span>
                </a>
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown ml-3">
            <a href="#" class="nav-link d-flex align-items-center dropdown-toggle text-white" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" alt="User Image" class="user-img">
                <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?php echo base_url.'admin/?page=user' ?>">
                    <i class="fa fa-user"></i> My Account
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- Notification Script -->
<script>
function fetchNotifications() {
    fetch('fetch_notifications.php')
    .then(response => response.json())
    .then(data => {
        let pendingArchive = parseInt(data.pendingArchive) || 0;
        let pendingStudents = parseInt(data.pendingStudents) || 0;
        let pendingAdvisers = parseInt(data.pendingAdvisers) || 0;

        document.getElementById("pendingArchive").innerText = pendingArchive;
        document.getElementById("pendingAdvisers").innerText = pendingAdvisers;

        let userType = <?php echo $_settings->userdata('type'); ?>;
        if (userType == 1) {
            document.getElementById("pendingStudents").innerText = pendingStudents;
        }

        let totalNotifications = pendingArchive + pendingAdvisers;
        if (userType == 1) {
            totalNotifications += pendingStudents;
        }

        document.getElementById("notificationCount").innerText = totalNotifications > 0 ? totalNotifications : "";
    })
    .catch(error => console.error("Error fetching notifications:", error));
}

document.addEventListener("DOMContentLoaded", fetchNotifications);
setInterval(fetchNotifications, 30000);
</script>