<?php
include '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
    
    $programs = query("SELECT id, title FROM programs WHERE department_id = ?", [$department_id])->fetch_all(MYSQLI_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($programs);
    exit; 
}
?>
