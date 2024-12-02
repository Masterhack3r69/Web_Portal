<?php
include '../../../config/db.php';

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];

    $sql = "SELECT p.id, p.title, p.description, p.banner_image, d.department_name 
            FROM programs p 
            INNER JOIN departments d ON p.department_id = d.id 
            WHERE p.department_id = ?";
    $programs = query($sql, [$department_id])->fetch_all(MYSQLI_ASSOC);

    if (empty($programs)) {
        echo "<div class='col-12 text-center py-5'>
                <p class='text-muted'>No available programs for this department.</p>
              </div>";
    } else {
        foreach ($programs as $program) {
            echo "<div class='col-md-6 mb-3'>
                    <div class='card mb-3 border h-100'>
                        <div class='card-img-container' style='position: relative; height: 150px; border-radius: 15px 15px 0 0;'>
                            <img src='../../../assets/img/uploads/{$program['banner_image']}' 
                                 alt='Banner' 
                                 style='height: 100%; width: 100%; object-fit: cover; border-radius: 15px 15px 0 0;'>
                            <div class='card-img-overlay d-flex align-items-center justify-content-center'>
                                <div class='bg-blur p-2 rounded' style='background-color: rgba(0,0,0, 0.2);'>
                                    <h5 class='text-center text-white m-0'>{$program['title']}</h5>
                                </div>
                            </div>
                        </div>
                        <div class='card-body py-0'>
                            <div class='program-card'>
                                <div class='multi-line-text-truncate mt-0' style='height: 100px;'>
                                    <p class='text-justify' style='overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>
                                        {$program['description']}
                                    </p>
                                </div>
                                <hr class='horizontal dark m-0'>
                                <div class='d-flex justify-content-end mt-3 align-middle'>
                                    <a href='view_program.php?id={$program['id']}' class='btn bg-gradient-info me-2 px-3 mb-0'><i class='fa fa-eye'></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>";
        }
    }
}
