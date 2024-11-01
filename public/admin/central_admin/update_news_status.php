<?php
include '../../../config/db.php'; 

$data = json_decode(file_get_contents('php://input'), true);
$newsId = $data['id'];
$status = $data['status'];
$rejectionReason = isset($data['rejection_reason']) ? $data['rejection_reason'] : null;

if ($status === 'rejected' && $rejectionReason) {
    // Store rejection reason in the database if needed
    $query = "UPDATE news SET status = ?, rejection_reason = ? WHERE id = ?";
    $params = [$status, $rejectionReason, $newsId];
} else {
    $query = "UPDATE news SET status = ? WHERE id = ?";
    $params = [$status, $newsId];
}

// Use the query function to execute the SQL
$result = query($query, $params);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
}
?>

