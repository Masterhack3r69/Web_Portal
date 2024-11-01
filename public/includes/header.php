<?php 
session_start();
    $current_page = basename($_SERVER['PHP_SELF']); 
    include '../config/db.php';

if (isset($_SESSION['success_message'])) {
    $message = $_SESSION['success_message'];
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Notification',
            text: '".addslashes($message)."'
        });
    </script>";
    unset($_SESSION['success_message']); 
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dinagat</title>
<!-- Bootstrap CSS -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Danfo&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


<!-- Custom Stylesheet -->
<link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet">
<link id="pagestyle" href="../../assets/css/main.css" rel="stylesheet">

</head>
<style>
  
* {
  font-family: "Montserrat", serif;
}

body {
  margin: 0;
  padding: 0;
  background-color: #F2F8F7;
}

header {
  background-color: #17A567;
  color: white;
  padding: 10px 0;
  position: fixed;
  width: 100%;
  top: 0;
  height: 60px;
  z-index: 1000;
}

<?php if ($current_page == 'index.php') { ?>
  header {
  color: white;
  padding: 10px 0;
  position: fixed;
  width: 100%;
  top: 0;
  height: 60px;
  z-index: 1000;
  background-color: transparent;
  transition: background-color 0.3s;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

header.scrolled {
  background-color: #17A567; 
}
<?php } ?>


@media (max-width: 480px) {
  .header-content {
    padding: 0 20px;
  }

  .nav-menu {
    display: none;
  }

  .video-overlay h1 {
        font-size: 1.5em; 
    }

    .video-overlay h4 {
        font-size: 0.95em; 
    }
}


.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 0 auto;
  padding: 0 40px;
  height: 100%;
}

.title h1 {
  margin: 0;
  font-size: 1.5em;
  font-weight: 700;
}

h1, h2, h3, h4, h5, h6 {
  color: #136F54;
}

p {
  color: #333333;
}

.nav-menu {
  flex-grow: 2; 
  display: flex;
  justify-content: center;
}

.nav-menu nav ul, .register-login {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  gap: 15px; 
}

nav ul li a {
  color: white;
  text-decoration: none;
  font-size: 1.1em;
  font-weight: 500;
  transition: color 0.3s, transform 0.3s;
}

nav ul li a:hover {
  color: #FD5E53;
  transform: scale(1.05);
}

.register-btn,
.login-btn {
  color: white;
  text-decoration: none;
  padding: 4px 16px;
  border-radius: 4px;
  transition: background-color 0.3s, transform 0.3s;
}

.register-btn, 
.login-btn {
  background-color: transparent;
  border: 1px solid #DBE2EF;
}

.register-btn:hover, .login-btn:hover {
  background-color: #F1464B;
  border: 1px solid #F1464B;
  color: #DBE2EF;
}

.video-container {
  position: relative;
  height: 70vh;
  overflow: hidden;
}

#bgVideo {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.video-overlay {
  display:flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 0 10%;
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.45);
}

.video-overlay h1, .video-overlay h2 {
  text-align: left;
  margin-left: 20px; 
  flex-grow: 1;
  letter-spacing: 2px;
}

.department-container {
  background: linear-gradient(to right, #17A567, #D6F9E9, #17A567);

}
.logo-container {
  position: relative;
  display: inline-block;
  cursor: pointer;
}

.logo-img {
  transition: transform 0.3s ease, filter 0.3s ease;
  width: 160px; 
  height: auto;
  border-radius: 50%;
}

.logo-container:hover .logo-img {
  transform: scale(1.05);
  filter: blur(3px); 
}

.overlay-text {
  position: absolute;
  bottom: 50%; 
  left: 50%;
  transform: translate(-50%, 50%); 
  background-color: rgba(0, 0, 0, 0.2);
  color: white;
  text-align: center;
  font-size: 12px; 
  padding: 5px;
  opacity: 0;
  transition: opacity 0.3s ease;
  width: 100%;
  border-radius: 10px;
}

.logo-container:hover .overlay-text {
  opacity: 1;
}


.multi-line-text-truncate-3 {
  overflow: hidden;
  max-width: 400px;
  display: -webkit-box;
  -webkit-line-clamp: 3; 
  -webkit-box-orient: vertical;
  margin: 20px auto;
}

.multi-line-text-truncate-4 {
  overflow: hidden;
  max-width: 400px;
  display: -webkit-box;
  -webkit-line-clamp: 8; 
  -webkit-box-orient: vertical;
  margin: 20px auto;
}

.multi-line-text-truncate-3 p {
  font-size: small;
}

.btn, .card {
  box-shadow: none;
}

.btn{
  border: #F1464B 1px solid  !important;
  color: #F1464B;
}

.btn:hover{
  background-color: #F1464B;
  color: white;
}

.f-program a{
  color: #F1464B;
}

.link-btn {
  background-color: #F1464B;
  color: white;
}

.benefit-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .benefit-card:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    background-color: #17A567;
  }

  .card-body img {
    transition: transform 0.3s ease;
  }

  .benefit-card:hover .card-body img {
    transform: scale(1.2);
  }

  .benefit-card:hover p, .benefit-card:hover h5 {
    color: white;
  }

