<!-- <?php

include '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submission_id = $_POST['submission_id'];
    $status = $_POST['status'];

    $sql = "UPDATE form_submissions SET status = ? WHERE id = ?";
    if (query($sql, [$status, $submission_id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
