<?php
session_start();
include '../../config/db.php';
include '../alerts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Error handling for required fields
    if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($_POST['password'])) {
        echo "All fields are required!";
        exit;
    }

    // Validate if username or email already exists (you should implement this check)
    $userExists = query("SELECT * FROM users WHERE username = ? OR email = ?", [$username, $email]);
    if ($userExists->num_rows > 0) {
        echo "Username or email already exists!";
        exit;
    }

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, password, email, first_name, last_name, middle_name) VALUES (?, ?, ?, ?, ?, ?)";
    if (query($sql, [$username, $password, $email, $firstname, $lastname, $middlename])) {
        header('Location: login_resident.php');
        exit;
    } else {
        echo "Error inserting data into the database!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>register</title>
  <link href="../../assets/css/form_authentication.css" rel="stylesheet" >
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" >
  <!-- <link href="../../assets/css/background.css" rel="stylesheet" > -->

</head>
<body class="bg-light">
<!-- -->
<div class="container">
<form class="form" id="registerForm" action="#" method="POST">
    <p class="title">Register </p>
    <div class="flex">
        <label for="firstname">
            <input required="" placeholder="" type="text" id="firstname" name="firstname" class="input">
            <span>Firstname</span>
        </label>
        <label for="middlename">
            <input required="" placeholder="" type="text" id="middlename" name="middlename" class="input">
            <span>Middlename</span>
        </label>
        <label for="lastname">
            <input required="" placeholder="" type="text" id="lastname" name="lastname" class="input">
            <span>Lastname</span>
        </label>
    </div>
    <label for="username">
        <input required="" placeholder="" type="text" id="username" name="username" class="input">
        <span>Username</span>
    </label>
    <label for="email">
        <input required="" placeholder="" type="email" id="email" name="email" class="input">
        <span>Email</span>
    </label>
    <label for="password">
        <input required="" placeholder="" type="password" id="password" name="password" class="input">
        <span>Password</span>
    </label>
    <label for="comfirmpassword">
        <input required="" placeholder="" type="password" id="comfirmpassword" name="cofirmpassword" class="input">
        <span>Confirm password</span>  
        <span class="error" id="error-message">Passwords do not match.</span> 
    </label>
    <button class="submit" type="submit" value="Register">Submit</button>
    <p class="signin">Already have an account? <a href="login_resident.php">Sign in</a></p>
</form>

</div>
<script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('comfirmpassword');
    const errorMessage = document.getElementById('error-message');

    confirmPassword.addEventListener('input', function() {
        if (confirmPassword.value !== password.value) {
            errorMessage.style.display = 'inline';
        } else {
            errorMessage.style.display = 'none'; 
        }
    });
</script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/jquery-3.7.1.min.js"></script>
</body>
</html>


<!-- <form action="#" method="POST">
  <label for="firstname">Firstname</label>
  <input type="text" name="firstname" id="firstname">
  <label for="lastname">Lastname</label>
  <input type="text" name="lastname" id="lastname">
  <label for="username">Username</label>
  <input type="text" name="username" id="username">
  <label for="email">Email</label>
  <input type="email" name="email" id="email">
  <label for="password">Password</label>
  <input type="password" name="password" id="password">
  <label for="birthdate">Birthdate</label>
  <input type="date" name="birthdate" id="birthdate">
  <label for="address">Address</label>
  <input type="text" name="address" id="address">
  <input type="submit" value="Register">
</form>
</form> -->
