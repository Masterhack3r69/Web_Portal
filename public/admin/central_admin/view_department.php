<?php
$title = "View Department";
include '../includes/header.php';

if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    if (!filter_var($department_id, FILTER_VALIDATE_INT)) {
        header("Location: department.php?msg=Invalid request.&type=warning");
        exit;
    }

    // Fetch the department details
    $sql = "SELECT d.*, a.username AS local_admin 
        FROM departments d 
        LEFT JOIN admin a ON d.local_admin_id = a.id 
        WHERE d.id = ?";
    $department = query($sql, [$department_id])->fetch_assoc();

    if (!$department) {
        header("Location: department.php?msg=Invalid request.&type=warning");
        exit;
    }

    // Fetch programs for this department
    $programs_sql = "SELECT * FROM programs WHERE department_id = ?";
    $programs = query($programs_sql, [$department_id])->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            <div class="card-body d-flex justify-content-end p-0">
                <a href="department.php" class="btn bg-gradient-dark" style="width: 100px;">Back</a>
            </div>
            <div class="row">
                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                    <div class="department-logo rounded-circle bg-light" style="width: 150px; height: 150px;">
                        <img src="<?php echo '../../../assets/img/uploads/' . $department['logo']; ?>" alt="Logo" class="img-fluid rounded-circle">
                    </div>
                </div>

                <div class="col-md-10">
                    <h2><?php echo htmlspecialchars($department['department_name'] ?? 'N/A'); ?></h2>
                    <div class="row border-top mt-2">
                        <div class="col-md-4">
                            <div class="p-2 text-center">
                                <strong>Head of Department</strong>
                                <p class="badge d-block bg-gradient-info text-white"><?php echo htmlspecialchars($department['department_head'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 text-center">
                                <strong>Local Admin</strong>
                                <?php if ($department['local_admin']) : ?>
                                    <p class="badge d-block bg-gradient-info text-white">
                                        <a href="view_local_admin.php?id=<?php echo $department['local_admin_id']; ?>" class="text-white">
                                            <?php echo htmlspecialchars($department['local_admin']); ?>
                                        </a>
                                    </p>
                                <?php else : ?>
                                    <p class="badge d-block bg-gradient-secondary text-white">N/A</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 text-center">
                                <strong>Status</strong>
                                <p class="badge d-block <?php echo ($department['status'] === 'Active') ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?> text-white"><?php echo htmlspecialchars($department['status'] ?? 'Unknown'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header">
                <h6>Department Description</h6>
            </div>
            <div class="card-body pt-0 ">
                <?php echo $department['description'] ?? 'No description available.'; ?>
            </div>
        </div>
    </div>
</div>

<div class="row py-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Programs</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($programs)) : ?>
                        <?php foreach ($programs as $program) : ?>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card program-card   ">
                                    <div class="card-img-container rounded-1" style="position: relative; height: 150px;">
                                        <img id="program-img" 
                                            src="<?php echo htmlspecialchars('../../../assets/img/uploads/' . $program['banner_image']); ?>" 
                                            class="card-img rounded-1" 
                                            alt="Banner" 
                                            style="height: 100%; width: 100%; object-fit: cover;"
                                            >
                                        <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                            <div class="bg-blur p-2 rounded" style="background-color: rgba(0,0,0, 0.2);">
                                                <h5 class="text-center text-white m-0"><?php echo htmlspecialchars($program['title']); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No active programs available for this department.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
