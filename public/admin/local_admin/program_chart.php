<?php

include '../../../config/db.php';

session_start();
$department_id = $_SESSION['department_id'];

$programs_sql = "SELECT title, id FROM programs WHERE department_id = ?";
$programs_result = query($programs_sql, [$department_id]);
$programs = $programs_result->fetch_all(MYSQLI_ASSOC);

$labels = [];
$applications = [];
foreach ($programs as $program) {
    $programs_id = $program['id'];
    $programs_title = $program['title'];
    
    $applications_sql = "SELECT COUNT(*) as total_applications FROM form_submissions WHERE program_id = ?";
    $applications_result = query($applications_sql, [$programs_id]);
    $applications_data = $applications_result->fetch_assoc();
    $total_applications = $applications_data['total_applications'];
    
    $labels[] = $programs_title;
    $applications[] = $total_applications;
}

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'applications' => $applications,
]);
