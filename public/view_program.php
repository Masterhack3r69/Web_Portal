<?php 
include './includes/header.php';

// Check if the program ID is set in the URL
if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    $sql = "SELECT id, title, description, banner_image, requirements, guidelines, location_format, beneficiary, schedule, status, duration, contact_email, form_id FROM programs WHERE id = ?";
    $program = query($sql, [$program_id])->fetch_assoc();
    
    if (!$program) {
        echo "<div class='alert alert-danger'>Program not found.</div>";
        exit;
    }

    $form_id = $program['form_id'];
    $sql = "SELECT * FROM forms WHERE id = ?";
    $form = query($sql, [$form_id])->fetch_assoc();

    if (!$form) {
        echo "<div class='alert alert-danger'>No form assigned to this program.</div>";
        exit;
    }

} else {
    echo "<div class='alert alert-warning'>No program selected.</div>";
    exit;
}

?>

<div class="breadcrumb-container mt-3 pt-5">
    <?php include 'breadcrumb.php'; ?>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card pb-4 mb-4">
                <div class="card-img-top position-relative">
                    <img src="../assets/img/uploads/<?php echo htmlspecialchars($program['banner_image']); ?>" class="w-100 card-img-top" alt="Banner">
                    <div class="card-img-overlay d-flex flex-column justify-content-end align-items-center text-center">
                        <a href="application_form.php?id=<?php echo htmlspecialchars($form['id']); ?>" class="btn link-btn px-4">Apply Now</a>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="description">
                        <h4 class="card-title"><?php echo htmlspecialchars($program['title']); ?></h4>
                        <p class="card-text p-1 mb-3"><?php echo ($program['description']); ?></p>
                    </div>
                    <hr>
                    <div class="requirement">
                        <h5>Requirements:</h5>
                        <p><?php echo ($program['requirements']); ?></p>
                    </div>
                    <hr>
                    <div class="guidelines">
                        <h5>Guidelines:</h5>
                        <p><?php echo ($program['guidelines']); ?></p>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><strong>Location:</strong></h6> 
                            <p><?php echo ($program['location_format']); ?></p>
                            <h6><strong>Beneficiary:</strong></h6> 
                            <p><?php echo ($program['beneficiary']); ?></p>
                            <h6><strong>Schedule:</strong></h6> 
                            <p><?php echo ($program['schedule']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Status:</strong></h6> 
                            <p><?php echo ($program['status']); ?></p>
                            <h6><strong>Program Duration:</strong></h6> 
                            <p><?php echo ($program['duration']); ?></p>
                            <h6><strong>Contact Info:</strong></h6> 
                            <a href="mailto:<?php echo ($program['contact_email']); ?>">
                                <?php echo ($program['contact_email']); ?>
                            </a>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="btn link-btn float-end">More Programs</a>
                        <a href="#" class="btn link-btn float-end me-2">Visit Department</a>
                        <a href="application_form.php?id=<?php echo htmlspecialchars($form['id']); ?>" class="btn link-btn float-end me-2">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './includes/footer.php'; ?>