<?php
require '../../../vendor/autoload.php';
include '../../../config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ob_end_clean(); 
if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];
    
    // Fetch program info
    $program_info_sql = "SELECT title, form_id FROM programs WHERE id = ?";
    $program_info_result = query($program_info_sql, [$program_id]);
    
    if ($program_info_result->num_rows > 0) {
        $program = $program_info_result->fetch_assoc();
        $program_title = htmlspecialchars($program['title']);
        $form_id = $program['form_id'];
        
        // Fetch submissions
        $sql = "SELECT id, submission_data, status FROM form_submissions WHERE form_id = ? AND program_id = ?";
        $submissions = query($sql, [$form_id, $program_id])->fetch_all(MYSQLI_ASSOC);
        
        // Initialize Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headerRow = ['#'];
        if (!empty($submissions)) {
            $first_submission = json_decode($submissions[0]['submission_data'], true);
            foreach ($first_submission as $key => $value) {
                $headerRow[] = htmlspecialchars($key);
            }
            $headerRow[] = 'Status';
        }
        
        // Add headers to the sheet
        foreach ($headerRow as $col => $header) {
            $sheet->setCellValue(chr(65 + $col) . '1', $header);
        }
        
        // Add submission data rows
        $rowCount = 2;
        foreach ($submissions as $index => $submission) {
            $sheet->setCellValue('A' . $rowCount, $index + 1);
            $submission_data = json_decode($submission['submission_data'], true);
            
            $colCount = 1;
            foreach ($submission_data as $value) {
                $sheet->setCellValue(chr(65 + $colCount) . $rowCount, htmlspecialchars($value));
                $colCount++;
            }
            $sheet->setCellValue(chr(65 + $colCount) . $rowCount, htmlspecialchars($submission['status']));
            $rowCount++;
        }
        
        // Set the download headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$program_title}_submissions.xlsx\"");
        
        // Write and download the file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
        
    } else {
        echo "<p>Program not found.</p>";
    }
} else {
    echo "<p>Invalid program ID.</p>";
}
?>

