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
            <h5 class="mb-0 text-truncate d-none d-lg-inline">
              <?php 
                if ($_SESSION['admin_type'] === 'local') {
                  $department_name = query("SELECT department_name FROM departments WHERE id = ?", [$_SESSION['department_id']])->fetch_assoc()['department_name'];
                  echo htmlspecialchars($department_name); 
                } else {
                  echo "Unified Web Portal"; 
                }
              ?>
            </h5>
          </div>
          <?php $messages = query("SELECT email, message FROM messages WHERE email LIKE ?", ['%@gmail.com']); ?>
            <div class="dropdown">
              <a class=" dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Messages
              </a>
              <ul class="dropdown-menu" aria-labelledby="messagesDropdown" id="messagesList">
                <?php while ($row = $messages->fetch_assoc()): ?>
                  <li>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="showMessage('<?php echo htmlspecialchars($row['message']); ?>', '<?php echo htmlspecialchars($row['email']); ?>')">
                      <?php echo htmlspecialchars($row['email']); ?>
                    </a>
                  </li>
                <?php endwhile; ?>  
              </ul>
            </div>
          <ul class="navbar-nav justify-content-end flex-row flex-wrap ms-auto">
            
            <li class="nav-item d-none d-lg-flex align-items-center me-2 ">
              <a class="btn btn-outline-primary btn-sm mb-0  py-2" target="_blank" href="../../../public/index.php">Visit Website</a>
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

<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">Message from <span id="emailLabel"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="messageBody">
        <!-- Message content will be injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function showMessage(message, email) {
    // Set the email in the modal title
    document.getElementById('emailLabel').innerText = email;
    // Set the message in the modal body
    document.getElementById('messageBody').innerText = message;
    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('messageModal'));
    modal.show();
}
</script>