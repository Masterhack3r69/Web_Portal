<?php 
ob_start();
include './includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $currentUrl = $_SERVER['REQUEST_URI']; 
    header("Location: ./users/login_resident.php?redirect=" . urlencode($currentUrl)); 
    exit;
}

$formId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$programSql = "SELECT id AS program_id FROM programs WHERE form_id = ?";
$programResult = query($programSql, [$formId]);
$programData = $programResult ? $programResult->fetch_assoc() : null;

if (!$programData) {
    echo "<p>Program not found.</p>";
    exit;
}

$programId = $programData['program_id'] ;

// Check if user has already applied to this program
$checkSubmissionSql = "SELECT id FROM form_submissions WHERE user_id = ? AND program_id = ?";
$existingSubmission = query($checkSubmissionSql, [$_SESSION['user_id'], $programId]);

if ($existingSubmission && $existingSubmission->num_rows > 0) {
    echo "<script>
    window.addEventListener('load', function() {
        Swal.fire({
                title: 'You have already applied to this program.',
                text: 'Please wait for confirmation.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'View Status',
                cancelButtonText: 'Exit'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './status_form.php?id={  }';
                } else {
                    window.location.href = './program.php';
                }
            });
        });
    </script>
    <div style='min-height: 100vh; width: 100%;'></div>";
    include './includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submissionData = json_encode($_POST);

    $sql = "INSERT INTO form_submissions (form_id, program_id, user_id, submission_data, status) VALUES (?, ?, ?, ?, ?)";
    if (query($sql, [$formId, $programId, $_SESSION['user_id'], $submissionData, 'Pending'])) {
        $_SESSION['success_message'] = "Form submitted successfully";
    } else {
        $_SESSION['success_message'] = "Failed to submit the form.";
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $formId . "&submitted=1");
    exit;
}

// Fetch form HTML
$sql = "SELECT form_html FROM forms WHERE id = ?";
$formHtmlResult = query($sql, [$formId]);

$programDetailSql = "SELECT programs.title, departments.logo, departments.department_name FROM programs 
                INNER JOIN departments ON programs.department_id = departments.id
                WHERE programs.form_id = ?";
$programDetailResult = query($programDetailSql, [$formId]);

if ($formHtmlResult && $programDetailResult) {
    $formHtml = $formHtmlResult->fetch_assoc()['form_html'];
    $programDetail = $programDetailResult->fetch_assoc();

    if ($programDetail) {
        $programTitle = $programDetail['title'];
        $departmentLogo = $programDetail['logo'];
        $departmentName = $programDetail['department_name'];
    } else {
        echo "<p>Program details not found.</p>";
        exit;
    }
} else {
    echo "<p>Form not found.</p>";
    exit;
}
?>
<div class="container mt-3 pt-5" style="min-height: 100vh">
    <form id="applicationForm" method="POST" action="#">
        <div class="row d-flex justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <?php if (!empty($departmentLogo)): ?>
                                    <img src="../assets/img/uploads/<?php echo htmlspecialchars($departmentLogo); ?>" 
                                         style="height: 50px; width: auto; margin-right: 10px;" 
                                         alt="Department Logo">
                                <?php endif; ?>
                                <h6><?php echo htmlspecialchars($departmentName); ?></h6> 
                            </div>
                            <hr class="horizontal dark">
                            <h5 class="mt-2"><?php echo htmlspecialchars($programTitle); ?></h5> 
                        </div>
                        <hr class="horizontal dark">
                        <?php echo $formHtml; ?>
                    </div>
                    <div class="px-4">
                        <button id="submitBtn" type="button" class="btn border-0 btn-lg w-100 mt-4 mb-0" data-toggle="tooltip" title="Submit Form">Submit</button>
                        <p class="text-center">
                            <small>
                                You must read and acknowledge the terms and conditions. 
                                <a href="#" data-bs-toggle="modal" data-bs-target="#consentModal" style="text-decoration: underline; color: red;">Click here to view the consent again.</a>
                        </small>
                        </p>
                    </div>
                </div>  
            </div>
        </div>
    </form> 
</div>

<div class="modal fade" id="consentModal" tabindex="-1" aria-labelledby="consentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consentModalLabel">Consent Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please read and acknowledge the following information before proceeding with the application.</p>
                <p>[Insert contract text or terms and conditions here]</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="modalAcknowledgment" required>
                    <label class="form-check-label" for="modalAcknowledgment">I have read and understand the terms and conditions.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="termsAcceptance" required>
                    <label class="form-check-label" for="termsAcceptance">I accept the terms and conditions.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script>
// Check if the consent checkboxes are checked before submission
document.getElementById("submitBtn").onclick = function(event) {
    event.preventDefault();

    const acknowledgmentChecked = document.getElementById("modalAcknowledgment").checked;
    const acceptanceChecked = document.getElementById("termsAcceptance").checked;

    if (acknowledgmentChecked && acceptanceChecked) {
        // Submit the form if both checkboxes are checked
        document.getElementById("applicationForm").submit();
    } else {
        // Open consent modal if checkboxes aren't checked
        $('#consentModal').modal('show');
    }
};


<?php if (isset($_GET['submitted']) && $_GET['submitted'] == 1): ?>
    window.addEventListener('load', function() {
        Swal.fire({
            title: 'Form Submitted!',
            text: 'Your application has been submitted successfully.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'View Submission',
            cancelButtonText: 'Return',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './status_form.php?id=<?php echo $formId; ?>';
            } else {
                window.location.href = './program.php';
            }
        });
    });
<?php endif; ?>
</script>

<?php
ob_end_flush();
include './includes/footer.php';
?>

