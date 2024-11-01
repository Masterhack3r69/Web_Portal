<?php  
$title = "Edit Department";
include '../includes/header.php';

if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    $sql = "SELECT * FROM departments WHERE id = ?";
    $department = query($sql, [$department_id])->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $updateFields = [];
        $params = [];
        
        if (!empty($_POST['department_name'])) {
            $updateFields[] = "department_name = ?";
            $params[] = $_POST['department_name'];
        }
        
        if (!empty($_POST['description'])) {
            $updateFields[] = "description = ?";
            $params[] = $_POST['description'];
        }
        
        if (!empty($_POST['department_head'])) {
            $updateFields[] = "department_head = ?";
            $params[] = $_POST['department_head'];
        }
        
        if (!empty($_POST['contact_phone'])) {
            $updateFields[] = "contact_phone = ?";
            $params[] = $_POST['contact_phone'];
        }
        
        if (!empty($_POST['contact_email'])) {
            $updateFields[] = "email = ?";
            $params[] = $_POST['contact_email'];
        }

        if (isset($_POST['status'])) {
            $updateFields[] = "status = ?";
            $params[] = $_POST['status'];
        }

        if (isset($_POST['location'])) {
            $updateFields[] = "location = ?";
            $params[] = $_POST['location'];
        }

        $logo = $department['logo']; 
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
            $logoDir = '../../../assets/img/uploads/';
            $logoFile = $logoDir . basename($_FILES['logo']['name']);
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoFile)) {
                $logo = $logoFile; 
                $updateFields[] = "logo = ?";
                $params[] = $logo;
            } else {
                showAlert("Error uploading the logo file.", "danger");
            }
        }

        $banner = $department['department_banner']; 
        if (isset($_FILES['department_banner']) && $_FILES['department_banner']['error'] == UPLOAD_ERR_OK) {
            $bannerDir = '../../../assets/img/uploads/';
            $bannerFile = $bannerDir . basename($_FILES['department_banner']['name']);
            if (move_uploaded_file($_FILES['department_banner']['tmp_name'], $bannerFile)) {
                $banner = $bannerFile; 
                $updateFields[] = "department_banner = ?";
                $params[] = $banner;
            } else {
                showAlert("Error uploading the banner image.", "danger");
            }
        }

        if (count($updateFields) > 0) {
        $sql = "UPDATE departments SET " . implode(', ', $updateFields) . " WHERE id = ?";

        $params[] = $department_id;
        
        try {
            $result = query($sql, $params);
            if ($result) {
                $_SESSION['success_message'] = "Department updated successfully!";
            } else {
                $_SESSION['warning_message'] = "Warning: The department could not be updated.";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }            
        }
    }
}

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    Edit Department - <span class="text-info"><?php echo $department['department_name']; ?></span>
                    <a href="department.php" class="btn back-btn bg-gradient-dark float-end">Back</a>
                </h5>
            </div>
            <div class="card-body">
                <form action="#" method="POST" enctype="multipart/form-data"> 
                    <div class="row">
                        <div class="col-md-6">
                            <label for="departmentName">Name of Department</label>
                            <input type="text" name="department_name" class="form-control" id="departmentName" value="<?php echo $department['department_name']; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="departmentHead">Department Head</label>
                            <input type="text" name="department_head" class="form-control" id="departmentHead" value="<?php echo $department['department_head']; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="contactEmail">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" id="contactEmail" value="<?php echo $department['email']; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="contactPhone">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" id="contactPhone" value="<?php echo $department['contact_phone']; ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control summernote" id="description" required><?php echo $department['description']; ?></textarea>
                        </div>

                        <div class="col-md-6 mt-1">
                            <label for="dept_status">Status</label>
                            <select name="status" class="form-select" aria-label="Default select example">
                                <option value="Active" <?php if ($department['status'] == 'Active') echo 'selected'; ?>>Active</option>
                                <option value="Inactive" <?php if ($department['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-1">
                            <label for="location">Location</label>
                            <input type="text" name="location" class="form-control" id="location" value="<?php echo $department['location']; ?>" required>
                        </div>

                        <div class="col-md-12 mt-1">
                            <label for="formFile">Logo</label>
                            <input class="form-control" type="file" name="logo" id="formFile" accept="image/*">
                        </div>

                        <div class="col-md-12 mt-1">
                            <label for="formFile">Banner</label>
                            <input class="form-control" type="file" name="department_banner" id="formFile" accept="image/*">
                        </div>

                        <div class="col-md-12 mt-1">
                            <br>
                            <button type="submit" class="btn bg-gradient-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

