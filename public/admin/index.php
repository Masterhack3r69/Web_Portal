<?php
session_start();
include '../../config/db.php';
include '../alerts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = query("SELECT * FROM admin WHERE username = ?", [$username]);
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['admin_type'] = $admin['admin_type'];
        $_SESSION['department_id'] = $admin['department_id']; 

        if ($admin['admin_type'] === 'central') {
            header('Location: ./central_admin/index.php');
        } elseif ($admin['admin_type'] === 'local') {
            header('Location: ./local_admin/index.php');
        }
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid username or password."; 
    }
}

if (isset($_GET['msg'])) {
    showAlert($_GET['msg'], 'success');
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link href="../../assets/css/form_authentication.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="../../assets/css/background.css" rel="stylesheet"> -->
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="bg-light" >
<div class="container">
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
    </form>
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/jquery-3.7.1.min.js"></script>

<?php if (isset($_SESSION['error_message'])): ?>
<script>
    Swal.fire({
        title: '<?php echo $_SESSION['error_message']; ?>', 
        showConfirmButton: false,
        timer: 1000, 
        customClass: {
            popup: 'small-alert' 
        },
        css: {
            fontSize: '0.8rem' 
        }
    });
</script>
<?php 
    unset($_SESSION['error_message']); // Clear error message after displaying
endif; 
?>
<style>
    .small-alert {
        width: 300px; 
        font-size: 0.5rem; 
    }
</style>
</body>
</html>
