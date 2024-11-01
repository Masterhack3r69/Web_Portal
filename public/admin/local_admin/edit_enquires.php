<?php
ob_start(); // Start output buffering
$title = "Edit News & Updates";
include '../includes/header.php';

if (isset($_SESSION['user_id'])) {
    $created_by = $_SESSION['user_id'];
}

// Fetch the department ID for the logged-in admin
$admin_info = query("SELECT department_id FROM admin WHERE id = ?", [$created_by])->fetch_assoc();
$department_id = $admin_info['department_id'] ?? null;

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
                header('Location: enquires.php');
                exit; 
            } else {
                $_SESSION['warning_message'] = "The news could not be updated.";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }
    }
}

// Fetch programs based on the department ID
$programs = query("SELECT id, title FROM programs WHERE department_id = ?", [$department_id])->fetch_all(MYSQLI_ASSOC);
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
                        <label for="program_id" class="form-label">Program:</label>
                        <select class="form-control" id="program_id" name="program_id" required>
                            <option value="">Select Program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?php echo $program['id']; ?>" <?php echo $program['id'] == $news['program_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($program['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end align-items-center">
                        <button type="submit" name="submit" class="btn bg-gradient-success">Update News</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

