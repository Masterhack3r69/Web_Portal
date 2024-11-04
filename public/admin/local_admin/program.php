<?php
$title = "Programs";
include '../includes/header.php';

$sql = "SELECT id, title, description, banner_image FROM programs WHERE department_id = ?"; 
$programs = query($sql, [$department_id]);  
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Programs 
                    <a href="create_program.php" class="btn bg-gradient-primary float-end">Create Program</a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($programs as $program): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card mb-3 border h-100">
                            <!-- image or random bg-color -->
                            <div class="card-img-container" style="position: relative; height: 150px; border-radius: 15px 15px 0 0;">
                                <img id="program-img" src="<?php echo htmlspecialchars( $program['banner_image']); ?>" 
                                alt="Banner" 
                                style="height: 100%; width: 100%; object-fit: cover; border-radius: 15px 15px 0 0;">
                                <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                    <div class="bg-blur p-2 rounded" style="background-color: rgba(0,0,0, 0.2);">
                                        <h5 class="text-center text-white m-0"><?php echo htmlspecialchars($program['title']); ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-0">
                                <div class="program-card">
                                    <div class="multi-line-text-truncate mt-0" style="height: 100px;">
                                        <p class="text-justify " style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?php echo ($program['description']); ?>
                                         </p>
                                    </div>
                                <hr class="horizontal dark m-0">
                                <div class="d-flex justify-content-end mt-3 align-middle">

                                    <a href="view_program.php?id=<?php echo $program['id']; ?>" class="btn bg-gradient-info me-2 px-3 mb-0"><i class="fa fa-eye"></i></a>

                                    <a href="edit_program.php?id=<?php echo $program['id']; ?>" class="btn bg-gradient-warning me-2 px-3 mb-0"><i class="fa fa-edit"></i></a>
                                    
                                    <a href="#" 
                                    class="btn bg-gradient-danger px-3 mb-0"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="bottom" 
                                    title="Delete Program"
                                    onclick="confirmDeleteProgram('<?php echo $program['id']; ?>')">
                                    <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 

<script>
    function confirmDeleteProgram(programId) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this program!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            fetch(`delete_program.php?id=${programId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Poof! Your program has been deleted!", {
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
            swal("Your program is safe!");
        }
    });
}

</script>