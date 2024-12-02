    <?php
    ob_start();
    $title = "Create Department";
    include '../includes/header.php'; 
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $department_name = $_POST['department_name'];
        $description = $_POST['description'];
        $department_head = $_POST['department_head'];
        $contact_phone = $_POST['contact_phone'];
        $email = $_POST['contact_email'];
        $status = isset($_POST['status']) ? $_POST['status'] : 'Inactive';
        $logo = '../../../assets/img/default_img/default_logo.png'; 
        $location = $_POST['location'];
        $department_banner = '../../../assets/img/default_img/default_banner.png';
        $local_admin_id = NULL; 
    
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
    
        if (isset($_FILES['department_banner']) && $_FILES['department_banner']['error'] == UPLOAD_ERR_OK) {
        $bannerDir = '../../../assets/img/uploads/';
        $bannerFile = $bannerDir . basename($_FILES['department_banner']['name']);
        if (move_uploaded_file($_FILES['department_banner']['tmp_name'], $bannerFile)) {
            $department_banner = $bannerFile; 
            $updateFields[] = "department_banner = ?";
            $params[] = $department_banner;
        } else {
            showAlert("Error uploading the banner image.", "danger");
            }
        }
    
        $sql = "INSERT INTO departments (department_name, description, department_head, contact_phone, email, status, logo, location, department_banner, local_admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $result = query($sql, [$department_name, $description, $department_head, $contact_phone, $email, $status, $logo, $location, $department_banner, $local_admin_id]);
        
        if ($result) {
            $_SESSION['success_message'] = "Department created successfully!";
            audit_log('info', 'Create', 'Department created successfully');
            header('Location: department.php');
            exit;
            } else {
                $_SESSION['warning_message'] = "The department could not be added.";
                
                audit_log('error', 'Create Failed', 'Failed to create department');
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            
            // Insert an audit log for the exception
            audit_log('error', 'Create Error', 'Error creating department: ' . $e->getMessage());
    }  

}

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    Department  
                    <a href="department.php" class="btn back-btn bg-gradient-dark float-end">Back</a>
                </h5>
            </div>
            <div class="card-body">
                <form action="#" method="POST" enctype="multipart/form-data"> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department_name" class="form-label">Name of Department</label>
                                <input type="text" name="department_name" class="form-control" id="department_name" placeholder="Name of Department" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department_head" class="form-label">Department Head</label>
                                <input type="text" name="department_head" class="form-control" id="department_head" placeholder="Department Head" required>
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control" id="contact_email" placeholder="Contact Email" required>
                            </div>
                        </div>      
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" id="contact_phone" placeholder="Contact Phone" required>
                            </div>
                        </div>
                        <div class="col-md-12 mt-1">
                            <label for="description">Description</label>
                            <input type="hidden" name="description" id="localDepartmentDescription">
                            <div id="editor-local-department-description"  style="height: 200px;"></div>
                        </div>
                        <div class="col-md-6 mt-1">
                            <div class="mb-3">
                                <label for="dept_status" class="form-label">Status</label>
                                <select name="status" class="form-select" aria-label="Default select example">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" id="location" placeholder="Location" required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <div class="mb-3">
                                <label for="formFile">Logo</label>
                                <input class="form-control" type="file" name="logo" id="formFile" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6 mt-1">
                            <div class="mb-3">
                                <label for="formFile">Department Banner</label>
                                <input class="form-control" type="file" name="department_banner" id="formFile" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <button type="submit" class="btn bg-gradient-success">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

