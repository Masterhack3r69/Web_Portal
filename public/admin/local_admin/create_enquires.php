<?php
ob_start(); 
$title = "News & Updates";
include '../includes/header.php';

if (isset($_SESSION['user_id'])) {
    $created_by = $_SESSION['user_id']; 
} else {
    echo "<script>alert('User is not logged in.');</script>";
}

$admin_info = query("SELECT department_id FROM admin WHERE id = ?", [$created_by])->fetch_assoc();
$department_id = $admin_info['department_id'] ?? null;

$programs = query("SELECT id, title FROM programs WHERE department_id = ?", [$department_id])->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $small_description = $_POST['small_description'] ?? null;
    $content = $_POST['content'];
    $program_id = $_POST['program_id'] ?? null;

    $image_url = '';
    $logoDir = '../../../assets/img/uploads/'; 

    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == UPLOAD_ERR_OK) {
        $uniqueImageName = time() . '_' . basename($_FILES['image_url']['name']);
        $imageFilePath = $logoDir . $uniqueImageName;

        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $imageFilePath)) {
            $image_url = $uniqueImageName; 
        } else {
            $_SESSION['error_message'] = "Error uploading the image file.";
        }
    }

    if (!$admin_info) {
        $_SESSION['error_message'] = "The created_by ID does not exist.";
    }

    $status = 'approved'; 

    $sql = "INSERT INTO news (title, small_description, content, image_url, department_id, program_id, created_by, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    try {
        $result = query($sql, [$title, $small_description, $content, $image_url, $department_id, $program_id, $created_by, $status]);

        if ($result) {
            $_SESSION['success_message'] = "News added successfully!";
            audit_log('news', 'Create', 'News added successfully');
            header('Location: enquires.php');
            exit; 
        } else {
            $_SESSION['warning_message'] = "The news could not be added.";
            audit_log('news', 'Create Failed', 'Failed to add news');
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        audit_log('news', 'Create Error', 'Error adding news ', $e->getMessage());
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>
                    News and Updates
                    <a href="enquires.php" class="btn bg-gradient-light float-end d-none d-lg-inline">back</a>
                    <a href="cenquires.php" class="btn bg-gradient-primary float-end d-inline d-lg-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add News and Updates">
                        <i class="fa fa-plus"></i>
                    </a>
                </h5> 
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="small_description" class="form-label">Small Description:</label>
                        <input type="text" class="form-control" id="small_description" name="small_description">
                    </div>
                   <div class="col-12">
                        <label for="content" class="form-label">Content:</label>
                        <input type="hidden" name="content" id="content">
                        <div id="editor-content" style="height: 200px;"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="image_url" class="form-label">Image URL:</label>
                        <input class="form-control" type="file" name="image_url" id="image_url" accept="image/*" required>
                    </div>
                    <div class="col-md-6">
                        <label for="program_id" class="form-label">Program:</label>
                        <select class="form-control" id="program_id" name="program_id" required>
                            <option value="">Select Program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?php echo $program['id']; ?>"><?php echo $program['title']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end align-items-center">
                        <button type="submit" name="submit" class="btn bg-gradient-success">Create News</button>
                    </div>
                </form>
                <?php if (empty($programs)): ?>
                    <div class="alert alert-warning mt-3">No programs found for your department.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
