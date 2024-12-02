<?php
session_start();
include '../../config/db.php';
include '../alerts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = query("SELECT * FROM users WHERE username = ?", [$username]);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; 
        
        if (isset($_GET['redirect'])) {
            $redirectUrl = $_GET['redirect'];
            header("Location: $redirectUrl");
        } else {
            header('Location: ../index.php'); 
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password. Please try again.";
    } 
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in</title>
  <link rel="icon" type="image/png" href="../../../assets/img/icon.png">
  <link href="https://fonts.googleapis.com/css2?family=Danfo&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link href="../../assets/css/background.css" rel="stylesheet"> -->
  
  <link href="../../assets/css/form_authentication.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
   <div class="alert-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
    <div class="text-center mb-2">
        <img src="../../assets/img/icon.png" alt="PGDIPS Icon" style="width: 80px; height: 80px; margin-bottom: 10px;">
        <h3 style="color:#17A567; font-weight: bold;">PGDI<span style="color:#F1464B; ">PS</span></h3>
    </div>
    <form class="form" method="POST" action="#">
        <p class="title">Sign in</p> 
        <label>
            <input required="" placeholder="" type="text" name="username" class="input">
            <span>Username</span>
        </label>
        <label>
            <input required="" placeholder="" type="password" name="password" class="input">
            <span>Password</span>
        </label>
        <button class="submit">Submit</button>
        <p class="signin">Don't have an account yet? <a href="register_resident.php">Sign Up</a></p>
    </form>
</div>

<script src="../../assets/js/jquery-3.7.1.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
