<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Sanitize inputs
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Insert message into the database with status set to 'unread'
    $query = "INSERT INTO messages (email, message, status) VALUES (?, ?, 'unread')";
    
    if (query($query, [$email, $message])) {
        echo "Message sent successfully.";
    } else {
        echo "Error sending message.";
    }
}

?>
