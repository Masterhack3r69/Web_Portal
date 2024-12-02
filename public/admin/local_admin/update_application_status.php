<?php
header('Content-Type: application/json');
require_once '../../../config/db.php';
require_once '../../../include/mail_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submission_id = $_POST['submission_id'];
    $status = $_POST['status'];

    // First, get the user_id and email from the form submission
    $get_user_sql = "SELECT fs.user_id, u.username, u.email 
                     FROM form_submissions fs 
                     JOIN users u ON fs.user_id = u.id 
                     WHERE fs.id = ?";
    $result = query($get_user_sql, [$submission_id]);
    $submission = $result->fetch_assoc();

    if ($submission) {
        // Update the submission status
        $update_sql = "UPDATE form_submissions SET status = ? WHERE id = ?";
        if (query($update_sql, [$status, $submission_id])) {
            // Create notification
            $notification_type = ($status === 'approved') ? 'application_approved' : 'application_rejected';
            $message = ($status === 'approved') 
                ? "Your application has been approved. Please wait for SMS with more details."
                : "Your application has been rejected. We apologize for any inconvenience.";

            // Insert notification
            $notif_sql = "INSERT INTO notifications (user_id, type, message, created_at) VALUES (?, ?, ?, NOW())";
            try {
                $notif_result = query($notif_sql, [$submission['user_id'], $notification_type, $message]);
                
                // Prepare and send email
                $email_subject = ($status === 'approved') ? "Application Approved" : "Application Status Update";
                $email_message = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; }
                            .container { padding: 20px; }
                            .header { color: #333; font-size: 24px; margin-bottom: 20px; }
                            .content { margin-bottom: 20px; }
                            .footer { color: #666; font-size: 14px; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>Application Status Update</div>
                            <div class='content'>
                                <p>Dear {$submission['username']},</p>
                                <p>{$message}</p>
                                " . ($status === 'approved' ? "<p>Our team will contact you soon with further instructions.</p>" : "") . "
                            </div>
                            <div class='footer'>
                                <p>Best regards,<br>PGDIPS Team</p>
                            </div>
                        </div>
                    </body>
                    </html>";
                
                $email_sent = sendEmail($submission['email'], $email_subject, $email_message);
                
                if ($notif_result && $email_sent) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Status updated, notification and email sent to ' . $submission['username']
                    ]);
                } else if ($notif_result) {
                    error_log("Failed to send email to: " . $submission['email']);
                    echo json_encode([
                        'success' => true,
                        'warning' => 'Status updated and notification sent, but email delivery failed'
                    ]);
                } else {
                    error_log("Failed to insert notification for user_id: " . $submission['user_id']);
                    echo json_encode([
                        'success' => true,
                        'warning' => 'Status updated but notification and email failed to send'
                    ]);
                }
            } catch (Exception $e) {
                error_log("Error creating notification or sending email: " . $e->getMessage());
                echo json_encode([
                    'success' => true,
                    'warning' => 'Status updated but notification and email failed to send'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Submission not found']);
    }
}
?>
