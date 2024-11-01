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

<div class="sticky-top d-flex justify-content-end mt-auto pt-3">
  <div>
  <a href="edit_program.php?id=<?php echo $program['id']; ?>" 
     class="btn bg-gradient-warning me-2"
     data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Edit Program">
    <i class="fa fa-edit fa-lg"></i>
  </a>
  <a href="#" 
     class="btn bg-gradient-danger me-2"
     data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Delete Program"
     onclick="confirmDelete('<?php echo $program['id']; ?>', 'delete_program.php?id=<?php echo $program['id']; ?>')">
    <i class="fa fa-trash fa-lg"></i>
  </a>
    <a href="view_applications.php?program_id=<?php echo $program['id']; ?>" data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Submissions" class="btn bg-gradient-info me-2">
    <i class="fa fa-list fa-lg"></i>
  </a>  
  </div>
 
</div>

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
        <p class="card-text"><?php echo $program['description']; ?></p>
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
          <p class="card-text d-inline-block"><?php echo $program['beneficiary']; ?></p>
        </div>
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Duration:</strong></h6>
          <p class="card-text d-inline-block"><?php echo $program['duration']; ?></p>
        </div>
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Location/Format:</strong></h6>
          <p class="card-text d-inline-block"><?php echo $program['location_format']; ?></p>
        </div>
        <div class="mb-1 border-bottom">
          <h6 class="d-inline-block"><strong>Contact:</strong></h6>
          <p class="card-text d-inline-block"><?php echo $program['contact_email']; ?></p>
        </div>
        <div class="">
          <h6 class="d-inline-block"><strong>Schedule:</strong></h6>
          <p class="card-text d-inline-block"><?php echo $program['schedule']; ?></p>
        </div>
      </div>
    </div>
   
  </div>
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <p>This part is the application form where we can choose a form.</p>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-body">
        <p>This part is where we can see how many users have applied for this program.</p>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Guidelines
          <span class="accordion-header float-end" id="guidelines">
            <div class="sticky-top">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#guidelinesCollapse" aria-expanded="true" aria-controls="guidelinesCollapse">
                View
              </button>
            </div>
            </sp>
        </h6>
        <div class="accordion mb-3" id="accordionExample">
          <div class="accordion-item">
            
            <div id="guidelinesCollapse" class="accordion-collapse collapse show" aria-labelledby="guidelines" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <p class="card-text"><?php echo $program['guidelines']; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Requirements 
          <span class="accordion-header float-end" id="requirements">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#requirementsCollapse" aria-expanded="true" aria-controls="requirementsCollapse">
                View
              </button>
            </sp>
        </h6>
        <div class="accordion mb-3" id="accordionExample">
          <div class="accordion-item">
            <div id="requirementsCollapse" class="accordion-collapse collapse show" aria-labelledby="requirements" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <p class="card-text"><?php echo $program['requirements']; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<?php include '../includes/footer.php'; ?>
