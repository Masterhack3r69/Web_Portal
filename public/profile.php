<?php
ob_start();
include './includes/header.php';

if (!isset($_SESSION['username'])) {
    header("Location: ./users/login_resident.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$user_result = query($sql, [$username]);
$user_data = $user_result->fetch_assoc();

$sql = "SELECT p.*, d.department_name, fs.status, fs.created_at 
        FROM form_submissions fs
        JOIN programs p ON fs.program_id = p.id 
        JOIN departments d ON p.department_id = d.id 
        WHERE fs.user_id = ?
        ORDER BY fs.created_at DESC";
$programs_result = query($sql, [$user_data['id']]);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $first_name = trim($_POST['first_name']);
        $middle_name = trim($_POST['middle_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $purok = trim($_POST['purok']);
        $barangay = trim($_POST['barangay']);
        $municipality = trim($_POST['municipality']);
        $province = trim($_POST['province']);
        $birthday = trim($_POST['birthday']);
        $sex = trim($_POST['sex']);

        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error_message = "First name, last name, and email are required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Please enter a valid email address.";
        } else {
            $sql = "UPDATE users SET first_name=?, middle_name=?, last_name=?, email=?, 
                    purok=?, barangay=?, municipality=?, province=?, birthday=?, sex=? 
                    WHERE id=?";
            $result = query($sql, [
                $first_name, $middle_name, $last_name, $email,
                $purok, $barangay, $municipality, $province, $birthday, $sex,
                $user_data['id']
            ]);
            
            if ($result) {
                // Update the user data in memory
                $user_data['first_name'] = $first_name;
                $user_data['middle_name'] = $middle_name;
                $user_data['last_name'] = $last_name;
                $user_data['email'] = $email;
                $user_data['purok'] = $purok;
                $user_data['barangay'] = $barangay;
                $user_data['municipality'] = $municipality;
                $user_data['province'] = $province;
                $user_data['birthday'] = $birthday;
                $user_data['sex'] = $sex;
                
                echo "<script>
                    swal({
                        title: 'Success!',
                        text: 'Your profile has been updated successfully!',
                        icon: 'success',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                value: true,
                                visible: true,
                                className: 'btn btn-success',
                                closeModal: true
                            }
                        }
                    });
                </script>";
            } else {
                echo "<script>
                    swal({
                        title: 'Error!',
                        text: 'There was a problem updating your profile. Please try again.',
                        icon: 'error',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                value: true,
                                visible: true,
                                className: 'btn btn-danger',
                                closeModal: true
                            }
                        }
                    });
                </script>";
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        $sql = "SELECT password FROM users WHERE id = ?";
        $result = query($sql, [$user_data['id']]);
        $user = $result->fetch_assoc();

        if (!password_verify($current_password, $user['password'])) {
            $password_error = "Current password is incorrect.";
        } elseif (strlen($new_password) < 8) {
            $password_error = "New password must be at least 8 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $password_error = "New passwords do not match.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $result = query($sql, [$hashed_password, $user_data['id']]);
            
            if ($result) {
                $password_success = "Password changed successfully!";
            } else {
                $password_error = "Error changing password. Please try again.";
            }
        }
    }
}

?>

<div class="container my-5 pt-5">
    <?php if (isset($success_message)): ?>
        <div class="notification notification-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="notification notification-error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="profile-section">
                <div class="profile-header">
                    <h3>Profile Information</h3>
                    <p class="text-muted">Manage your account details and personal information</p>
                </div>
                <form method="POST" action="" class="mb-4">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name <span class="required-field">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($user_data['middle_name'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="required-field">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="required-field">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purok" class="form-label">Purok</label>
                            <input type="text" class="form-control" id="purok" name="purok" value="<?php echo htmlspecialchars($user_data['purok'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="barangay" name="barangay" value="<?php echo htmlspecialchars($user_data['barangay'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="municipality" class="form-label">Municipality</label>
                            <input type="text" class="form-control" id="municipality" name="municipality" value="<?php echo htmlspecialchars($user_data['municipality'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Province</label>
                            <input type="text" class="form-control" id="province" name="province" value="<?php echo htmlspecialchars($user_data['province'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user_data['birthday'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-control" id="sex" name="sex">
                                <option value="">Select Sex</option>
                                <option value="Male" <?php echo ($user_data['sex'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($user_data['sex'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-link">Save Changes</button>
                </form>

                <div class="border-top pt-4">
                    <h3 class="section-title">Change Password</h3>
                    <?php if (isset($password_success)): ?>
                        <div class="notification notification-success"><?php echo htmlspecialchars($password_success); ?></div>
                    <?php endif; ?>
                    <?php if (isset($password_error)): ?>
                        <div class="notification notification-error"><?php echo htmlspecialchars($password_error); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password <span class="required-field">*</span></label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password <span class="required-field">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password <span class="required-field">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-link">Update Password</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="profile-section">
                <h3 class="section-title">Program Applications</h3>
                <?php if ($programs_result && $programs_result->num_rows > 0): ?>
                    <?php while ($program = $programs_result->fetch_assoc()): ?>
                        <div class="program-card">
                            <h5><?php echo htmlspecialchars($program['program_name']); ?></h5>
                            <p class="text-muted mb-2">Department: <?php echo htmlspecialchars($program['department_name']); ?></p>
                            <small class="text-muted">
                                Status: 
                                <span class="badge bg-<?php 
                                    echo match(strtolower($program['status'])) {
                                        'approved' => 'success',
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        default => 'primary'
                                    };
                                ?>">
                                    <?php echo htmlspecialchars(ucfirst($program['status'] ?? 'Pending')); ?>
                                </span>
                            </small>
                            <div class="mt-2">
                                <small class="text-muted">Applied on: <?php echo date('F j, Y', strtotime($program['created_at'])); ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">You haven't applied to any programs yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
ob_end_flush();
include './includes/footer.php'; ?>
