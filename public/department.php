<?php include './includes/header.php'; ?>
<div class="breadcrumb-container mt-3 pt-5">
<?php include 'breadcrumb.php'; ?>
</div>
<div class="container-fluid">
  <h4 class="text-center m-2 py-3">Departments</h4>
  <?php
  $sql = "SELECT * FROM departments WHERE status = 'Active'";
  $departments = query($sql);

  if (mysqli_num_rows($departments) > 0): ?>
  <div class="row my-4 mx-2">
    <?php while ($department = mysqli_fetch_assoc($departments)): ?>
    <div class="col-md-4 mb-3">
      <div class="card card-box h-100">
        <div class="card-body text-center pb-0">
        <img src="<?php echo '../assets/img/uploads/' . $department['logo']; ?>" alt="<?php echo htmlspecialchars($department['department_name']); ?>" class="img-fluid rounded-circle" style="height: 100px; width: 100px;">
          <div class="card-title text-dark fw-bold p-1 m-0" ><?php echo htmlspecialchars($department['department_name']); ?></div>
          <hr class="horizontal dark m-0">
          <p class="card-text text-sm p-1 mb-3"><?php echo ($department['description']); ?></p>
        </div>
        <a href="view_department.php?id=<?php echo $department['id']; ?>" class="btn p-2 mx-5 ">Read more</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>
<?php include './includes/footer.php'; ?>