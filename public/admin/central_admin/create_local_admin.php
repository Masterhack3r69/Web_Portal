<?php
$title = "Create Local Admin";
include '../includes/header.php';

$department_sql = "SELECT * FROM departments";
$departments = query($department_sql)->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $department_id = $_POST['department_id'] ?? null; 
    $central_admin_id = $_SESSION['user_id'];

    // Validate department selection
    if (empty($department_id)) {
        showAlert("Please assign a department before creating a local admin.", "warning");
    } else {
        try {
            $sql = "INSERT INTO admin (username, password, email, admin_type, department_id) VALUES (?, ?, ?, 'local', ?)";
            $result = query($sql, [$username, $password, $email, $department_id]);

            if ($result) {
                $_SESSION['success_message'] = "Local admin updated successfully!";
            } else {
                $_SESSION['warning_message'] = "Failed to update local admin.";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5>
                    Create Local Admin
                    <a href="local_admin.php" class="btn back-btn bg-gradient-dark float-end">Back</a>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="#">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username" required>
                                <label for="floatingInput">Username</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="email" class="form-control" id="floatingInput" placeholder="Email" required>
                                <label for="floatingInput">Email</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="floatingInput" placeholder="Password" required>
                                <label for="floatingInput">Password</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select name="department_id" id="department" class="form-select" required>
                                    <option value="" selected disabled>Select a department</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?php echo $department['id']; ?>">
                                            <?php echo htmlspecialchars($department['department_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="department">Assign Department</label>
                            </div>    
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-success">Create Local Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
