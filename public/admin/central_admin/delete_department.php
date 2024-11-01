<?php
include '../../../config/db.php';
header('Content-Type: application/json'); 
$response = ['success' => false, 'message' => ''];

if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    // Fetch the logo path
    $sql = "SELECT logo FROM departments WHERE id = ?";
    $result = query($sql, [$department_id]);

    if ($result && $department = $result->fetch_assoc()) {
        $logoPath = '../../../assets/img/uploads/' . $department['logo']; // This should be the unique name stored in the database.
    
        // Check if the file exists and try to delete it
        if (file_exists($logoPath)) {
            if (!unlink($logoPath)) {
                $response['message'] = "Error: Unable to delete logo file.";
                echo json_encode($response);
                exit;
            }
        }

        // Prepare to delete the department
        $sql = "DELETE FROM departments WHERE id = ?";
        
        try {
            $result = query($sql, [$department_id]);

            if ($result) {
                $response['success'] = true;
                $response['message'] = "Department deleted successfully!";
            } else {
                $response['message'] = "Error: Unable to delete the department.";
            }
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $response['message'] = "Department not found.";
    }
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
exit;
?>
