<?php
include './includes/header.php';
$departments = query("SELECT id, department_name FROM departments")->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT p.id, p.title, p.description, p.banner_image, d.department_name FROM programs p INNER JOIN departments d ON p.department_id = d.id";
$programs = query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<div class="breadcrumb-container mt-3 pt-5 ">
<?php include 'breadcrumb.php'; ?>
</div>
<div class="container-fluid mb-5">
<h4 class="text-center m-2 py-3">Programs</h4>
  <div class="row my-4 mx-2">
    <div class="col-12">
      <div class="form-group mb-3">
        <label for="department_id" >Filter by Department</label>
        <select class="form-select" style="width: 100%; max-width: 400px" id="department_id" onchange="filterPrograms(this.value)">
          <option value="">All</option>
          <?php foreach ($departments as $department) : ?>
            <option value="<?php echo $department['id']; ?>"><?php echo $department['department_name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="row my-4 mx-2" id="program-container">
    <?php foreach ($programs as $program) :
    ?>
    <div class="col-md-4 mb-3">
      <div class="card card-box h-100">
        <img src="../assets/img/uploads/<?php echo $program['banner_image']; ?>" class="card-img-top" style="height: 150px;" alt="Banner">
        <div class="card-body text-center pb-0">
          <div class="card-title p-1 m-0 text-dark fw-bold" ><?php echo $program['title']; ?></div>
          <hr class="horizontal dark m-0">
          <div class="multi-line-text-truncate-4 mt-0">
           <p class="card-text p-1 mb-3" ><?php echo $program['description']; ?></p>
          </div>
        </div>
        <a href="view_program.php?id=<?php echo $program['id']; ?>" class="btn p-2 mx-5 ">Read more</a>
      </div>
    </div>
    <?php endforeach; ?>  
    </div>
</div>
</div>
<script>
  function filterPrograms(department_id) {
    $.ajax({
      url: 'filter_programs.php',
      method: 'POST',
      data: {department_id: department_id},
      success: function(response) {
        $('#program-container').html(response);
      }
    });
  }
</script>

<?php include './includes/footer.php'; ?>