.card-box {
 border-radius: 20px;
 background: #f5f5f5;
 position: relative;
 border: 1px solid #c3c6ce;
 transition: 0.5s ease-out;
 overflow: visible;
}


.card-box:hover {
 border-color: #F1464B;
 box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.25);
}

.card-box:hover .card-button {
 transform: translate(-50%, 50%);
 opacity: 1;
}

.carousel-img {
    height: 400px; 
    object-fit: cover; 
}

.form-check {
    margin: 10px 0;
}

  .dropbtn {
    background-color: transparent;
    color: white;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: 500;
  }

  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    width: 200px;
    border-radius: 5px;
    z-index: 1;
  }

  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  .dropdown-content a:hover {
    background-color: #ddd;
    border-radius: 5px;
    color: #F1464B;
  }

  .dropdown:hover .dropdown-content {
    display: block;
    
  }

  .dropdown:hover .dropbtn {
    color: #F1464B;
    
  }

  .log-in-btn:hover, .profile-dropdown:hover {
    color:#F1464B;
  }

  #responsiveMenu {
    background-color: #A3D7BC; 
    position: absolute; 
    top: 60px;
    left: 0;
    width: 100%;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); 
    z-index: 1000;
  }

  #responsiveMenu ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  #responsiveMenu li {
    padding: 10px 20px; 
  }

  #responsiveMenu li a {
    text-decoration: none; 
    color: #333333; 
  }

  #responsiveMenu li:hover {
    background-color: #f0f0f0; 
  }

  #responsiveMenu li a:hover {
    color: #F1464B; 
  }

  .nav-icon {
    cursor: pointer;
    font-size: 1.5em;
    color: #fff;
  }

</style>
<body>  
<header class="header">
  <div class="header-content">
    <div class="title text-white">
      <h1 class="text-white">Dinagat <span style="color: #F1464B;">Connect</span></h1>
    </div>

    <!-- Navigation Menu -->
    <div class="nav-menu d-none d-md-flex">
      <nav>
        <ul>
          <div class="dropdown">
            <li class="dropbtn"><a href="index.php">Home</a></li>
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

    <!-- Hamburger Icon for Smaller Screens -->
    <div class="nav-icon d-md-none" title="Menu" onclick="toggleMenu()">
      <i class="fas fa-bars"></i>
    </div>

    <!-- Register/Login Section -->
    <div class="register-login d-none d-md-flex">
      <?php if (isset($_SESSION['username'])) { ?>
        <div class="dropdown log-in-btn">
          <a href="#" class="text-decoration-none" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit; font-size: 1.2em;">
            <i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton" style="min-width: 200px; font-size: 1.1em;">
            <li><a class="dropdown-item d-flex align-items-center profile-dropdown" href="./users/profile.php">
              <i class="fas fa-user me-2"></i> My Profile
            </a></li>
            <li><a class="dropdown-item d-flex align-items-center profile-dropdown" href="./users/notifications.php">
              <i class="fas fa-bell me-2"></i> Notifications
            </a></li>
            <li><a class="dropdown-item d-flex align-items-center profile-dropdown" href="./logout.php">
              <i class="fas fa-sign-out-alt me-2"></i> Sign Out
            </a></li>
          </ul>
        </div>
      <?php } else { ?>
        <a id="login" href="./users/login_resident.php" class="login-btn">Login</a>
        <a href="./users/register_resident.php" class="register-btn">Register</a>
      <?php } ?>
    </div>
  </div>

  <!-- Responsive Menu -->
  <div id="responsiveMenu" class="d-md-none" style="display: none;">
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="department.php">Department</a></li>
        <li><a href="program.php">Program</a></li>
        <li><a href="benefits.php">News & Updates</a></li>
        <div class="dropdown">
          <li class="dropbtn"><a href="#">More</a></li>
          <div class="dropdown-content">
            <a href="index.php#featured">Featured Programs</a>
            <a href="index.php#about">About Us</a>
            <a href="index.php#contact">Contact Us</a>
          </div>
        </div>
      </ul>
    </nav>
  </div>
</header>
 
  <script>
  function toggleMenu() {
    const menu = document.getElementById('responsiveMenu');
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
  }
</script>
