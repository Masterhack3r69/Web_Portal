<?php
session_start();
include '../../config/db.php';
include '../alerts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Error handling for required fields
    if (empty($firstname) || empty($lastname) || empty($username) || empty($phone) || empty($email) || empty($_POST['password'])) {
        $_SESSION['error'] = "All fields are required";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Validate phone number format
    if (!preg_match('/^09\d{9}$/', $phone)) {
        $_SESSION['error'] = "Phone number must be 11 digits and start with 09";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Additional email validation for allowed domains
    $allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];
    $emailParts = explode('@', $email);
    $domain = end($emailParts);
    
    if (!in_array(strtolower($domain), $allowedDomains)) {
        $_SESSION['error'] = "Please use gmail.com, yahoo.com, outlook.com, or hotmail.com";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Check for disposable email domains
    if (strpos($email, 'tempmail') !== false || strpos($email, 'throwaway') !== false) {
        $_SESSION['error'] = "Disposable email addresses are not allowed";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Check for duplicate names
    $nameExists = query("SELECT * FROM users WHERE first_name = ? AND last_name = ?", [$firstname, $lastname]);
    if ($nameExists->num_rows > 0) {
        $_SESSION['error'] = "This name is already registered in the system";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Validate if username, phone, or email already exists
    $userExists = query("SELECT * FROM users WHERE username = ? OR phone = ? OR email = ?", [$username, $phone, $email]);
    if ($userExists->num_rows > 0) {
        $existingUser = $userExists->fetch_assoc();
        if ($existingUser['username'] === $username) {
            $_SESSION['error'] = "This username is already taken";
        } elseif ($existingUser['email'] === $email) {
            $_SESSION['error'] = "This email is already registered";
        } elseif ($existingUser['phone'] === $phone) {
            $_SESSION['error'] = "This phone number is already registered";
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, password, phone, email, first_name, last_name, middle_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
    try {
        if (query($sql, [$username, $password, $phone, $email, $firstname, $lastname, $middlename])) {
            $_SESSION['success'] = "success";
            header('Location: login_resident.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Registration failed. Please try again";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../../../assets/img/icon.png">
  <title>register</title>
  <link href="https://fonts.googleapis.com/css2?family=Danfo&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" >
  <link href="../../assets/css/form_authentication.css" rel="stylesheet" >
</head>
<body class="bg-light">

<div class="container my-4">
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
        <img src="../../assets/img/icon.png" alt="PGDIPS Icon" style="width: 80px; height: 80px;">
        <h3 style="color:#17A567; font-weight: bold;">PGDI<span style="color:#F1464B; ">PS</span></h3>
    </div>
<form class="form" id="registerForm" action="#" method="POST">
    <p class="title">Register </p>

    <div class="flex">
        <label for="firstname">
            <input required="" placeholder="" type="text" id="firstname" name="firstname" class="input" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
            <span>Firstname</span>
        </label>
        <label for="middlename">
            <input required="" placeholder="" type="text" id="middlename" name="middlename" class="input" value="<?php echo isset($_POST['middlename']) ? htmlspecialchars($_POST['middlename']) : ''; ?>">
            <span>Middlename</span>
        </label>
        <label for="lastname">
            <input required="" placeholder="" type="text" id="lastname" name="lastname" class="input" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
            <span>Lastname</span>
        </label>
    </div>
    <label for="username">
        <input required="" placeholder="" type="text" id="username" name="username" class="input" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        <span>Username</span>
        <span class="error" id="username-error"></span>
    </label>
    <label for="phone">
        <input required="" placeholder="" type="text" id="phone" name="phone" class="input" pattern="09[0-9]{9}" maxlength="11" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
        <span>Phone Number</span>
        <span class="error" id="phone-error"></span>
    </label>
    <label for="email">
        <input required="" placeholder="" type="email" id="email" name="email" class="input" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <span>Email (email@example.com)</span>
        <span class="error" id="email-error"></span>
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
    <p class="signin mb-0">Already have an account? <a href="login_resident.php">Sign in</a>. <a href="/public/index.php">Go Back</a></p>
</form>

</div>
<script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('comfirmpassword');
    const errorMessage = document.getElementById('error-message');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const usernameInput = document.getElementById('username');
    
    // Error message elements
    const emailError = document.getElementById('email-error');
    const phoneError = document.getElementById('phone-error');
    const usernameError = document.getElementById('username-error');

    // Set error messages from PHP session if they exist
    window.onload = function() {
        <?php if(isset($_SESSION['error'])): ?>
            const errorType = "<?php echo $_SESSION['error']; ?>";
            if(errorType.includes('username')) {
                usernameError.textContent = errorType;
                usernameError.style.display = 'inline';
                usernameInput.setCustomValidity('Invalid username');
            } else if(errorType.includes('email')) {
                emailError.textContent = errorType;
                emailError.style.display = 'inline';
                emailInput.setCustomValidity('Invalid email');
            } else if(errorType.includes('phone')) {
                phoneError.textContent = errorType;
                phoneError.style.display = 'inline';
                phoneInput.setCustomValidity('Invalid phone');
            }
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    };

    confirmPassword.addEventListener('input', function() {
        if (confirmPassword.value !== password.value) {
            errorMessage.style.display = 'inline';
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            errorMessage.style.display = 'none';
            confirmPassword.setCustomValidity('');
        }
    });

    // Phone number validation
    phoneInput.addEventListener('input', function() {
        const phone = phoneInput.value;
        const phoneRegex = /^09\d{9}$/;
        
        // Remove any non-numeric characters
        phoneInput.value = phone.replace(/[^0-9]/g, '');
        
        if (phone.length === 0) {
            phoneError.textContent = 'Phone number is required';
            phoneError.style.display = 'inline';
            phoneInput.setCustomValidity('Phone number is required');
        } else if (!phoneRegex.test(phone)) {
            phoneError.textContent = 'Phone number must be 11 digits and start with 09';
            phoneError.style.display = 'inline';
            phoneInput.setCustomValidity('Invalid phone format');
        } else {
            phoneError.style.display = 'none';
            phoneInput.setCustomValidity('');
        }
    });

    emailInput.addEventListener('input', function() {
        const email = emailInput.value;
        const allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address';
            emailError.style.display = 'inline';
            emailInput.setCustomValidity('Invalid email format');
        } else {
            const domain = email.split('@')[1];
            if (!allowedDomains.includes(domain.toLowerCase())) {
                emailError.textContent = 'Please use gmail.com, yahoo.com, outlook.com, or hotmail.com';
                emailError.style.display = 'inline';
                emailInput.setCustomValidity('Invalid email domain');
            } else {
                emailError.style.display = 'none';
                emailInput.setCustomValidity('');
            }
        }
    });

    // Clear error messages when input changes
    usernameInput.addEventListener('input', function() {
        usernameError.style.display = 'none';
        usernameInput.setCustomValidity('');
    });
</script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/jquery-3.7.1.min.js"></script>
</body>
</html>
