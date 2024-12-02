<?php
ob_start();
$title = "Create Program";
include '../includes/header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['program_title'];
    $description = $_POST['program_description'];
    $beneficiary = $_POST['program_beneficiary'];
    $status = $_POST['status'];
    $guidelines = $_POST['program_guidelines'];
    $requirements = $_POST['program_requirements'];
    $duration = $_POST['program_duration'];
    $location_format = $_POST['program_location'];
    $contact_email = $_POST['program_contact'];
    $schedule = $_POST['program_schedule'];
    $banner_image = null;

    $department_id = $_SESSION['department_id'];
    $form_id = $_POST['form_id'] ?? null;

    // Handle file upload
    if (isset($_FILES['program_banner']) && $_FILES['program_banner']['error'] == UPLOAD_ERR_OK) {
        $bannerDir = '../../../assets/img/uploads/';
        // Create directory if it doesn't exist
        if (!file_exists($bannerDir)) {
            mkdir($bannerDir, 0777, true);
        }
        
        $bannerFileName = basename($_FILES['program_banner']['name']);
        // Clean the filename to prevent directory traversal
        $bannerFileName = str_replace(['../', '..\\'], '', $bannerFileName);
        $bannerFile = $bannerDir . $bannerFileName;
        
        // Check if file is actually an image
        $check = getimagesize($_FILES['program_banner']['tmp_name']);
        if($check !== false) {
            if (move_uploaded_file($_FILES['program_banner']['tmp_name'], $bannerFile)) {
                $banner_image = $bannerFileName;
            } else {
                $_SESSION['error_message'] = "Error uploading the file.";
            }
        } else {
            $_SESSION['error_message'] = "File is not an image.";
        }
    }

    $sql = "INSERT INTO programs (title, description, beneficiary, status, guidelines, requirements, duration, location_format, contact_email, schedule, banner_image, department_id, form_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $result = query($sql, [$title, $description, $beneficiary, $status, $guidelines, $requirements, $duration, $location_format, $contact_email, $schedule, $banner_image, $department_id, $form_id]);
        
        
        if ($result) {
            $_SESSION['success_message'] = "Program created successfully!";
            audit_log('program', 'Create', 'Program created successfully');
            header('Location: program.php');
            exit;
        } else {
            $_SESSION['warning_message'] = "Warning: The program could not be created.";
            audit_log('program', 'Create Failed', 'Failed to create program');
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        audit_log('program', 'Create Error', 'Error creating program');
    }
}


$departmentId = $_SESSION['department_id'];
$formsSql = "SELECT id, form_name FROM forms WHERE department_id = ?";
$formsResult = query($formsSql, [$departmentId]);

if ($formsResult) {
    $forms = $formsResult->fetch_all(MYSQLI_ASSOC);
}

?>
<div class="row">
<div class="col-md-12">
  <div class="card">
    <div class="card-header">
    <h5 class="text-dark">
      Create New Program
      <a href="program.php" class="btn back-btn bg-gradient-dark float-end">Back</a>
    </h5>
    </div>
    <div class="card-body">
      <form action="#" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-12">
            <div class="mb-3">
              <label for="programTitle" class="form-label">Program Title</label>
              <input type="text" class="form-control" id="programTitle" name="program_title" required>
            </div>
          </div>
          <div class="col-md-12">
          <div class="mb-3">
            <label for="programDescription" class="form-label">Description</label>
            <input type="hidden" name="program_description" id="programDescription">
            <div id="editor-description" style="height: 200px;"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="programBeneficiary" class="form-label">Beneficiary</label>
            <input type="text" class="form-control" id="programBeneficiary" name="program_beneficiary" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="prog_status" class="form-label">Status</label>
            <select name="status" class="form-select" aria-label="Default select example">
              <option value="Active">Available</option>
              <option value="Inactive">Unavailable</option>
            </select>
          </div>
        </div>  
        <div class="col-md-12">
          <div class="mb-3">
              <label for="programGuidelines" class="form-label">Guidelines</label>
              <input type="hidden" name="program_guidelines" id="programGuidelines">
              <div id="editor-guidelines" style="height: 200px;"></div>            
          </div>
        </div>
        <div class="col-md-12">
          <div class="mb-3">
            <label for="programRequirements" class="form-label">Requirements</label>
            <input type="hidden" name="program_requirements" id="programRequirements">
            <div id="editor-requirements" style="height: 200px;"></div>
          </div>
         </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="programDuration" class="form-label">Duration</label>
              <input type="text" class="form-control" id="programDuration" name="program_duration" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="programLocation" class="form-label">Location/Format</label>
              <input type="text" class="form-control" id="programLocation" name="program_location" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="programContact" class="form-label">Contact</label>
              <input type="email" class="form-control" id="programContact" name="program_contact" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="programSchedule" class="form-label">Schedule</label>
              <input type="date" class="form-control" id="programSchedule" name="program_schedule" required>
            </div>
          </div>
          <div class="col-md-6">
              <div class="mb-3">
                <label for="formSelect">Select Form</label>
                <select name="form_id" id="formSelect" class="form-select">
                    <option value="" selected disabled>Select a form</option>
                    <?php foreach ($formsResult as $form): ?>
                        <option value="<?php echo htmlspecialchars($form['id']); ?>">
                            <?php echo htmlspecialchars($form['form_name']); ?>
                        </option>
                    <?php endforeach; ?>
                  </select>
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
                <br>
                <button type="submit" class="btn bg-gradient-success">Create</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
  <?php include '../includes/footer.php' ?>