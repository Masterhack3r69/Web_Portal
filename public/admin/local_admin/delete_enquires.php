<?php
session_start();
include '../../../config/db.php'; 

header('Content-Type: application/json'); 

$response = ['success' => false, 'message' => 'Failed to delete news record. Please try again.'];

if (isset($_GET['id'])) {
    $newsId = $_GET['id'];
    $deleteQuery = "DELETE FROM news WHERE id = ?";
    $result = query($deleteQuery, [$newsId]);

    if ($result) {
        $response['success'] = true;
        $response['message'] = "News record has been deleted successfully.";
    }
} else {
    $response['message'] = "No news record specified for deletion.";
}

echo json_encode($response);
exit;
?>