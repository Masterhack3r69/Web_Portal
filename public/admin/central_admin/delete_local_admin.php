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
            } else {
                $response['message'] = "Error: Unable to delete the local admin.";
            }
        } else {
            $response['message'] = "Error: Admin not found.";
        }
    } catch (Exception $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request. Admin ID missing.";
}

echo json_encode($response);
exit;
?>
