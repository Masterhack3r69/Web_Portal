<?php
$title = "Edit Local Admin";
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
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $admin['password']; 
    $department_id = $_POST['department_id'];

    try {

        $update_sql = "UPDATE admin SET username = ?, email = ?, password = ?, department_id = ? WHERE id = ?";
        $result = query($update_sql, [$username, $email, $password, $department_id, $admin_id]);

        if ($result) {
            $_SESSION['success_message'] = "Local admin updated successfully!";
        } else {
            $_SESSION['warning_message'] = "Failed to update Local admin!";
        }
     } catch (Exception $e) {
         $_SESSION['error_message'] = "Error: " . $e->getMessage();
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
                    <a href="local_admin.php" class="btn back-btn bg-gradient-dark float-end">Back</a>
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

                        <!-- Password Field (Optional Update) -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="floatingInput" placeholder="Password">
                                <label for="floatingInput">Password (Leave blank to keep current password)</label>
                            </div>
                        </div>

                        <!-- Department Dropdown -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select name="department_id" id="department" class="form-select" required>
                                    <option value="" disabled>Select Department</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?php echo $department['id']; ?>" 
                                            <?php echo ($department['id'] == $admin['department_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($department['department_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="floatingInput">Assign Department</label>
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
