<?php
ob_start(); // Start output buffering
$title = "Edit News & Updates";
include '../includes/header.php';

if (isset($_SESSION['user_id'])) {
    $created_by = $_SESSION['user_id'];
}

$departments = query("SELECT id, department_name FROM departments")->fetch_all(MYSQLI_ASSOC);

// Get news ID from URL
if (isset($_GET['id'])) {
    $news_id = $_GET['id'];
    $news = query("SELECT * FROM news WHERE id = ?", [$news_id])->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateFields = [];
    $params = [];

    if (!empty($_POST['title'])) {
        $updateFields[] = "title = ?";
        $params[] = $_POST['title'];
    }

    if (!empty($_POST['small_description'])) {
        $updateFields[] = "small_description = ?";
        $params[] = $_POST['small_description'];
    }

    if (!empty($_POST['content'])) {
        $updateFields[] = "content = ?";
        $params[] = $_POST['content'];
    }

    if (!empty($_POST['department_id'])) {
        $updateFields[] = "department_id = ?";
        $params[] = $_POST['department_id'];
    }

    if (!empty($_POST['program_id'])) {
        $updateFields[] = "program_id = ?";
        $params[] = $_POST['program_id'];
    }

    // If an image file is uploaded, handle it
    $image_url = $news['image_url'];
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == UPLOAD_ERR_OK) {
        $logoDir = '../../../assets/img/uploads/';
        $uniqueImageName = time() . '_' . basename($_FILES['image_url']['name']);
        $imageFilePath = $logoDir . $uniqueImageName;

        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $imageFilePath)) {
            $image_url = $uniqueImageName; // Update the image URL if uploaded successfully
        } else {
            $_SESSION['error_message'] = "Error uploading the image file.";
        }
    }

    // Add image URL to params if it was updated
    if ($image_url != $news['image_url']) {
        $updateFields[] = "image_url = ?";
        $params[] = $image_url;
    }

    if (!empty($updateFields)) {
        $sql = "UPDATE news SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $params[] = $news_id;

        try {
            $result = query($sql, $params);
            if ($result) {
                $_SESSION['success_message'] = "News updated successfully!";
                header('Location:  enquires.php');
                exit; 
            } else {
                $_SESSION['warning_message'] = "The news could not be updated.";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Edit News and Updates</h5>
                <a href="enquires.php" class="btn bg-gradient-light float-end">Back</a>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="small_description" class="form-label">Small Description:</label>
                        <input type="text" class="form-control" id="small_description" name="small_description" value="<?php echo htmlspecialchars($news['small_description']); ?>">
                    </div>
                    <div class="col-12">
                        <label for="content" class="form-label">Content:</label>
                        <textarea name="content" class="form-control summernote" id="content" rows="3" required><?php echo htmlspecialchars($news['content']); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="image_url" class="form-label">Image URL:</label>
                        <input class="form-control" type="file" name="image_url" id="image_url" accept="image/*">
                        <small>Current Image: <?php echo htmlspecialchars($news['image_url']); ?></small>
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department:</label>
                        <select class="form-control" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['id']; ?>" <?php echo $department['id'] == $news['department_id'] ? 'selected' : ''; ?>>
                                    <?php echo $department['department_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="program_id" class="form-label">Program:</label>
                        <select class="form-control" id="program_id" name="program_id" required>
                            <option value="">Select Program</option>
                           
                        </select>
                    </div>
                    <div class="col-md-6  d-flex justify-content-end align-items-center">
                        <button type="submit" name="submit" class="btn bg-gradient-success ">Update News</button>
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
                                $('#program_id').append('<option value="' + program.id + '" ' + (program.id == '<?php echo $news['program_id']; ?>' ? 'selected' : '') + '>' + program.title + '</option>');
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
        }).change(); 
    });
</script>
