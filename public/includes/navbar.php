<header class="header">
  <div class="header-content">
    <div class="title logo-title-container">
        <div class="logo-icon-container">
            <img src="../../assets/img/logo.png" alt="PGDIPS Logo" class="logo-title">
        </div>
        <a href="index.php"><h1 class="text-white"><span style="color: #F1464B;">PGDI</span>PS</h1></a>
    </div>
    <div class="nav-menu d-none d-md-flex">
      <nav>
        <ul>
          <div class="dropdown">
            <li class="dropbtn mb-0"><a href="index.php">Home</a></li>
            <div class="dropdown-content">
              <a href="index.php#featured">Featured Programs</a>
              <a href="index.php#about">About Us</a>
              <a href="index.php#contact">Contact Us</a>
            </div>
          </div>
          <li class="mb-0"><a href="department.php">Department</a></li>
          <li class="mb-0"><a href="program.php">Program</a></li>
          <li class="mb-0"><a href="news_update.php">News & Updates</a></li>
        </ul>
      </nav>
    </div>

    <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['username'])) { ?>
          <div class="dropdown dropdown-start me-3 d-md-none">
              <a href="index.php" class="dropbtn" id="notificationButtonMobile" data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit; font-size: 1.2em; position: relative;">
                  <i class="fas fa-bell notification-icon"></i>
              </a>
            <div class="dropdown-content notification-dropdown" aria-labelledby="notificationButtonMobile" style="right: 0; width: 300px; max-height: 400px; overflow-y: auto;">
                <div id="notificationListMobile">
                    <div class="text-center p-3 no-notifications">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="mb-0">No new notifications</p>
                    </div>
                </div>
            </div>
          </div>
        <?php } ?>
        <div class="nav-icon d-md-none" title="Menu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <div class="registera-login d-none d-md-flex">
    <?php if (isset($_SESSION['username'])) { ?>
        <div class="dropdown dropdown-start me-3">
            <a href="index.php" class="dropbtn" id="notificationButtonDesktop" data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit; font-size: 1.2em; position: relative;">
                <i class="fas fa-bell notification-icon"></i>
            </a>
            <div class="dropdown-content notification-dropdown" aria-labelledby="notificationButtonDesktop" style="right: 0; width: 300px; max-height: 400px; overflow-y: auto;">
                <div id="notificationListDesktop">
                    <div class="text-center p-3 no-notifications">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="mb-0">No new notifications</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown dropdown-start log-in-btn">
            <a href="#" class="dropbtn" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit; font-size: 1.2em;">
                <i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?>
            </a>
            <div class="dropdown-content " aria-labelledby="userMenuButton" style="right: 0;">
                <a class="dropdown-item d-flex align-items-center profile-dropdown" href="./profile.php">
                  <i class="fas fa-user me-2"></i> My Profile
              </a>
              <a class="dropdown-item d-flex align-items-center profile-dropdown" href="./logout.php">
                  <i class="fas fa-sign-out-alt me-2"></i> Sign Out
              </a>
            </div>
        </div>
    <?php } else { ?>
        <a id="login" href="./users/login_resident.php" class="login-btn me-1">Login</a>
        <a href="./users/register_resident.php" class="register-btn">Register</a>
    <?php } ?>
</div>

  <div id="responsiveMenu" class="d-md-none">
    <nav>
      <ul>
        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="department.php"><i class="fas fa-building"></i> Department</a></li>
        <li><a href="program.php"><i class="fas fa-table"></i> Program</a></li>
        <li><a href="news_update.php"><i class="fas fa-newspaper"></i> News & Updates</a></li>
        <?php if (isset($_SESSION['username'])) { ?>
            <li><a href="./profile.php"><i class="fas fa-user"></i> My Profile</a></li>
            <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Sign Out</a></li>
        <?php } else { ?>
          <li><a href="./users/login_resident.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
          <li><a href="./users/register_resident.php"><i class="fas fa-user-plus"></i> Register</a></li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</header>
  