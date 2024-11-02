<?php include './includes/header.php';

 if (isset($_GET['id'])) {
   $department_id = $_GET['id'];
   $sql = "SELECT department_name, department_head, description, contact_phone, email, location, department_banner, logo FROM departments WHERE id = ?";
   $department = query($sql, [$department_id])->fetch_assoc();

   $programs_sql = "SELECT * FROM programs WHERE department_id = ?";
   $programs = query($programs_sql, [$department_id])->fetch_all(MYSQLI_ASSOC);
 }
?>
<div class="breadcrumb-container mt-3 pt-5">
<?php include 'breadcrumb.php'; ?>
</div>
<div class="container-fluid mb-5">

  <div class="row my-4">
    <div class="col-md-9 mb-3">
      <div class="card h-100 shadow-sm ">
        <img src="../assets/img/uploads/<?php echo $department['department_banner']; ?>" class="card-img-top " style="height: 300px; width:auto;" alt="">
        <div class="card-body">
          <h5 class="card-title "><?php echo $department['department_name']; ?></h5>
          <p class="card-text"><?php echo $department['description']; ?></p>
        </div>      
      </div>
    </div>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-center">Information</h5>
          <hr class="horizontal dark">
          <div class="logo d-flex justify-content-center" >
            <img src="../assets/img/logos/<?php echo $department['logo']; ?>" class="card-img mb-3 rounded-circle" style="height: 150px; width: 150px;" alt="">
          </div>
          
          <h6 class="fw-bold"><i class="fas fa-map-marker-alt"></i> Location:</h6>
          <p><?php echo $department['location']; ?></p>
          <h6 class="fw-bold"><i class="fas fa-user"></i> Department Head:</h6>
          <p><?php echo $department['department_head']; ?></p>
          <h6 class="fw-bold"><i class="fas fa-phone"></i> Contact Phone:</h6>
          <p class="card-text">  <?php echo $department['contact_phone']; ?></p>
          <h6 class="fw-bold"><i class="fas fa-envelope"> </i> Contact Email:</h6>
          <p class="card-text">  <?php echo $department['email']; ?></p>
          <h6 class="fw-bold"><i class="fas fa-globe"> </i> Visit Us:</h6>
          <p class="card-text"> <?php //echo $department['email']; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- programs og the department -->
<div class="container-fluid" style="background-color: #A3D7BC;">
    <div class="title text-center p-4">
        <h4 class="text-white">Programs</h4>
    </div>
    <div class="row mx-4 pb-4">
        <?php if (!empty($programs)) : ?>
            <?php foreach ($programs as $program) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card card-box h-100">
                        <img src="../assets/img/uploads/<?php echo ($program['banner_image']); ?>" class="card-img-top" style="height: 150px;" alt="Banner">
                        <div class="card-body pb-2">
                            <h6 class="card-title"><?php echo ($program['title']); ?></h6>
                            <div class="multi-line-text-truncate-3 mt-0">
                                <?php echo ($program['description']); ?>
                            </div>
                            
                        </div>
                        <a href="view_program.php?id=<?php echo $program['id']; ?>" class="btn py-2 mx-5">Learn More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <p class="text-center text-white">No programs available.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="container-fluid bg-white mb-5" id="news-container">
  <div class="row pb-4">
    <div class="col-md-12">
      <div class="title text-center p-4">
        <h4 class="text-black">News and Updates</h4>
      </div>
      <div class="card mb-3 shadow-sm border">
        <div class="row">
          <div class="col-md-4 h-100">
            <img src="../assets/img/curved-images/curved-11.jpg" class="img-fluid rounded-start">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title">This is an  Update</h5>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore totam sunt dicta necessitatibus? Veniam eaque ullam nesciunt reiciendis esse molestias, repellendus ipsa alias. Deserunt, illum. Amet voluptate vero magni porro. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vitae, inventore! Suscipit sunt, tenetur delectus quaerat iste, ea repellat labore deserunt doloribus inventore fugiat ad repellendus ullam, placeat vero ipsam earum.</p>
              <p class="card-text float-end"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
            
          </div>
        </div>
      </div>
      <div class="card mb-3 shadow-sm border">
        <div class="row">
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title">This is an  Update</h5>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore totam sunt dicta necessitatibus? Veniam eaque ullam nesciunt reiciendis esse molestias, repellendus ipsa alias. Deserunt, illum. Amet voluptate vero magni porro. Lorem ipsum dolor, sit amet consectetur adipisicing elit. </p>
              <p class="card-text float-end"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
          </div>
          <div class="col-md-4 h-100">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="../assets/img/curved-images/curved-11.jpg" class="d-block w-100 rounded-end" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="../assets/img/curved-images/curved-10.jpg" class="d-block w-100 rounded-end" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="../assets/img/curved-images/curved-8.jpg" class="d-block w-100 rounded-end" alt="...">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include './includes/footer.php'; ?>