<?php
include '../../../config/db.php';
header('Content-Type: application/json'); 

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    try {
        // Check if the admin exists
        $check_sql = "SELECT * FROM admin WHERE id = ?";
        $admin = query($check_sql, [$admin_id])->fetch_assoc();

        if ($admin) {
            // Perform the delete operation
            $delete_sql = "DELETE FROM admin WHERE id = ?";
            $result = query($delete_sql, [$admin_id]);

            if ($result) {
                $response['success'] = true;
                $response['message'] = "Local admin deleted successfully!";
                audit_log('local_admin', 'Delete', 'Local admin has been deleted');
            } else {
                $response['message'] = "Error: Unable to delete the local admin.";
                audit_log('local_admin', 'Delete Failed', 'Failed to delete local admin');
            }
        } else {
            $response['message'] = "Error: Admin not found.";
            audit_log('local_admin', 'Delete Not Found', 'Local admin not found while trying to delete');
        }
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
            audit_log('local_admin', 'Delete Error', 'Error deleting local admin');
        }
    } else {
        $response['message'] = "Invalid request. Admin ID missing.";
        audit_log('local_admin', 'Delete Invalid Request', 'Invalid request to delete local admin ');
    }

echo json_encode($response);
exit;
?>
