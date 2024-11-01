<?php
include '../../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$form_id = $data['id'];

$sql = "SELECT COUNT(*) as count FROM programs WHERE form_id = ?";
$result = query($sql, [$form_id]);

$inUse = false;
if ($result && $row = $result->fetch_assoc()) {
    $inUse = $row['count'] > 0;
}

echo json_encode(['inUse' => $inUse]);
exit;

