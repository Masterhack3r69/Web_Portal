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
  <title>Dinagat Islands Provincial Governor’s Office: Programs and Services</title>
<!-- Bootstrap CSS -->
<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="../../assets/img/icon.png">
<!-- Google Fonts Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Danfo&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Custom Stylesheet -->
<link id="pagestyle" href="../../assets/css/main-bootstrap.css?v=1.0.7" rel="stylesheet">
<link id="pagestyle" href="../../assets/css/main.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/notifications.css">
</head>

<style>
.logo-title {
    height: 40px;
    width: auto;
    max-width: 100%;
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

 header .logo-icon-container {
    transition: all 0.3s ease;
    padding: 5px;
    border-radius: 50%;
    background-color: transparent;
  }

  header.scrolled .logo-icon-container {
    background-color: white;
  }

  header .title a h1 {
    color: white !important;
    transition: all 0.3s ease;
  }
  
  header .title a h1 span {
    color: #F1464B !important;
    transition: all 0.3s ease;
  }

  header.scrolled .title a h1 {
    color: #F1464B !important;
  }
  
  header.scrolled .title a h1 span {
    color: white !important;
  }
  
<?php } ?>
@media (max-width: 768px) {
    .logo-title {
          height: 30px;
    }
    .video-overlay h3 {
        font-size: 1rem; 
    }

    .video-overlay p {
        font-size: 0.9rem; 
    }
}

@media (max-width: 480px) {
    .video-overlay h3 {
        font-size: 1rem; 
    }

    .video-overlay p {
        display: none;
    }
    
    .logo-overlay img {
        width: 50px;
    }
}

@media (min-width: 992px) {
    .responsive-logo {
        max-width: 200px;
    }
    .responsive-title {
        font-size: 2.5rem;
    }
    .responsive-description {
        font-size: 1.2rem;
    }
}

@media (min-width: 1200px) {
    .responsive-logo {
        max-width: 250px;
    }
    .responsive-title {
        font-size: 3rem;
    }
    .responsive-description {
        font-size: 1.4rem;
    }
}

 @keyframes fadeInFromSide {
      0% {
        opacity: 0;
        transform: translateX(-100px);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    
.fade-in-section {
  overflow: hidden;
  position: relative;
}

.fade-in-left {
  opacity: 0;
  transform: translateX(-10px); 
  transition: opacity 1s ease, transform 1s ease;
}

.fade-in-right {
  opacity: 0;
  transform: translateX(10px); 
  transition: opacity 1s ease, transform 1s ease;
}

.fade-in-section.in-view .fade-in-left,
.fade-in-section.in-view .fade-in-right {
  opacity: 1;
  transform: translateX(0);
}

.fade-in-bottom {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 1s ease, transform 1s ease;
}

.in-view {
  opacity: 1;
  transform: translateY(0);
}


</style>

<body> 
<div class="spinner-overlay">
  <div class="spinner"></div>
</div>
<?php include 'navbar.php'; ?>
