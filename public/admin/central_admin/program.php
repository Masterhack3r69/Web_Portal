<?php
$title = "Programs";
include '../includes/header.php';

$departments = query("SELECT id, department_name FROM departments")->fetch_all(MYSQLI_ASSOC);
$sql = "SELECT p.id, p.title, p.description, p.banner_image, d.department_name FROM programs p INNER JOIN departments d ON p.department_id = d.id";
$programs = query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Programs
                <select class="form-select float-end" style="width: 100%; max-width: 400px" id="department_id" onchange="filterPrograms(this.value)">
                <option value="">All</option>
                <?php foreach ($departments as $department) : ?>
                    <option value="<?php echo $department['id']; ?>"><?php echo $department['department_name']; ?></option>
                <?php endforeach; ?>
                </select>
                </h5>
            </div>
            <div class="card-body">
                <div class="row" id="program-container">
                    <?php if (!empty($programs)): ?>
                        <?php foreach ($programs as $program): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card mb-3 border h-100">
                                <div class="card-img-container" style="position: relative; height: 150px; border-radius: 15px 15px 0 0;">
                                    <img src="<?php echo htmlspecialchars('../../../assets/img/uploads/' . $program['banner_image']); ?>" 
                                         alt="Banner" 
                                         style="height: 100%; width: 100%; object-fit: cover; border-radius: 15px 15px 0 0;">
                                    <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                        <div class="bg-blur p-2 rounded" style="background-color: rgba(0,0,0, 0.2);">
                                            <h5 class="text-center text-white m-0"><?php echo $program['title']; ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-0">
                                    <div class="program-card">
                                        <div class="multi-line-text-truncate mt-0" style="height: 100px;">
                                            <p class="text-justify" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <?php echo $program['description']; ?>
                                             </p>
                                        </div>
                                        <hr class="horizontal dark m-0">
                                        <div class="d-flex justify-content-end mt-3 align-middle">
                                            <a href="view_program.php?id=<?php echo $program['id']; ?>" class="btn bg-gradient-info me-2 px-3 mb-0"><i class="fa fa-eye"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-center text-muted py-5">No programs available.</p>
                        </div>
                    <?php endif; ?>
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

    function filterPrograms(department_id) {
        $.ajax({
            url: 'filter_programs.php',
            method: 'POST',
            data: { department_id: department_id },
            success: function(response) {
                $('#program-container').html(response);
            }
        });
    }
</script>
