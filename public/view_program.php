<?php 
include './includes/header.php';

// Function to check if profile is complete
function isProfileComplete($userData) {
    $requiredFields = [
        'first_name', 'last_name', 'email', 
        'purok', 'barangay', 'municipality', 'province',
        'birthday', 'sex'
    ];
    
    foreach ($requiredFields as $field) {
        if (empty($userData[$field])) {
            return false;
        }
    }
    return true;
}

if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    $sql = "SELECT p.id, p.title, p.description, p.banner_image, p.requirements, p.guidelines, p.location_format, 
                   p.beneficiary, p.schedule, p.status, p.duration, p.contact_email, p.form_id, p.department_id
            FROM programs p
            LEFT JOIN departments d ON p.department_id = d.id
            WHERE p.id = ?";
    $program = query($sql, [$program_id])->fetch_assoc();

    // Get user data for profile check
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM users WHERE username = ?";
        $user_result = query($sql, [$username]);
        $user_data = $user_result->fetch_assoc();
        $profile_complete = isProfileComplete($user_data);
    }
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
                    <img src="../assets/img/uploads/<?php echo htmlspecialchars($program['banner_image']); ?>" class="w-100 card-img-top" alt="Banner" style="height: 400px; object-fit: cover;">
                    <div class="card-img-overlay d-flex flex-column justify-content-end align-items-center text-center">
                        <?php if (!isset($_SESSION['username'])): ?>
                            <a href="./users/login_resident.php" class="btn link-btn px-4">Login to Apply</a>
                        <?php elseif ($program['status'] !== 'Active'): ?>
                            <a onclick="showUnavailableAlert()" class="btn link-btn px-4">Apply Now</a>
                        <?php elseif (!$profile_complete): ?>
                            <a onclick="showIncompleteProfileAlert()" class="btn link-btn px-4">Apply Now</a>
                        <?php else: ?>
                            <a href="application_form.php?id=<?php echo htmlspecialchars($program['form_id']); ?>" class="btn link-btn px-4">Apply Now</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="description">
                        <h4 class="card-title"><?php echo htmlspecialchars($program['title']); ?></h4>
                        <p class="card-text p-1 mb-3"><?php echo $program['description']; ?></p>
                    </div>
                    <hr>
                    <div class="requirement">
                        <h5>Requirements:</h5>
                        <p><?php echo $program['requirements']; ?></p>
                    </div>
                    <hr>
                    <div class="guidelines">
                        <h5>Guidelines:</h5>
                        <p><?php echo $program['guidelines']; ?></p>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><strong>Location:</strong></h6> 
                            <p><?php echo htmlspecialchars($program['location_format']); ?></p>
                            <h6><strong>Beneficiary:</strong></h6> 
                            <p><?php echo htmlspecialchars($program['beneficiary']); ?></p>
                            <h6><strong>Schedule:</strong></h6> 
                            <p><?php echo htmlspecialchars($program['schedule']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Status:</strong></h6> 
                            <p><?php echo htmlspecialchars($program['status']); ?></p>
                            <h6><strong>Program Duration:</strong></h6> 
                            <p><?php echo htmlspecialchars($program['duration']); ?></p>
                            <h6><strong>Contact Info:</strong></h6> 
                            <a href="mailto:<?php echo htmlspecialchars($program['contact_email']); ?>">
                                <?php echo htmlspecialchars($program['contact_email']); ?>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end w-100">
                        <a href="program.php" class="btn responsive-btn me-2 mb-2 btn-sm px-2">More Programs</a>
                        <a href="view_department.php?id=<?php echo htmlspecialchars($program['department_id']); ?>" 
                           class="btn me-2 mb-2 btn-sm px-2">Department
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './includes/footer.php'; ?>
<script>
function showUnavailableAlert() {
    Swal.fire({
        title: 'Program Unavailable',
        text: 'This program is currently not available for online applications.',
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6'
    });
}

function showIncompleteProfileAlert() {
    Swal.fire({
        title: 'Incomplete Profile',
        text: 'Please complete your profile information before applying to this program.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Complete Profile',
        cancelButtonText: 'Later',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'profile.php';
        }
    });
}
</script>
