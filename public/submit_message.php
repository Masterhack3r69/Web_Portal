<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $feedback_type = $_POST['feedback_type'];
    $message = $_POST['message'];

    // Sanitize inputs
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $feedback_type = htmlspecialchars($feedback_type, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Insert feedback into the database with status set to 'unread'
    $query = "INSERT INTO messages (email, feedback_type, message, status) VALUES (?, ?, ?, 'unread')";
    
    if (query($query, [$email, $feedback_type, $message])) {
        echo "Feedback sent successfully.";
    } else {
        echo "Error sending feedback.";
    }
}
?>
