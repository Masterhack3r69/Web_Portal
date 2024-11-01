<?php
include '../../../config/db.php';

function getMonthlyRegistrations() {
    $sql = "SELECT MONTH(created_at) AS month, COUNT(*) AS count
            FROM users
            GROUP BY MONTH(created_at)
            ORDER BY month ASC";
    $result = query($sql);
    
    $monthlyData = array_fill(0, 12, 0);  
    while ($row = $result->fetch_assoc()) {
        $monthlyData[(int)$row['month'] - 1] = (int)$row['count'];
    }
    return $monthlyData;
}

header('Content-Type: application/json');
echo json_encode(getMonthlyRegistrations());
?>
