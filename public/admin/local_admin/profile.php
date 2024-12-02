<?php
ob_start();
$title = "Profile";
include '../includes/header.php';

// Fetch the logged-in admin's information
$admin_id = $_SESSION['user_id'];
$admin = query("SELECT * FROM admin WHERE id = ?", [$admin_id])->fetch_assoc();

// Fetch the department details
$department = query("SELECT id, department_name, description, department_head, contact_phone, email, logo, location, department_banner FROM departments WHERE id = ?", [$admin['department_id']])->fetch_assoc();
?>

<div class="row mb-3">
  <!-- Admin Profile Card -->
  <div class="col-md-4 mb-3">
    <div class="card h-100">
      <div class="card-body text-center">
        <div class="d-flex justify-content-center">
          <img src="../../../assets/img/default_img/avatar.svg" class="img-fluid rounded-circle border" width="150" />
        </div>
        <hr class="horizontal dark m-2">
        <h5 class="card-title my-2"><?php echo htmlspecialchars($admin['username']); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars($admin['email']); ?></p>
        <div class="">
          <a href="edit_profile.php?id=<?php echo $admin['id']; ?>" class="btn bg-gradient-primary py-2 px-3 mb-0">Edit Profile</a>
          <a href="edit_department.php?id=<?php echo $department['id']; ?>" class="btn bg-gradient-info py-2 px-3 mb-0">Edit Department</a>
        </div>
        
      </div>
    </div>
  </div>
  <div class="col-md-8 mb-3">
    <div class="card h-100">
      <div class="position-relative" style="height: 325px;">
        <?php if (!empty($department['department_banner'])): ?>
          <div class="card-img" style="height: 100%; width: 100%; position: absolute; background-color: rgba(0, 0, 0, 0.3);"></div>
            <img src="../../../assets/img/uploads/<?php echo htmlspecialchars($department['department_banner']); ?>" class="card-img img-fluid" style="height: 100%; width: 100%; object-fit: cover;" alt="Department Banner">
        <?php endif; ?>
        <?php if (!empty($department['logo'])): ?>
          <div style="top: 40%;" class="position-absolute start-50 translate-middle">
            <img src="../../../assets/img/uploads/<?php echo htmlspecialchars($department['logo']); ?>" class="img-fluid rounded-circle" width="130" alt="Department Logo">
          </div>
        <?php endif; ?>
        <div class="position-absolute bottom-0 start-0 p-3">
          <h4 class="card-title text-white text-center"><?php echo htmlspecialchars($department['department_name']); ?></h4>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8 mb-3">
    <div class="card h-100">
      <div class="card-body">
        <p class="text-muted"><?php echo $department['description']; ?></p>
        
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
  <div class="card h-100">
    <div class="card-body py-1">
      <div class="py-3">
        <i class="fa fa-user-tie text-primary me-2"></i>
        <strong >Department Head:</strong><br>
        <span class="mt-2"><?php echo htmlspecialchars($department['department_head']); ?></span>
      </div>
      <hr class="horizontal dark m-0">
      <div class="py-3">
        <i class="fa fa-map-marker-alt text-primary me-2"></i>
        <strong>Location:</strong><br>
        <span class="mt-2"><?php echo htmlspecialchars($department['location']); ?></span>
      </div>
      <hr class="horizontal dark m-0">
      <div class="py-3">
        <i class="fa fa-phone text-primary me-2"></i>
        <strong>Contact Phone:</strong><br>
        <span class="mt-2"><?php echo htmlspecialchars($department['contact_phone']); ?></span>
      </div>
      <hr class="horizontal dark m-0">
      <div class="py-3">
        <i class="fa fa-envelope text-primary me-2"></i>
        <strong>Email:</strong><br>
        <a href="mailto:<?php echo htmlspecialchars($department['email']); ?>"><?php echo htmlspecialchars($department['email']); ?></a>
      </div>
    </div>
  </div>
</div>



</div>

<?php include '../includes/footer.php'; ?>
