<?php
include '../../../config/db.php';


header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

$input = json_decode(file_get_contents('php://input'), true);
$form_id = isset($input['id']) ? intval($input['id']) : 0;

if ($form_id > 0) {
    $sql = "DELETE FROM forms WHERE id = ?";
    $result = query($sql, [$form_id]);

    if ($result) {
        $response['success'] = true;
        $response['message'] = "Form deleted successfully!";
    } else {
        $response['message'] = "Error deleting the form.";
    }
} else {
    $response['message'] = "Invalid form ID.";
}

echo json_encode($response);
exit;
