<?php 
$pageHover = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main" >
    <div class="sidenav-header">
      <a class="navbar-brand text-center m-0 fs-6  pb-1" href="#">
        <i class="fa <?= ($_SESSION['admin_type'] === 'central') ? 'fa-circle-user' : 'fa-user-gear'; ?> fa-lg text-primary"></i>
        <span class="ms-1 font-weight-bold d-flex flex-column">
          <?php echo ($_SESSION['admin_type'] === 'central') ? 'Central Admin' : 'Local Admin'; ?>
          <?php if($_SESSION['admin_type'] !== 'central'): ?>
           
          <?php endif; ?>
        </span>
      </a>
      <hr class="horizontal dark mt-0">
    </div>
    
    <div class="collapse navbar-collapse w-auto pt-3" id="sidenav-collapse-main">
      <ul class="navbar-nav pb-5">
        <li class="nav-item">
          <a class="nav-link <?= $pageHover == 'index.php' ? 'active' : ''; ?>" href="index.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-home <?= $pageHover == 'index.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Manage</h6>
        </li>

        <?php if ($_SESSION['admin_type'] === 'central'): ?>
          <!-- Central Admin-->
          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'enquires.php' ? 'active' : ''; ?>" href="enquires.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-bullhorn <?= $pageHover == 'enquires.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">News And Updates</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'department.php' ? 'active' : ''; ?>" href="../central_admin/department.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-building <?= $pageHover == 'department.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">Department</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'program.php' ? 'active' : ''; ?>" href="../central_admin/program.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-tasks <?= $pageHover == 'program.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">Programs</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'local_admin.php' ? 'active' : ''; ?>" href="../central_admin/local_admin.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-user-gear <?= $pageHover == 'local_admin.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">Local Admin</span>
            </a>
          </li>
          <li class="nav-item">
          <a class="nav-link <?= $pageHover == 'user.php' ? 'active' : ''; ?>" href="../central_admin/user.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-users <?= $pageHover == 'user.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Users</span>
          </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'audit_log.php' ? 'active' : ''; ?>" href="../central_admin/audit_log.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-clipboard <?= $pageHover == 'audit_log.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">Audit Logs</span>
            </a>
          </li> 

          <!-- local admin -->
        <?php elseif ($_SESSION['admin_type'] === 'local'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'enquires.php' ? 'active' : ''; ?>" href="../local_admin/enquires.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-bullhorn <?= $pageHover == 'enquires.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">News And Updates</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'program.php' ? 'active' : ''; ?>" href="../local_admin/program.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-tasks <?= $pageHover == 'program.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">Programs</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'form.php' ? 'active' : ''; ?>" href="../local_admin/form.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-wrench <?= $pageHover == 'form.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">Form Builder</span>
            </a>
          </li>
          
            <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Settings</h6>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $pageHover == 'profile.php' ? 'active' : ''; ?>" href="../local_admin/profile.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-user <?= $pageHover == 'profile.php' ? 'text-white' : 'text-dark'; ?> text-sm"></i>
              </div>
              <span class="nav-link-text ms-1">profile</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
</aside>

