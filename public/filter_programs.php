<?php
include '../config/db.php';

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];

    if ($department_id === '') {
        $sql = "SELECT p.id, p.title, p.description, p.banner_image, d.department_name 
                FROM programs p 
                INNER JOIN departments d ON p.department_id = d.id";
    } else {
        $sql = "SELECT p.id, p.title, p.description, p.banner_image, d.department_name 
                FROM programs p 
                INNER JOIN departments d ON p.department_id = d.id 
                WHERE p.department_id = ?";
    }

    $programs = query($sql, $department_id !== 'all' ? [$department_id] : [])->fetch_all(MYSQLI_ASSOC);

    if (empty($programs)) {
        echo "<div class='col-12 text-center py-5'>
                <h5 class='text-muted'>No available programs for this department.</h5>
              </div>";
    } else {
        foreach ($programs as $program) {
            echo "<div class='col-md-4 h-100 rounded'>
                    <div class='card card-box h-100'>
                        <img src='../assets/img/uploads/" . $program['banner_image'] . "' class='img-fluid rounded-top' style='height: 150px; object-fit: cover;' alt='Banner'>
                        <div class='card-bod py-1'>
                            <div class='card-title p-1 m-0 text-dark fw-bold text-center'>" . $program['title'] . "</div>
                            <hr class='horizontal dark m-0 mb-2'>
                            <div class='multi-line-text-truncate-4 mt-0 px-3'>
                                " . $program['description'] . "
                            </div>
                        </div>
                        <a href='view_program.php?id=" . htmlspecialchars($program['id']) . "' class='btn py-2 mx-5'>Learn More</a>
                    </div>
                  </div>";
                  
        }
    }
}

