<?php
session_start();
require_once '../../../config/db.php';

// Check if user is logged in and has appropriate permissions
if (!isset($_SESSION['department_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $form_id = isset($input['id']) ? intval($input['id']) : 0;

    if ($form_id <= 0) {
        throw new Exception("Invalid form ID.");
    }

    // Check if the form belongs to the department
    $check_sql = "SELECT id FROM forms WHERE id = ? AND department_id = ?";
    $check_result = query($check_sql, [$form_id, $_SESSION['department_id']]);
    
    if (!$check_result || $check_result->num_rows === 0) {
        throw new Exception("Form not found or unauthorized access.");
    }

    // Check if the form is being used by any programs
    $program_check_sql = "SELECT id FROM programs WHERE form_id = ?";
    $program_result = query($program_check_sql, [$form_id]);
    
    if ($program_result && $program_result->num_rows > 0) {
        throw new Exception("Cannot delete form as it is being used by one or more programs.");
    }

    // Delete the form
    $delete_sql = "DELETE FROM forms WHERE id = ? AND department_id = ?";
    $delete_result = query($delete_sql, [$form_id, $_SESSION['department_id']]);

    if ($delete_result) {
        $response['success'] = true;
        $response['message'] = "Form deleted successfully!";
        
        // Log the action if audit_log function exists
        if (function_exists('audit_log')) {
            audit_log('form', 'Delete', "Form ID: {$form_id} has been deleted");
        }
    } else {
        throw new Exception("Error deleting the form.");
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    
    // Log the error if audit_log function exists
    if (function_exists('audit_log')) {
        audit_log('form', 'Delete Failed', $e->getMessage());
    }
}

echo json_encode($response);
exit;
