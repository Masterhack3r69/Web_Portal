<?php
include '../../../config/db.php';
header('Content-Type: application/json'); 

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    $sql = "SELECT banner_image FROM programs WHERE id = ?";
    $result = query($sql, [$program_id]);

    if ($result && $program = $result->fetch_assoc()) {
        $bannerPath = '../../../assets/img/uploads/' . $program['banner_image'];
    
        // Check if the banner exists and delete it
        if (file_exists($bannerPath)) {
            unlink($bannerPath);
        }

        $sql = "DELETE FROM programs WHERE id = ?";

        try {
            $result = query($sql, [$program_id]);

            if ($result) {
                $response['success'] = true;
                $response['message'] = "Program deleted successfully!";
                audit_log('program', 'Delete', 'Program has been deleted');
            } else {
                $response['message'] = "Error: Unable to delete the program.";
                audit_log('program', 'Delete Failed', 'Failed to delete program');
            }
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
            audit_log('program', 'Delete Error', 'Error deleting program');
        }
    } else {
        $response['message'] = "Program not found.";
        audit_log('program', 'Delete Not Found', 'Program not found');
    }
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
exit;
?>
