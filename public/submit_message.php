<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $feedback_type = $_POST['feedback_type'];
    $department_id = $_POST['department_id']; 
    $message = $_POST['message'];
    

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $feedback_type = htmlspecialchars($feedback_type, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $department_id = $department_id ? filter_var($department_id, FILTER_SANITIZE_NUMBER_INT) : null; // Sanitize or set to null

    $query = "INSERT INTO messages (email, feedback_type, message, status, department_id) VALUES (?, ?, ?, 'unread', ?)";
    
    if (query($query, [$email, $feedback_type, $message, $department_id])) {
        echo "Feedback sent successfully.";
    } else {
        echo "Error sending feedback.";
    }
}
?>
