<?php 
ob_start();
include './includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $currentUrl = $_SERVER['REQUEST_URI']; 
    header("Location: ./users/login_resident.php?redirect=" . urlencode($currentUrl)); 
    exit;
}

// Fetch user data after session check
$userSql = "SELECT first_name, middle_name, last_name, phone, email, purok, barangay, municipality, province, birthday, sex FROM users WHERE id = ?";
$userResult = query($userSql, [$_SESSION['user_id']]);
$userData = $userResult ? $userResult->fetch_assoc() : null;

if (!$userData) {
    echo "<p>User data not found.</p>";
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

$programId = $programData['program_id'];

// Check if this is a new submission confirmation
if (isset($_GET['submitted']) && $_GET['submitted'] == 1) {
    if (isset($_SESSION['submission_success']) && $_SESSION['submission_success']) {
        // Clear the session flag
        unset($_SESSION['submission_success']);
        
        echo "
        <div class='container mt-5 pt-5' style='min-height: 80vh'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body text-center'>
                            <i class='fas fa-check-circle text-success fa-3x mb-3'></i>
                            <h4>Application Submitted Successfully!</h4>
                            <p>Your application has been submitted. You will be notified once it has been reviewed.</p>
                            <a href='./program.php' class='btn link-btn'>Return to Programs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
        include './includes/footer.php';
        exit;
    }
}

// Check for existing submissions
$checkSubmissionSql = "SELECT id FROM form_submissions WHERE user_id = ? AND program_id = ? AND form_id = ?";
$existingSubmission = query($checkSubmissionSql, [$_SESSION['user_id'], $programId, $formId]);

if ($existingSubmission && $existingSubmission->num_rows > 0) {
    echo "
    <div class='container mt-5 pt-5' style='min-height: 80vh'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-body text-center'>
                        <i class='fas fa-info-circle text-info fa-3x mb-3'></i>
                        <h4>Application Already Submitted</h4>
                        <p>You have already applied to this program. Please wait for confirmation.</p>
                        <a href='./program.php' class='btn link-btn'>Return to Programs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>";
    include './includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadedFiles = [];
    $uploadPath = '../assets/img/uploads/applications/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Handle file uploads
    if (!empty($_FILES)) {
        foreach ($_FILES as $fieldName => $fileInfo) {
            if (is_array($fileInfo['name'])) {
                $filesCount = count($fileInfo['name']);
                for ($i = 0; $i < $filesCount; $i++) {
                    if ($fileInfo['error'][$i] === UPLOAD_ERR_OK) {
                        $tempName = $fileInfo['tmp_name'][$i];
                        $originalName = $fileInfo['name'][$i];
                        $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $newFileName = uniqid('file_') . '_' . time() . '.' . $fileExtension;
                        $targetFile = $uploadPath . $newFileName;
                        
                        if (move_uploaded_file($tempName, $targetFile)) {
                            $uploadedFiles[$fieldName][] = $newFileName;
                        }
                    }
                }
            } else {
                // Handle single file upload
                if ($fileInfo['error'] === UPLOAD_ERR_OK) {
                    $tempName = $fileInfo['tmp_name'];
                    $originalName = $fileInfo['name'];
                    $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $newFileName = uniqid('file_') . '_' . time() . '.' . $fileExtension;
                    $targetFile = $uploadPath . $newFileName;
                    
                    if (move_uploaded_file($tempName, $targetFile)) {
                        $uploadedFiles[$fieldName] = $newFileName;
                    }
                }
            }
        }
    }

    // Prepare submission data
    $postData = $_POST;
    $postData['uploaded_files'] = $uploadedFiles;
    $submissionData = json_encode($postData);

    $sql = "INSERT INTO form_submissions (form_id, program_id, user_id, submission_data, status) VALUES (?, ?, ?, ?, ?)";
    if (query($sql, [$formId, $programId, $_SESSION['user_id'], $submissionData, 'unread'])) {
        $_SESSION['submission_success'] = true;
    } else {
        $_SESSION['submission_success'] = false;
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $formId . "&submitted=1");
    exit;
}

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
<div class="container mt-3 pt-5" style="min-height: 80vh">
    <form id="applicationForm" method="POST" action="#" enctype="multipart/form-data" class="d-flex flex-column">
        <div class="row d-flex justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <?php if (!empty($departmentLogo)): ?>
                                    <img src="../assets/img/uploads/<?php echo htmlspecialchars($departmentLogo); ?>" 
                                         style="height: 50px; width: auto; margin-right: 10px;" 
                                         alt="Department Logo">
                                         <div>
                                             <p class="m-0" style="font-size: 12px;">Republic of the Philippines</p> 
                                         <p class="m-0" style="font-size: 12px;">Caraga Administrative Region</p> 
                                         <p class="m-0" style="font-size: 12px;">PROVINCE OF DINAGAT ISLANDS</p> 
                                         </div>
                                <?php endif; ?>
                                
                            </div>
                            <p class="mb-0 text-bold" style="font-size: 12px;"><?php echo htmlspecialchars($departmentName); ?></p> 
                            <p class=" text-bold" style="font-size: 14px;"><?php echo htmlspecialchars($programTitle); ?></p> 
                        </div>
                        <hr class="horizontal dark">
                        <?php echo $formHtml; ?>
                    </div>
                    <div class="px-4">
                        <div class="form-check my-1">
                            <input class="form-check-input" type="checkbox" id="modalAcknowledgment" required>
                            <label class="form-check-label" for="modalAcknowledgment">I have read and understand the <a type="button" class="link-danger" data-bs-toggle="modal" data-bs-target="#consentModal">Terms and Conditions</a></label>
                        </div>
                        <button id="submitBtn" type="button" class="btn border-0 btn-lg w-100 mb-4" data-toggle="tooltip" title="Submit Form">Submit</button>
                    </div>
                </div>  
            </div>
        </div>
    </form> 
</div>

<div class="modal fade" id="consentModal" tabindex="-1" aria-labelledby="consentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="consentModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="p-3">
                    <h6 class="mb-3">Please read and acknowledge the following information before proceeding with the application.</h6>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">1. Data Collection and Usage</h6>
                        <p>Your personal information will be collected and processed solely for the purpose of evaluating and processing your application. This includes your name, contact details, and any other information required to complete this application.</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">2. Data Storage and Protection</h6>
                        <p>All information provided will be securely stored and will only be accessible by authorized personnel within our organization. We are committed to protecting your data in compliance with applicable data protection laws.</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">3. Retention Policy</h6>
                        <p>Your data will be retained only for as long as necessary to fulfill the purposes outlined in this application or as required by law. After this period, your information will be securely deleted.</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">4. Right to Withdraw</h6>
                        <p>You may withdraw your consent at any time by contacting our support team. Please note that this may impact your application status if processing is ongoing.</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">5. Agreement and Acknowledgment</h6>
                        <p>By accepting these terms, you agree to the collection and processing of your data as outlined above. You also acknowledge that this consent does not guarantee acceptance or approval of your application.</p>
                    </div>

                    <div class="alert ">
                        <i class="fas fa-info-circle me-2"></i>
                        If you have any questions about these terms, please contact our support team at <strong>support@pdi.gov.ph</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn link-btn" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', function() {
    // User data from PHP
    const userData = <?php echo json_encode($userData); ?>;
    
    // Function to find and fill input fields
    function fillFormFields() {
        // Common field mappings
        const fieldMappings = {
            'first_name': ['first name', 'firstname', 'fname'],
            'middle_name': ['middle name', 'middlename', 'mname'],
            'last_name': ['last name', 'lastname', 'lname'],
            'email': ['email', 'email address'],
            'phone': ['phone', 'contact', 'mobile', 'contact number', 'phone number'],
            'purok': ['purok', 'street', 'address1'],
            'barangay': ['barangay', 'brgy', 'village'],
            'municipality': ['municipality', 'city', 'town'],
            'province': ['province', 'state', 'region'],
            'birthday': ['birthday', 'birth date', 'birthdate', 'date of birth', 'dob'],
            'sex': ['sex', 'gender']
        };

        // Find all input elements and select elements
        const inputs = document.querySelectorAll('input:not([type="radio"]), select');
        
        // Handle regular inputs and selects
        inputs.forEach(input => {
            const inputName = input.name.toLowerCase();
            const inputLabel = input.getAttribute('aria-label')?.toLowerCase() || 
                             input.getAttribute('placeholder')?.toLowerCase() || 
                             input.closest('.form-group')?.querySelector('label')?.textContent.toLowerCase() || '';
            
            // Check each field mapping
            for (const [userField, possibleMatches] of Object.entries(fieldMappings)) {
                if (possibleMatches.some(match => 
                    inputName.includes(match) || inputLabel.includes(match)
                )) {
                    if (userData[userField]) {
                        if (userField === 'birthday') {
                            input.value = userData[userField].split(' ')[0]; // Take only the date part
                        } else {
                            input.value = userData[userField];
                        }
                        // Lock the field if it's a personal information field
                        input.readOnly = true;
                        input.style.backgroundColor = '#f8f9fa';
                        // Add auto-filled note
                        const note = document.createElement('small');
                        note.className = 'text-muted ms-2';
                        note.textContent = '(Auto-filled)';
                        if (!input.nextElementSibling?.classList.contains('text-muted')) {
                            input.parentNode.insertBefore(note, input.nextSibling);
                        }
                    }
                    break;
                }
            }
        });

        // Handle radio buttons for sex/gender
        const sexRadios = document.querySelectorAll('input[type="radio"]');
        sexRadios.forEach(radio => {
            const radioName = radio.name.toLowerCase();
            const radioLabel = radio.parentElement?.textContent.toLowerCase() || '';
            
            if (radioName.includes('sex') || radioName.includes('gender') || 
                radioLabel.includes('sex') || radioLabel.includes('gender')) {
                const radioValue = radio.value.toLowerCase();
                const userSex = (userData.sex || '').toLowerCase();
                
                if (radioValue === userSex || 
                    (radioValue === 'm' && userSex === 'male') ||
                    (radioValue === 'f' && userSex === 'female')) {
                    radio.checked = true;
                }
                radio.disabled = true; // Lock the radio button
                
                // Add auto-filled note if not already present
                const container = radio.closest('.form-group') || radio.closest('.form-check') || radio.parentElement;
                if (container && !container.querySelector('.text-muted')) {
                    const note = document.createElement('small');
                    note.className = 'text-muted ms-2';
                    note.textContent = '(Auto-filled)';
                    container.appendChild(note);
                }
            }
        });
    }

    // Initial form fill
    fillFormFields();
});
</script>

