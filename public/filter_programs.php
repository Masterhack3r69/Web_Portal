<?php
include '../config/db.php';

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];

    // Query the programs for the selected department
    $sql = "SELECT p.id, p.title, p.description, p.banner_image, d.department_name 
            FROM programs p 
            INNER JOIN departments d ON p.department_id = d.id 
            WHERE p.department_id = ?";
    $programs = query($sql, [$department_id])->fetch_all(MYSQLI_ASSOC);

    // Check if there are programs for the selected department
    if (empty($programs)) {
        echo "<div class='col-12 text-center py-5'>
                <h5 class='text-muted'>No available programs for this department.</h5>
              </div>";
    } else {
        // Display each program
        foreach ($programs as $program) :
            echo "<div class='col-md-4 mb-3'>
                    <div class='card card-box h-100'>
                        <img src='../assets/img/uploads/" . $program['banner_image'] . "' class='card-img-top' style='height: 150px;' alt='Banner'>
                        <div class='card-body pb-2'>
                            <h6 class='card-title'>" . $program['title'] . "</h6>
                            <div class='multi-line-text-truncate-3 mt-0'>
                                " . $program['description'] . "
                            </div>
                        </div>
                        <a href='view_program.php?id=" . $program['id'] . "' class='btn py-2 mx-5'>Learn More</a>
                    </div>
                  </div>";
        endforeach;
    }
}
