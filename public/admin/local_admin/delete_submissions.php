<?php
include '../../../config/db.php';
header('Content-Type: application/json');

if (isset($_GET['form_id']) && isset($_GET['program_id'])) {
    $form_id = $_GET['form_id'];
    $program_id = $_GET['program_id'];

    $delete_sql = "DELETE FROM form_submissions WHERE form_id = ? AND program_id = ?";
    $result = query($delete_sql, [$form_id, $program_id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Submissions deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting submissions. Please try again.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid form or program ID.']);
}

exit;
?>

