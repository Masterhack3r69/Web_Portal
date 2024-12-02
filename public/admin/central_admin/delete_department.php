<?php
include '../../../config/db.php';
header('Content-Type: application/json'); 
$response = ['success' => false, 'message' => ''];

if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    $sql = "SELECT logo FROM departments WHERE id = ?";
    $result = query($sql, [$department_id]);

    if ($result && $department = $result->fetch_assoc()) {
        $logoPath = '../../../assets/img/uploads/' . $department['logo']; 
    
            if (file_exists($logoPath)) {
            if (!unlink($logoPath)) {
                $response['message'] = "Error: Unable to delete logo file.";
                echo json_encode($response);
                exit;
            }
        }

        $sql = "DELETE FROM departments WHERE id = ?";
        
        try {
            $result = query($sql, [$department_id]);

            if ($result) {
                $response['success'] = true;
                $response['message'] = "Department deleted successfully!";
                audit_log('department', 'Delete', 'Department deleted successfully');
            } else {
                $response['message'] = "Error: Unable to delete the department.";
                audit_log('department', 'Delete Failed', 'Failed to delete department');
            }
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
            audit_log('department', 'Delete Error', 'Error deleting department' . $e->getMessage());
        }
    } else {
        $response['message'] = "Department not found.";
        audit_log('department', 'Delete Not Found', 'Department not found');
    }
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
exit;
?>
