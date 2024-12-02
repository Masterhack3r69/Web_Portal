<?php
require '../../../vendor/autoload.php';
include '../../../config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ob_end_clean(); // Clear output buffer to prevent corrupt output

if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];
    
    // Fetch program info
    $program_info_sql = "SELECT title, form_id FROM programs WHERE id = ?";
    $program_info_result = query($program_info_sql, [$program_id]);
    
    if ($program_info_result->num_rows > 0) {
        $program = $program_info_result->fetch_assoc();
        $program_title = htmlspecialchars($program['title']);
        $form_id = $program['form_id'];
        
        // Base query
        $sql = "SELECT id, submission_data, status, created_at FROM form_submissions WHERE form_id = ? AND program_id = ?";
        $params = [$form_id, $program_id];
        
        // Add date filters if provided
        if (!empty($_GET['start_date'])) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $_GET['start_date'];
        }
        if (!empty($_GET['end_date'])) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $_GET['end_date'];
        }
        
        // Add status filter if provided
        if (!empty($_GET['status'])) {
            $sql .= " AND status = ?";
            $params[] = $_GET['status'];
        }
        
        $submissions_result = query($sql, $params);
        $submissions = [];
        
        // Apply additional filters that can't be done in SQL
        while ($row = $submissions_result->fetch_assoc()) {
            $submission_data = json_decode($row['submission_data'], true);
            $include_row = true;
            
            // Apply text search filter
            if (!empty($_GET['search'])) {
                $search_text = strtolower($_GET['search']);
                $row_text = strtolower(implode(' ', array_values($submission_data)));
                if (strpos($row_text, $search_text) === false) {
                    $include_row = false;
                }
            }
            
            // Apply age filter
            if (!empty($_GET['age']) && isset($submission_data['age'])) {
                $age = intval($submission_data['age']);
                $age_range = explode('-', str_replace('+', '', $_GET['age']));
                if (count($age_range) === 2) {
                    if (!($age >= intval($age_range[0]) && $age <= intval($age_range[1]))) {
                        $include_row = false;
                    }
                } else {
                    if (!($age >= intval($age_range[0]))) {
                        $include_row = false;
                    }
                }
            }
            
            // Apply sex filter
            if (!empty($_GET['sex']) && isset($submission_data['sex'])) {
                if (strtolower($submission_data['sex']) !== strtolower($_GET['sex'])) {
                    $include_row = false;
                }
            }
            
            // Apply municipality filter
            if (!empty($_GET['municipality']) && isset($submission_data['municipality'])) {
                if ($submission_data['municipality'] !== $_GET['municipality']) {
                    $include_row = false;
                }
            }
            
            // Apply barangay filter
            if (!empty($_GET['barangay']) && isset($submission_data['barangay'])) {
                if ($submission_data['barangay'] !== $_GET['barangay']) {
                    $include_row = false;
                }
            }
            
            if ($include_row) {
                $submissions[] = $row;
            }
        }
        
        // Initialize Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headerRow = ['#'];
        if (!empty($submissions)) {
            $first_submission = json_decode($submissions[0]['submission_data'], true);
            foreach ($first_submission as $key => $value) {
                if ($key !== 'uploaded_files') {
                    $headerRow[] = htmlspecialchars($key);
                }
            }
            $headerRow[] = 'Status';
            $headerRow[] = 'Date Applied';
        }
        
        // Add headers to the sheet
        foreach ($headerRow as $col => $header) {
            $sheet->setCellValue(chr(65 + $col) . '1', $header);
            // Make headers bold
            $sheet->getStyle(chr(65 + $col) . '1')->getFont()->setBold(true);
        }
        
        // Add submission data rows
        $rowCount = 2;
        foreach ($submissions as $index => $submission) {
            $sheet->setCellValue('A' . $rowCount, $index + 1);
            $submission_data = json_decode($submission['submission_data'], true);
            
            $colCount = 1;
            foreach ($submission_data as $key => $value) {
                if ($key !== 'uploaded_files') {
                    $sheet->setCellValue(chr(65 + $colCount) . $rowCount, htmlspecialchars($value));
                    $colCount++;
                }
            }
            $sheet->setCellValue(chr(65 + $colCount) . $rowCount, htmlspecialchars($submission['status']));
            $colCount++;
            $sheet->setCellValue(chr(65 + $colCount) . $rowCount, date('M d, Y', strtotime($submission['created_at'])));
            $rowCount++;
        }
        
        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the download headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $program_title . '_submissions.xlsx"');
        header('Cache-Control: max-age=0');
        
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