<script>
document.getElementById("submitBtn").onclick = function(event) {
    event.preventDefault();
    
    const acknowledgmentChecked = document.getElementById("modalAcknowledgment").checked;
    
    if (!acknowledgmentChecked) {
        Swal.fire({
            title: 'Terms & Conditions',
            text: 'Please read and accept the Terms and Conditions before submitting.',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
        return;
    }

    // Check all required fields
    const form = document.getElementById("applicationForm");
    const requiredFields = form.querySelectorAll('[required]');
    let hasEmptyFields = false;
    let firstEmptyField = null;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            hasEmptyFields = true;
            field.classList.add('is-invalid');
            if (!firstEmptyField) firstEmptyField = field;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    if (hasEmptyFields) {
        firstEmptyField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        Swal.fire({
            title: 'Required Fields',
            text: 'Please fill in all required fields before submitting.',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
        return;
    }
    
    Swal.fire({
        title: 'Submit Application?',
        text: 'Are you sure you want to submit this application? This action cannot be undone.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'No, review again'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("applicationForm").submit();
        }
    });
};

// Add input event listeners to remove invalid class when user starts typing
document.querySelectorAll('[required]').forEach(field => {
    field.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
        }
    });
});

<?php if (isset($_GET['submitted']) && $_GET['submitted'] == 1): ?>
    window.addEventListener('load', function() {
        Swal.fire({
            title: 'Application Submitted!',
            text: 'Your application has been submitted successfully. You will be notified once it has been reviewed.',
            icon: 'success',
            confirmButtonText: 'Return to Programs',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            window.location.href = './program.php';
        });
    });
<?php endif; ?>
</script>

<?php
ob_end_flush();
include './includes/footer.php';
?>
