<?php
include '../../../config/db.php';

session_start();

$department_id = $_SESSION['department_id'];

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../admin/index.php');
    exit;
}

if ($_SESSION['admin_type'] === 'central') {
    if (strpos($_SERVER['REQUEST_URI'], '/local_admin/') !== false) {
        header('Location: ../central_admin/index.php');
        exit;
    }
} elseif ($_SESSION['admin_type'] === 'local') {
    if (strpos($_SERVER['REQUEST_URI'], '/central_admin/') !== false) {
        header('Location: ../local_admin/index.php');
        exit;
    }
} else {
    header('Location: ../../admin/index.php');
    exit;
}

if (isset($_GET['msg']) && isset($_GET['type'])) {
  showAlert($_GET['msg'], $_GET['type']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    <?php if(isset($title)) { echo $title; }else { echo "Dashboard"; } ?>
  </title>
  <!-- Font Stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- CSS Stylesheets -->
<link href="../../../assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="../../../assets/css/nucleo-svg.css" rel="stylesheet" />
<link id="pagestyle" href="../../../assets/css/main-bootstrap.css?v=1.0.7" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  
</head>
<style>
 .sidenav-header {
    position: sticky;
    top: 0;
    z-index: 1000; 
    background-color: white; 
}

.sidenav {
    height: 100vh; 
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.multi-line-text-truncate {
  overflow: hidden; 
  display: -webkit-box;
  -webkit-line-clamp: 3; 
  -webkit-box-orient: vertical;
  margin: 20px auto;
}

</style>

<body class="g-sidenav-show  bg-gray-100"></body>

  <?php include 'sidebar.php' ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

  <?php include 'navbar.php' ?>

    <div class="container py-4">

      