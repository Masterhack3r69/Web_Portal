<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    $sql = "INSERT INTO messages (email, message) VALUES (?, ?)";
    $params = [$email, $message];

    if (query($sql, $params)) {
        echo "Message sent successfully.";
    } else {
        echo "Error: Could not send the message.";
    }
}
?>
