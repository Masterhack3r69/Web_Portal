  <nav class="navbar navbar-main navbar-expand-lg shadow-sm  px-0 mx-4 border-radius-xl bg-white mt-3 " navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
          <li class="breadcrumb-item text-sm">
            <a class="opacity-5 text-dark">Pages</a>
          </li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
            <?php if(isset($title)) { echo htmlspecialchars($title);} else { echo "Dashboard";} ?>
          </li>
        </ol>
      </nav>
      <div class="collapse navbar-collapse mt-sm-0 mt-2" id="navbar">
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="mx-auto">
            <h6 class="mb-0 text-truncate d-none d-lg-inline">
              <?php 
                if ($_SESSION['admin_type'] === 'local') {
                  $department_name = query("SELECT department_name FROM departments WHERE id = ?", [$_SESSION['department_id']])->fetch_assoc()['department_name'];
                  echo htmlspecialchars($department_name); 
                } else {
                  echo "Unified Web Portal"; 
                }
              ?>
            </h6>
          </div>
          <ul class="navbar-nav justify-content-end flex-row flex-wrap ms-auto">
            <li class="nav-item dropdown position-relative d-flex align-items-center">
              <?php if ($_SESSION['admin_type'] === 'central'): ?>
                  <?php
                      // Query to count unread feedback messages
                      $message_query = query("SELECT COUNT(*) as unread_count FROM messages WHERE status = 'unread'");
                      $row = $message_query->fetch_assoc();
                      $unread_messages = $row['unread_count'];
                  ?>
                  <li class="nav-item dropdown position-relative d-flex align-items-center me-4">
                      <?php if ($unread_messages > 0): ?>
                          <span class="notification-dot position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                              <?php echo $unread_messages; ?>
                              <span class="visually-hidden">unread messages</span>
                          </span>
                      <?php else: ?>
                          <span class="notification-dot position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 1em;"></span>
                      <?php endif; ?>
                      
                      <a class="nav-link text-body my-0 px-1 p-0" href="../central_admin/message.php" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-envelope fa-lg text-primary"></i>
                      </a>
                  </li>
              <?php endif; ?>
            </li>


            <li class="nav-item d-none d-lg-flex align-items-center me-2 ">
              <a class="btn btn-outline-primary btn-sm mb-0 px-3  py-2" target="_blank" href="../../../public/index.php">Visit Website</a>
            </li>
            <li class="nav-item d-flex d-lg-none align-items-center me-4">
              <a class="nav-link text-body px-1 px-md-0 me-sm-4" target="_blank" href="#">
                <i class="fas fa-globe fa-lg text-primary"></i> 
              </a>
            </li>
            <li class="nav-item d-flex d-lg-none align-items-center me-2">
              <a class="nav-link text-body p-0" href="../../../public/logout.php">
                <i class="fas fa-sign-out-alt text-danger fa-lg"></i>
              </a>
            </li>
            <li class="nav-item d-none d-lg-flex align-items-center me-2">
              <a class="btn btn-outline-danger btn-sm mb-0 px-3 py-2 rounded" href="../../../public/logout.php">
                Log Out
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

