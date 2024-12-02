<nav class="navbar navbar-main navbar-expand-lg shadow-sm  px-0 mx-4 border-radius-xl bg-white mt-3 " navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
      <div class="collapse navbar-collapse mt-sm-0 mt-2" id="navbar">
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="mx-auto">
            <h6 class="mb-0 text-truncate d-none d-lg-inline">
              <?php 
                if ($_SESSION['admin_type'] === 'local') {
                  $department_name = query("SELECT department_name FROM departments WHERE id = ?", [$_SESSION['department_id']])->fetch_assoc()['department_name'];
                  echo htmlspecialchars($department_name); 
                } else {
                  echo "Provincial Government of Dinagat Islands: Programs and Services"; 
                }
              ?>
            </h6>
          </div>
          <ul class="navbar-nav justify-content-end flex-row flex-wrap ms-auto">
            <li class="nav-item dropdown position-relative d-flex align-items-center">
                <?php
                $admin_type = $_SESSION['admin_type'];
                $admin_department_id = $_SESSION['department_id'] ?? null; 
            
                if ($admin_type === 'central' || $admin_type === 'local'): 
                    if ($admin_type === 'central') {
                        if (isset($_GET['view_messages'])) {
                            query("UPDATE messages SET status = 'read' WHERE department_id IS NULL AND status = 'unread'");
                        }
                        $message_query = query("SELECT COUNT(*) as unread_count FROM messages WHERE status = 'unread' AND department_id IS NULL");
                        $message_link = "../central_admin/message.php"; 
                    } elseif ($admin_type === 'local') {
                        if (isset($_GET['view_messages'])) {
                            query("UPDATE messages SET status = 'read' WHERE department_id = ? AND status = 'unread'", [$admin_department_id]);
                        }
                        $message_query = query("SELECT COUNT(*) as unread_count FROM messages WHERE status = 'unread' AND department_id = ?", [$admin_department_id]);
                        $message_link = "../local_admin/message.php"; 
                    }
                    
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
            
                        <a class="nav-link text-body my-0 px-1 p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="markMessagesAsRead(event, '<?php echo $admin_type; ?>', <?php echo $admin_department_id ? $admin_department_id : 'null'; ?>)">
                            <i class="fas fa-envelope fa-lg text-primary"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4">
                            <?php
                            if ($admin_type === 'central') {
                                $recent_messages = query("SELECT email, message, created_at, status FROM messages 
                                                       WHERE department_id IS NULL 
                                                       ORDER BY created_at DESC LIMIT 5");
                            } else {
                                $recent_messages = query("SELECT email, message, created_at, status FROM messages 
                                                       WHERE department_id = ? 
                                                       ORDER BY created_at DESC LIMIT 5", 
                                                       [$admin_department_id]);
                            }

                            if ($recent_messages && $recent_messages->num_rows > 0):
                                while ($message = $recent_messages->fetch_assoc()):
                                    $message_preview = strlen($message['message']) > 50 ? 
                                                     substr(htmlspecialchars($message['message']), 0, 50) . '...' : 
                                                     htmlspecialchars($message['message']);
                            ?>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="<?php echo $message_link; ?>">
                                        <div class="d-flex py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold"><?php echo htmlspecialchars($message['email']); ?></span>
                                                </h6>
                                                <p class="text-xs <?php echo $message['status'] === 'unread' ? 'text-primary' : 'text-muted'; ?> mb-0">
                                                    <?php echo $message_preview; ?>
                                                </p>
                                                <small class="text-muted" style="font-size: 0.7em;">
                                                    <?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="#">
                                        <div class="d-flex py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-sm font-weight-normal mb-0">No messages found</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="mt-2">
                                <a class="dropdown-item text-center border-radius-md text-primary" href="<?php echo $message_link; ?>">
                                    View All Messages
                                </a>
                            </li>
                        </ul>
                    </li>

                    <?php if ($admin_type === 'local'): 
                        // When viewing applications, update their status from unread to pending
                        if (isset($_GET['program_id'])) {
                            $program_id = $_GET['program_id'];
                            
                            // Update unread applications to pending when viewed
                            query("UPDATE form_submissions 
                                   SET status = 'pending' 
                                   WHERE program_id = ? 
                                   AND status = 'unread'", [$program_id]);
                        }
                        
                        // Get applications that are either unread (new) or pending
                        $applications_query = query(
                            "SELECT p.id, p.title, 
                             COUNT(CASE WHEN fs.status = 'unread' THEN 1 END) as new_applications,
                             COUNT(*) as total_applications 
                             FROM form_submissions fs 
                             JOIN programs p ON fs.program_id = p.id 
                             WHERE (fs.status = 'unread' OR fs.status = 'pending')
                             AND p.department_id = ? 
                             GROUP BY p.id, p.title 
                             ORDER BY new_applications DESC 
                             LIMIT 5", 
                            [$admin_department_id]
                        );
                        
                        // Count total unread applications for notification dot
                        $total_unread = query(
                            "SELECT COUNT(*) as total 
                             FROM form_submissions fs 
                             JOIN programs p ON fs.program_id = p.id 
                             WHERE fs.status = 'unread' 
                             AND p.department_id = ?", 
                            [$admin_department_id]
                        )->fetch_assoc()['total'];
                    ?>
                        <li class="nav-item dropdown position-relative d-flex align-items-center me-4">
                            <?php if ($total_unread > 0): ?>
                                <span class="notification-dot position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                                    <?php echo $total_unread; ?>
                                    <span class="visually-hidden">new applications</span>
                                </span>
                            <?php endif; ?>
                            
                            <a class="nav-link text-body my-0 px-1 p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-lg <?php echo $total_unread > 0 ? 'text-danger' : 'text-primary'; ?>"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4">
                                <?php if ($applications_query->num_rows > 0): ?>
                                    <?php while ($program = $applications_query->fetch_assoc()): ?>
                                        <li class="mb-2">
                                            <a class="dropdown-item border-radius-md" href="../local_admin/view_applications.php?program_id=<?php echo $program['id']; ?>">
                                                <div class="d-flex py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="text-sm font-weight-normal mb-1">
                                                            <span class="font-weight-bold"><?php echo htmlspecialchars($program['title']); ?></span>
                                                        </h6>
                                                        <p class="text-xs <?php echo $program['new_applications'] > 0 ? 'text-danger' : 'text-primary'; ?> mb-0">
                                                            <?php 
                                                            if ($program['new_applications'] > 0) {
                                                                echo $program['new_applications'] . ' new application(s)';
                                                            } else {
                                                                echo $program['total_applications'] . ' pending application(s)';
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md" href="#">
                                            <div class="d-flex py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="text-sm font-weight-normal mb-0">No new applications</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
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

<script>

</script>
