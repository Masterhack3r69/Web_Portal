<?php
include '../../../config/db.php'; 

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'News not found.'];

if (isset($_GET['id'])) {
    $newsId = $_GET['id'];
    $newsQuery = query("SELECT n.id, n.title, n.small_description, d.department_name, p.title AS program_title, a.username AS created_by, n.status, n.image_url
        FROM news n
        JOIN departments d ON n.department_id = d.id
        JOIN programs p ON n.program_id = p.id
        JOIN admin a ON n.created_by = a.id
        WHERE n.id = ?", [$newsId])->fetch_assoc();

    if ($newsQuery) {
        $response['success'] = true;
        $response['news'] = $newsQuery;
    }
}

echo json_encode($response);
exit;
?>
