<?php
include '../../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$formId = $data['id'];

$sql = "SELECT * FROM forms WHERE id = ?";
$originalForm = query($sql, [$formId]);

if ($originalForm && $originalForm->num_rows > 0) {
    $formData = $originalForm->fetch_assoc();
    
    $baseName = $formData['form_name'];
    $newName = $baseName;
    $counter = 1;

    while (true) {
        $checkSql = "SELECT COUNT(*) AS count FROM forms WHERE form_name = ?";
        $countResult = query($checkSql, [$newName]);
        
        if ($countResult && $countResult->num_rows > 0) {
            $countRow = $countResult->fetch_assoc();
            if ($countRow['count'] == 0) {
                break;
            } else {
                $newName = $baseName . "($counter)";
                $counter++;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error checking form names.']);
            exit;
        }
    }

    $department_id = $formData['department_id']; 

    $copySql = "INSERT INTO forms (form_name, form_html, department_id, created_at) VALUES (?, ?, ?, NOW())";
    $isCopied = query($copySql, [$newName, $formData['form_html'], $department_id]);
    
    if ($isCopied) {
        audit_log('form', 'Form Copied', 'A form was copied with the new name: ' . $newName);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
