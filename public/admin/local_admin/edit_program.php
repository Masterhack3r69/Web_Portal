
<?php  
ob_start();
$title = "Edit Program";
include '../includes/header.php';

if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    // Fetch the program details
    $sql = "SELECT * FROM programs WHERE id = ?";
    $program = query($sql, [$program_id])->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $updateFields = [];
        $params = [];
        
        // Collecting data for update
        if (!empty($_POST['program_title'])) {
            $updateFields[] = "title = ?";
            $params[] = $_POST['program_title'];
        }
        
        if (!empty($_POST['program_description'])) {
            $updateFields[] = "description = ?";
            $params[] = $_POST['program_description'];
        }
        
        if (!empty($_POST['program_beneficiary'])) {
            $updateFields[] = "beneficiary = ?";
            $params[] = $_POST['program_beneficiary'];
        }
        
        if (isset($_POST['status'])) {
            $updateFields[] = "status = ?";
            $params[] = $_POST['status'];
        }

        if (!empty($_POST['program_guidelines'])) {
            $updateFields[] = "guidelines = ?";
            $params[] = $_POST['program_guidelines'];
        }

        if (!empty($_POST['program_requirements'])) {
            $updateFields[] = "requirements = ?";
            $params[] = $_POST['program_requirements'];
        }

        if (!empty($_POST['program_duration'])) {
            $updateFields[] = "duration = ?";
            $params[] = $_POST['program_duration'];
        }

        if (!empty($_POST['program_location'])) {
            $updateFields[] = "location_format = ?";
            $params[] = $_POST['program_location'];
        }

        if (!empty($_POST['program_contact'])) {
            $updateFields[] = "contact_email = ?";
            $params[] = $_POST['program_contact'];
        }

        if (!empty($_POST['program_schedule'])) {
            $updateFields[] = "schedule = ?";
            $params[] = $_POST['program_schedule'];
        }

        if (isset($_POST['form_id'])) {
            $updateFields[] = "form_id = ?";
            $params[] = $_POST['form_id'];
        }

        // Check for banner image upload
        $banner = $program['banner_image']; 
        if (isset($_FILES['program_banner']) && $_FILES['program_banner']['error'] == UPLOAD_ERR_OK) {
            $bannerDir = '../../../assets/img/uploads/';
            $bannerFile = $bannerDir . basename($_FILES['program_banner']['name']);
            if (move_uploaded_file($_FILES['program_banner']['tmp_name'], $bannerFile)) {
                $banner = $bannerFile; 
                $updateFields[] = "banner_image = ?";
                $params[] = $banner;
            } else {
                showAlert("Error uploading the banner image.", "danger");
            }
        }

        if (count($updateFields) > 0) {
            $sql = "UPDATE programs SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $params[] = $program_id; 

            try {
                $result = query($sql, $params);
                
                if ($result) {
                    $_SESSION['success_message'] = "Program updated successfully!";
                    header('Location: program.php');
                    exit; 
                } else {
                    showAlert("Warning: The program could not be updated.", "warning");
                     audit_log('news', 'Create Failed', 'The program could not be updated');
                }
            } catch (Exception $e) {
                showAlert("Error: " . $e->getMessage(), "danger");
            }
        } else {
            showAlert("No fields to update.", "warning");
        }
    }
}

$departmentId = $_SESSION['department_id'];
$formsSql = "SELECT id, form_name FROM forms WHERE department_id = ?";
$formsResult = query($formsSql, [$departmentId]);

if ($formsResult) {
    $forms = $formsResult->fetch_all(MYSQLI_ASSOC); // Fetch all as an associative array
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <span class="text-primary"><?= $program['title'] ?></span>
                    <a href="view_program.php?id=<?php echo $program['id']; ?>" class="btn bg-gradient-info mx-2 float-end">View</a>
                    <a href="program.php" class="btn back-btn bg-gradient-secondary float-end">Back</a>
                </h5>
            </div>
            <div class="card-body">
                <form action="edit_program.php?id=<?= $program_id ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="programTitle" class="form-label">Program Title</label>
                                <input type="text" class="form-control" id="programTitle" name="program_title" value="<?= $program['title'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                         <div class="mb-3">
                            <label for="programDescription" class="form-label">Description</label>
                            <input type="hidden" name="program_description" id="programDescription">
                            <div id="editor-description" style="height: 200px;"><?= $program['description'] ?></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programBeneficiary" class="form-label">Beneficiary</label>
                                <input type="text" class="form-control" id="programBeneficiary" name="program_beneficiary" value="<?= $program['beneficiary'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prog_status" class="form-label">Status</label>
                                <select name="status" class="form-select" aria-label="Default select example">
                                    <option value="Active" <?= $program['status'] == 'Active' ? 'selected' : '' ?>>Available</option>
                                    <option value="Inactive" <?= $program['status'] == 'Inactive' ? 'selected' : '' ?>>Unavailable</option>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-12">
                          <div class="mb-3">
                              <label for="programGuidelines" class="form-label">Guidelines</label>
                              <input type="hidden" name="program_guidelines" id="programGuidelines">
                              <div id="editor-guidelines" style="height: 200px;"><?= $program['guidelines'] ?></div>            
                          </div>
                        </div>
                       <div class="col-md-12">
                          <div class="mb-3">
                            <label for="programRequirements" class="form-label">Requirements</label>
                            <input type="hidden" name="program_requirements" id="programRequirements">
                            <div id="editor-requirements" style="height: 200px;"><?= $program['requirements'] ?></div>
                          </div>
                         </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programDuration" class="form-label">Duration</label>
                                <input type="text" class="form-control" id="programDuration" name="program_duration" value="<?= $program['duration'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programLocation" class="form-label">Location/Format</label>
                                <input type="text" class="form-control" id="programLocation" name="program_location" value="<?= $program['location_format'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programContact" class="form-label">Contact</label>
                                <input type="email" class="form-control" id="programContact" name="program_contact" value="<?= $program['contact_email'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programSchedule" class="form-label">Schedule</label>
                                <input type="text" class="form-control" id="programSchedule" name="program_schedule" value="<?= $program['schedule'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programBanner" class="form-label">Banner Image</label>
                                <input type="file" class="form-control" id="programBanner" name="program_banner" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="formSelect">Select Form</label>
                                <select name="form_id" id="formSelect" class="form-select" required>
                                    <option value="" selected disabled>Select a form</option>
                                    <?php foreach ($forms as $form): ?>
                                        <option value="<?php echo htmlspecialchars($form['id']); ?>" <?= ($form['id'] == $program['form_id']) ? 'selected' : '' ?>>
                                            <?php echo htmlspecialchars($form['form_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <button type="submit" class="btn bg-gradient-success">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include '../includes/footer.php'; ?>

 