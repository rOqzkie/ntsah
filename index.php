<?php 
require_once('./config.php');
if (session_status() === PHP_SESSION_NONE) session_start();

// Restrict access to logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="" style="">
<style>
    #header{
        height:30vh;
        width:calc(100%);
        position:relative;
        top:-1em;
    }
    #header:before{
        content:"";
        position:absolute;
        height:calc(100%);
        width:calc(100%);
        /*background-image:url(<?= validate_image($_settings->info("cover")) ?>);*/
        background-color: #E3F2FD;
        background-size:cover;
        background-repeat:no-repeat;
        background-position: center center;
    }
    #header>div{
        position:absolute;
        height:calc(100%);
        width:calc(100%);
        z-index:2;
    }
    #top-Nav a.nav-link.active {
        color: #001f3f;
        font-weight: 900;
        position: relative;
    }
    #top-Nav a.nav-link.active:before {
        content: "";
        position: absolute;
        border-bottom: 2px solid #001f3f;
        width: 33.33%;
        left: 33.33%;
        bottom: 0;
    }
</style>
<?php require_once('inc/header.php') ?>
<body class="layout-top-nav layout-fixed layout-navbar-fixed" style="">
    <div class="wrapper">
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
        <?php require_once('inc/topBarNav.php') ?>
        <?php if($_settings->chk_flashdata('success')): ?>
        <script>
            alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
        </script>
        <?php endif;?>    
        <!-- Content Wrapper. Contains page content -->
        <?php if($page == "home" || $page == "about_us"): ?>
        <?php endif; ?>
        <!-- Main content -->
        <section class="content card">
            <div class="container">
            <?php 
                if(!file_exists($page.".php") && !is_dir($page)){
                    include '404.html';
                }else{
                    if(is_dir($page))
                        include $page.'/index.php';
                    else
                        include $page.'.php';
                }
            ?>
            </div>
        </section>
        <!-- /.content -->
        <div class="modal fade" id="confirm_modal" role='dialog'>
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                    </div>
                    <div class="modal-body">
                        <div id="delete_content">
                        
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
    <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                    <div class="modal-body">
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_right" role='dialog'>
        <div class="modal-dialog modal-full-height  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="fa fa-arrow-right"></span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewer_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                <img src="" alt="">
            </div>
        </div>
    </div>
    </div>
        <!-- /.content-wrapper -->
</body>
<div class="footer">
    <?php require_once('inc/footer.php') ?>
</div>
</html>