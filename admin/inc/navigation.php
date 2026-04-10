<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand bg-secondary">
  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link d-flex align-items-center bg-transparent shadow-sm px-3">
    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Logo" class="brand-image img-circle elevation-2 bg-black me-2" style="width: 1.8rem; height: 1.8rem; object-fit: cover;">
    <span class="brand-text fw-light"><?php echo $_settings->info('short_name') ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-4 text-bold">
      <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Archives -->
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=archives" class="nav-link nav-archives">
            <i class="nav-icon fas fa-archive"></i>
            <p>
              <?php echo $_settings->userdata('type') == 1 ? 'Manage Archives' : 'Manage Dept. Archives'; ?>
            </p>
          </a>
        </li>

        <!-- Students (Admin only) -->
        <?php if ($_settings->userdata('type') == 1): ?>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=students" class="nav-link nav-students">
            <i class="nav-icon fas fa-users"></i>
            <p>Manage Students</p>
          </a>
        </li>
        <?php endif; ?>

        <!-- Advisers -->
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=adviser" class="nav-link nav-adviser">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>
              <?php echo $_settings->userdata('type') == 1 ? 'Manage Advisers' : 'Manage Dept. Advisers'; ?>
            </p>
          </a>
        </li>

        <!-- Maintenance Section (Admin only) -->
        <?php if ($_settings->userdata('type') == 1): ?>
        <li class="nav-header text-uppercase text-muted fw-bold mt-3 text-white">Maintenance</li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=college" class="nav-link nav-college">
            <i class="nav-icon fas fa-university"></i>
            <p>College List</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=departments" class="nav-link nav-departments">
            <i class="nav-icon fas fa-th-list"></i>
            <p>Department List</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=program" class="nav-link nav-program">
            <i class="nav-icon fas fa-scroll"></i>
            <p>Program List</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=position" class="nav-link nav-position">
            <i class="nav-icon fas fa-user-tag"></i>
            <p>Academic Rank List</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Department Chair List</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=discipline" class="nav-link nav-discipline">
            <i class="nav-icon fa fa-search-plus"></i>
            <p>Research Discipline List</p>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>

<script>
  var page;
  $(document).ready(function () {
    page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>'.replace(/\//gi, '_');
    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active');
      if ($('.nav-link.nav-' + page).hasClass('tree-item')) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active');
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open');
      }
      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree')) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open');
      }
    }

    $('#receive-nav').click(function () {
      $('#uni_modal').on('shown.bs.modal', function () {
        $('#find-transaction [name="tracking_code"]').focus();
      });
      uni_modal("Enter Tracking Number", "transaction/find_transaction.php");
    });
  });
</script>