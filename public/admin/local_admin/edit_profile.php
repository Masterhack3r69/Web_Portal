<?php
ob_start();
$title = "Profile";
include '../includes/header.php';

// Fetch departments 
$department_sql = "SELECT * FROM departments";
$departments = query($department_sql)->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    $admin_sql = "SELECT * FROM admin WHERE id = ?";
    $admin = query($admin_sql, [$admin_id])->fetch_assoc();

    if (!$admin) {
        showAlert("Error: Admin not found.", "danger");
        header("Location: local_admin.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['password'];

    if (password_verify($current_password, $admin['password'])) {
        $password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : $admin['password'];

        try {
            $update_sql = "UPDATE admin SET username = ?, email = ?, password = ? WHERE id = ?";
            $result = query($update_sql, [$username, $email, $password, $admin_id]);

            if ($result) {
                $_SESSION['success_message'] = "Local admin updated successfully!";
                audit_log('local_admin', 'Update', 'Local admin updated successfully');
                header("Location: profile.php");
                exit();
            } else {
                $_SESSION['warning_message'] = "Failed to update Local admin!";
                audit_log('local_admin', 'Update Failed', 'Failed to update local admin');
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            audit_log('local_admin', 'Update Error', 'Error updating local admin');
        }
    } else {
        $_SESSION['error_message'] = "Error: Current password is incorrect.";
        audit_log('local_admin', 'Update Error', 'Incorrect current password for updating local admin');
    }
}
?>

<!-- Edit Local Admin Form -->
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5>
                    Edit Local Admin
                    <a href="profile.php" class="btn back-btn bg-gradient-dark float-end">Back</a>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <!-- Username Field -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control" id="floatingInput" 
                                    value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                                <label for="floatingInput">Username</label>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="floatingInput" 
                                    value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                <label for="floatingInput">Email</label>
                            </div>
                        </div>

                        <!-- Current Password Field -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="current_password" class="form-control" id="floatingInput" placeholder="Current Password" required>
                                <label for="floatingInput">Current Password</label>
                            </div>
                        </div>

                        <!-- New Password Field (Optional Update) -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="floatingInput" placeholder="New Password">
                                <label for="floatingInput">New Password (Leave blank to keep current password)</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-success">Update Local Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

