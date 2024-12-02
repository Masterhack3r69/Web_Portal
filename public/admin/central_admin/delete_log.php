<?php
session_start();
include '../../../config/db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Failed to delete audit logs. Please try again.'];

$deleteQuery = "DELETE FROM audit_logs";
$result = query($deleteQuery);

if ($result) {
    $response['success'] = true;
    $response['message'] = "All audit logs have been deleted successfully.";
} else {
    $response['message'] = "Error: Unable to delete audit logs.";
}

echo json_encode($response);
exit;
?>