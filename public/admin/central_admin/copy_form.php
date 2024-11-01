<?php
include '../../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$formId = $data['id'];

// Fetch the original form using the existing query function
$sql = "SELECT * FROM forms WHERE id = ?";
$originalForm = query($sql, [$formId]);

if ($originalForm && $originalForm->num_rows > 0) {
    $formData = $originalForm->fetch_assoc();
    
    // Prepare the new form name
    $baseName = $formData['form_name'];
    $newName = $baseName;
    $counter = 1;

    // Check for existing forms with the same name to create a unique name
    while (true) {
        // Check if the new name already exists
        $checkSql = "SELECT COUNT(*) AS count FROM forms WHERE form_name = ?";
        $countResult = query($checkSql, [$newName]);
        
        if ($countResult && $countResult->num_rows > 0) {
            $countRow = $countResult->fetch_assoc();
            if ($countRow['count'] == 0) {
                // If the name doesn't exist, we can use it
                break;
            } else {
                // If it exists, increment the counter and update the new name
                $newName = $baseName . "($counter)";
                $counter++;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error checking form names.']);
            exit;
        }
    }

    $copySql = "INSERT INTO forms (form_name, form_html, created_at) VALUES (?, ?, NOW())";
    $isCopied = query($copySql, [$newName, $formData['form_html']]);
    
    if ($isCopied) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
