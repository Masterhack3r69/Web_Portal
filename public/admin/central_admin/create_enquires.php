<?php
ob_start(); 
$title = "Create News & Updates";
include '../includes/header.php';

$departments = query("SELECT id, department_name FROM departments")->fetch_all(MYSQLI_ASSOC);

if (isset($_SESSION['user_id'])) {
    $created_by = $_SESSION['user_id']; 
} else {
    echo "<script>alert('User is not logged in.');</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $small_description = $_POST['small_description'] ?? null;
    $content = $_POST['content'];
    $department_id = $_POST['department_id'] ?? null;
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

    // Get admin type
    $admin_info = query("SELECT admin_type FROM admin WHERE id = ?", [$created_by])->fetch_assoc();
    
    // Check if ang admin exists
    if (!$admin_info) {
        $_SESSION['error_message'] = "The created_by ID does not exist.";
    }

    $admin_type = $admin_info['admin_type'];

    $status = ($admin_type === 'central') ? 'approved' : 'pending';

    $sql = "INSERT INTO news (title, small_description, content, image_url, department_id, program_id, created_by, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    try {
        $result = query($sql, [$title, $small_description, $content, $image_url, $department_id, $program_id, $created_by, $status]);

        if ($result) {
            $_SESSION['success_message'] = "News added successfully!";
            
            header('Location: enquires.php');
            exit; 
        } else {
            $_SESSION['warning_message'] = "The news could not be added.";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
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
                        <textarea name="content" class="form-control summernote" id="content" rows="3" required></textarea>         
                    </div>
                    <div class="col-md-6">
                        <label for="image_url" class="form-label">Image URL:</label>
                        <input class="form-control" type="file" name="image_url" id="image_url" accept="image/*" required>
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department:</label>
                        <select class="form-control" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['id']; ?>"><?php echo $department['department_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="program_id" class="form-label">Program:</label>
                        <select class="form-control" id="program_id" name="program_id" required disabled>
                            <option value="">Select Program</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <button type="submit" name="submit" class="btn bg-gradient-success">Create News</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#department_id').change(function() {
            var departmentId = $(this).val();
            $('#program_id').empty().append('<option value="">Select Program</option>').prop('disabled', true);
            
            if (departmentId) {
                $.ajax({
                    url: 'get_program_news.php', 
                    type: 'POST',
                    data: { department_id: departmentId },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(i, program) {
                                $('#program_id').append('<option value="' + program.id + '">' + program.title + '</option>');
                            });
                            $('#program_id').prop('disabled', false);
                        } else {
                            alert('No programs found for this department.');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch programs.');
                    }
                });
            }
        });
    });
</script>
