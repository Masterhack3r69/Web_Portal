<?php
require_once '../../config/db.php';
session_start();

header('Content-Type: application/json');

function getNotifications($userId) {
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
    try {
        $result = query($sql, [$userId]);
        $notifications = [];
        
        while ($row = $result->fetch_assoc()) {
            $notifications[] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'message' => $row['message'],
                'created_at' => $row['created_at'],
                'is_read' => $row['is_read']
            ];
        }
        
        return $notifications;
    } catch (Exception $e) {
        error_log("Error fetching notifications: " . $e->getMessage());
        return [];
    }
}

function markNotificationsAsRead($userId) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
    try {
        return query($sql, [$userId]);
    } catch (Exception $e) {
        error_log("Error marking notifications as read: " . $e->getMessage());
        return false;
    }
}

// Check both possible session variables for user ID
$userId = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;

if ($userId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle POST request for marking notifications as read
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (isset($data['action']) && $data['action'] === 'mark_as_read') {
            $success = markNotificationsAsRead($userId);
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Notifications marked as read' : 'Failed to mark notifications as read'
            ]);
        }
    } else {
        // Handle GET request for fetching notifications
        $notifications = getNotifications($userId);
        echo json_encode([
            'success' => true, 
            'notifications' => $notifications,
            'user_id' => $userId
        ]);
    }
} else {
    error_log("No user ID found in session. Session data: " . print_r($_SESSION, true));
    echo json_encode([
        'success' => false, 
        'message' => 'User not logged in',
        'session_data' => $_SESSION
    ]);
}
?>
