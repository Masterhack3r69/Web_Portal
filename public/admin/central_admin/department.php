<?php
$title = "Department";
include '../includes/header.php'; 

$sql = "SELECT d.*, a.username AS local_admin FROM departments d LEFT JOIN admin a ON d.id = a.department_id AND a.admin_type = 'local' "; 
$departments = query($sql)->fetch_all(MYSQLI_ASSOC);    
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    Departments 
                    <a href="create_department.php" class="btn bg-gradient-primary float-end d-none d-lg-inline">Add Department</a>
                    <a href="create_department.php" class="btn bg-gradient-primary float-end d-inline d-lg-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add Department">
                        <i class="fa fa-plus"></i>
                    </a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                <?php if (count($departments) > 0): ?>
                    <?php foreach ($departments as $department): ?>
                        <div class="col-md-12">
                            <div class="card mb-3 shadow-none border">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-2 text-center d-flex justify-content-center  align-items-center">
                                        <div class="department-logo rounded-circle bg-light " style="width: 100px; height: 100px; overflow: hidden;">
                                            <img src="<?php echo '../../../assets/img/uploads/' .  $department['logo']; ?>" 
                                                alt="Logo" 
                                                class="img-fluid rounded-circle border" 
                                                onerror="this.onerror=null; this.src='path/to/placeholder-image.png';">
                                        </div>
                                    </div>

                                        <div class="col-md-10">
                                            <h5 class="mb-2"><?php echo htmlspecialchars($department['department_name']); ?></h5>
                                            <div class="multi-line-text-truncate" >
                                                <p class="mb-3"><?php echo ($department['description']); ?></p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal dark m-0">
                                <div class="row px-3">
                                    <!-- Status, Local Admin, and Programs Section (visible on large screens) -->
                                    <div class="col-md-8 d-none d-lg-flex justify-content-start align-items-center">
                                        <span class="badge d-block me-2 <?php echo ($department['status'] === 'Active') ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?> text-white"><?php echo $department['status']; ?></span>
                                        <span class="badge bg-gradient-primary me-2">Local Admin: <?php echo htmlspecialchars($department['local_admin'] ?? 'N/A'); ?></span>
                                    </div>

                                    <!-- Buttons Section -->
                                    <div class="col-md-4 d-flex justify-content-end align-middle">
                                        <a href="view_department.php?id=<?php echo $department['id']; ?>" class="btn bg-gradient-info text-white px-3 py-2 me-2 my-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Department">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="edit_department.php?id=<?php echo $department['id']; ?>" class="btn bg-gradient-success text-white px-3 py-2 me-2 my-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Department">
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" 
                                        class="btn bg-gradient-danger px-3 py-2 my-2" 
                                        data-toggle="tooltip" 
                                        title="Delete Department" 
                                        onclick="confirmDeleteDepartment('<?php echo $department['id']; ?>')">
                                        <i class="fa fa-trash"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12 text-center">
                        <h3 class="text-secondary">No department found.</h3>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
    </div>
</div>


<?php include '../includes/footer.php'; ?>

<script>
    function confirmDeleteDepartment(departmentId) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this department!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            fetch(`delete_department.php?id=${departmentId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Poof! Your department has been deleted!", {
                        icon: "success",
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    swal(data.message, {
                        icon: "error",
                    });
                }
            })
            .catch(error => {
                swal("An error occurred.", {
                    icon: "error",
                });
            });
        } else {
            swal("Your department is safe!");
        }
    });
}

</script>