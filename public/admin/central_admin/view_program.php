<?php
$title = "View Program";
include '../includes/header.php'; 
if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    $sql = "SELECT * FROM programs WHERE id = ?";
    $program = query($sql, [$program_id])->fetch_assoc(); 
    if (!$program) {
      header("Location: program.php?msg=Invalid request.&type=warning");
      exit;
    }
} 
?>


<div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">
        <div class="card-body color-card  p-0">
          <div class="card-img-container" style="position: relative; height: 250px; border-radius: 15px">
            <img id="program-img" src="<?php echo htmlspecialchars('../../../assets/img/uploads/' . $program['banner_image']); ?>" 
            alt="Banner" 
            style="height: 100%; width: 100%; object-fit: cover; border-radius: 15px 15px 0 0;">
            <div class="card-img-overlay d-flex align-items-center justify-content-center">
                <div class="bg-blur p-2 rounded" style="background-color: rgba(0,0,0, 0.2);">
                    <h5 class="text-center text-white m-0"><?php echo htmlspecialchars($program['title']); ?></h5>
                </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
</div>

<div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Description</h6>
        <p class="card-text"><?php echo ($program['description']); ?></p>
      </div>  
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <div class="card mb-3">
      <div class="card-body">
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Beneficiaries:</strong></h6>
          <p class="card-text d-inline-block"><?php echo ($program['beneficiary']); ?></p>
        </div>
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Duration:</strong></h6>
          <p class="card-text d-inline-block"><?php echo ($program['duration']); ?></p>
        </div>
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Location/Format:</strong></h6>
          <p class="card-text d-inline-block"><?php echo ($program['location_format']); ?></p>
        </div>
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Contact:</strong></h6>
          <p class="card-text d-inline-block"><?php echo ($program['contact_email']); ?></p>
        </div>
        <div class="">
          <h6 class="d-inline-block"><strong>Schedule:</strong></h6>
          <p class="card-text d-inline-block"><?php echo ($program['schedule']); ?></p>
        </div>
      </div>
    </div>
   
  </div>
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <div class="">
          <h6 class="d-inline-block"><strong>Status:</strong></h6>
          <?php if($program['status'] == 'Active'): ?>
            <p class="badge bg-success text-white">Active</p>
          <?php else: ?>
            <p class="badge bg-secondary text-white">Inactive</p>
          <?php endif; ?>
        </div>
      <div class="">
        <h6 class="d-inline-block"><strong>Created:</strong></h6>
        <p class="card-text d-inline-block"><?php echo date('F j, Y, g:i a', strtotime($program['created_at'])); ?></p>
      </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-body ">
        <?php if (!empty($program['form_id'])): ?>
        <h6 class="d-inline-block"><strong>Form Status:</strong></h6>
          <p class="badge bg-primary text-white">Form Available </p>
          <a href="view_applications.php?program_id=<?php echo $program['id']; ?>" class="bg-gradient-info btn px-2 py-2 float-end">
           view submissions
          </a> 
        <?php else: ?>
          <h6 class="d-inline-block"><strong>Form Status:</strong></h6>
          <p class="badge bg-warning text-white">No Form Assigned</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Guidelines</h6>
        <p class="card-text"><?php echo $program['guidelines']; ?></p>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Requirements</h6>
        <p class="card-text"><?php echo $program['requirements']; ?></p>
      </div>
    </div>
  </div>
</div>


<?php include '../includes/footer.php'; ?>

