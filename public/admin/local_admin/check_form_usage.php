<?php
include '../../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$form_id = $data['id'];

$sql = "SELECT p.id, p.title FROM programs p WHERE p.form_id = ?";
$result = query($sql, [$form_id]);

$programs = [];
$programTitles = [];
$inUse = false;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
        $programTitles[] = $row['title'];
    }
    $inUse = count($programs) > 0;
}

echo json_encode([
    'inUse' => $inUse,
    'programs' => $programs,
    'programList' => implode(', ', $programTitles)
]);
exit;