  <?php include './includes/header.php'; 
  $programs = query("SELECT p.id, p.title, p.description, p.banner_image, d.department_name FROM programs p INNER JOIN departments d ON p.department_id = d.id LIMIT 4")->fetch_all(MYSQLI_ASSOC);

  $latestNews = query("SELECT id, title, small_description, created_at FROM news WHERE status = 'approved' ORDER BY created_at DESC LIMIT 4")->fetch_all(MYSQLI_ASSOC);
  ?>
  <div class="video-container">
    <img src="../assets/img/website_img/IMG_8794.PNG" alt="header"  style="height: 100%; width: 100%; object-fit: cover;">
    <div class="video-overlay">
      <div>
        <h1  class="text-white">Dinagat Connect</h1>
      </div>
      <div >
        <h4 class="text-white">Your Access Point to Provincial Programs</h4>
      </div>
    </div>
  </div> 
  <?php include 'breadcrumb.php'; ?>
  
<div class="department-container mb-5">
  <div class="title text-center pt-3">
      <h5>DEPARTMENTS</h5>
      <hr class="horizontal light mt-0">
  </div>
  <?php
    $sql = "SELECT id, department_name, logo FROM departments WHERE status = 'Active'";
    $logos = query($sql);
    if ($logos && $logos->num_rows > 0): ?>
        <div class="logos-slider">
            <div class="logos-wrapper">
                <?php while ($department = $logos->fetch_assoc()): ?>
                    <div class="logo-container me-3 mb-3">
                        <a href="view_department.php?id=<?= $department['id']; ?>" class="text-decoration-none">
                            <img src="<?php echo '../assets/img/uploads/' . $department['logo']; ?>" class="d-inline-block logo-img" alt="<?= htmlspecialchars($department['department_name']); ?>">
                            <div class="overlay-text"><?= htmlspecialchars($department['department_name']); ?></div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else: ?>
        <p>No active departments available.</p>
    <?php endif; ?>
</div>
<div class="container-fluid mt-3">
  <div class="row mb-5">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-9 col-sm-12 mb-3" >
          <div class="card h-100" id="featured">
            <div class="title card-header pb-0">
              <h5>
                Featured Programs
              </h5>
            </div>
            <div class="card-body pb-0">  
              <div class="row">
              <?php
                foreach ($programs as $program) :
                ?>
                <div class="col-md-6 col-sm-12 mb-2">
                  <div class="card card-box h-100">
                    <img src="../assets/img/uploads/<?php echo $program['banner_image']; ?>" class="card-img-top" style="height: 150px;" alt="Banner">
                  <div class="card-body pb-2 pt-1">
                    <div class="card-title p-1 m-0 text-dark fw-bold text-center" ><?php echo $program['title']; ?></div>
                    <hr class="horizontal dark m-0">
                      <div class="multi-line-text-truncate-4" >
                        <?php echo $program['description']; ?>
                        </div>
                      </div>
                      <a href="view_program.php?id=<?php echo $program['id']; ?>" class="btn ms-3 py-2" style="width: fit-content;">View</a>
                  </div>
                </div>
                <?php endforeach; ?> 
              </div>
            </div> 
            <div class="text-center m-0">
              <a class="btn link-btn p-2 px-3" type="button">See More</a>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-12">
          <div class="card h-100">
              <div class="title card-header pb-0">
                  <h5>
                      News and Updates
                  </h5>
              </div>
              <div class="card-body pb-0">
                <?php foreach ($latestNews as $news): ?>
                  <div class="card shadow-sm border mb-2 rounded-2" style="height: 180px;">
                      <div class="card-body p-2">
                          <div class=" text-center"> 
                              <p class="text-sm fw-bold mb-0"><?php echo htmlspecialchars($news['title']); ?></p>
                          </div>
                          <hr class="horizontal dark m-0">
                          <div class="multi-line-text-truncate-3 mt-1 mx-1">
                              <p class="small">
                                  <?php echo htmlspecialchars($news['small_description']); ?>
                              </p>
                          </div>
                      </div>
                      <div class="d-flex justify-content-between text-sm p-3">
                          <a href="news_update.php?id=<?php echo $news['id']; ?>" class="stretched-link text-danger">Read More</a>
                          <span class="fw-light text-muted"><?php echo date('m/d/Y', strtotime($news['created_at'])); ?></span> 
                      </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <div class="text-center m-0">
                <a class="btn link-btn p-2 px-3" type="button">See More</a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid bg-white" id="about">
  <div class="row">
    <div class="col-md-12"> 
      <!-- Benefits Section -->
      <div class="row my-5 d-flex align-items-center" >
        <div class="col-md-6 mb-4"> 
          <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="../assets/img/website_img/2024110217160109.jpg" class="d-block w-100 carousel-img rounded" alt="About Us Slide 1">
              </div>
              <div class="carousel-item">
                <img src="../assets/img/website_img/pexels-olly-3760069.jpg" class="d-block w-100 carousel-img rounded" alt="About Us Slide 2">
              </div>
              <div class="carousel-item">
                <img src="../assets/img/website_img/pexels-pixabay-265087.jpg" class="d-block w-100 carousel-img rounded" alt="About Us Slide 3">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4"> 
          <h2 class="mb-4 ps-5">About Us</h2>
          <p class="px-5">We are dedicated to providing a streamlined, efficient, and secure platform that simplifies the process of accessing essential government services and programs. Our mission is to ensure that everyone can easily navigate and benefit from the resources available to them.</p>
        </div>
      </div>
      <div class="about-us text-center m-5  ">
        <h2>Benefits</h2>
        <hr class="horizontal dark m-0">
      </div>
      <div class="row mb-5"> 
        <div class="col-md-4">
          <div class="card benefit-card mb-4 h-100"> 
            <div class="card-body text-center">
              <img src="../assets/img/logos/access.gif" alt="access" style="height: 100px;" class="img-fluid benefit-logo">
              <h5>Easy to Access</h5>
              <p>Your information is protected with top-notch security measures to ensure privacy and safety.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card benefit-card mb-4 h-100"> 
            <div class="card-body text-center">
              <img src="../assets/img/logos/time.gif" alt="access" style="height: 100px;" class="img-fluid benefit-logo">
              <h5>Time-Saving</h5>
              <p>Save time with our efficient online system, designed to provide quick and easy access to government programs.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card benefit-card mb-4 h-100"> 
            <div class="card-body text-center">
              <img src="../assets/img/logos/process.gif" alt="access" style="height: 100px;" class="img-fluid benefit-logo">
              <h5>Simplified Process</h5>
              <p>Our portal offers a simplified application process, reducing the time and effort needed to access services.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="contact" class="container-fluid py-5" style="background-image: url('../assets/img/website_img/IMG_20241102_171716_284.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <div class="row d-flex  justify-content-center text-center">
    <div class="col-md-6 py-4 rounded-3 mb-3" style=" background-color: rgba(0,0,0,0.4);"> 
      <div class="text-white mb-5">
        <h3 class="text-white mb-5">Contact Information</h3>
        <div class="mb-4">
           <p class="mb-0 text-white">
            <i class="fa fa-map-marker"></i> 
            Address:
          </p>
           <p  class="text-white">123 Main Street, City, Country</p>
        </div>
        <div class="mb-4">
           <p class="mb-0 text-white">
            <i class="fa fa-envelope"></i> 
            General Support: 
          </p>
          <a href="mailto:support@example.com" class="text-info">support@example.com</a>
        </div>
        <div class="mb-4">
           <p class="mb-0 text-white">
            <i class="fa fa-link"></i> 
            Visit Website: 
          </p>
          <a href="https://example.com" target="_blank" class="text-info">example.com</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h3>Send Us Your Feedback</h3>
            </div>
            <div class="card-body">
                <form id="messageForm">
                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            class="form-control"
                            id="floatingInput"
                            name="email"
                            placeholder="name@example.com"
                            required
                        />
                        <label for="floatingInput">Email address</label>
                        <div id="emailError" class="text-danger" style="display: none;">Please enter a valid email address.</div>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-control" id="feedbackType" name="feedback_type" required>
                            <option value="">Select Feedback Type</option>
                            <option value="Bug Report">Bug Report</option>
                            <option value="Feature Request">Feature Request</option>
                            <option value="General Feedback">General Feedback</option>
                        </select>
                        <label for="feedbackType">Feedback Type</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea
                            class="form-control"
                            placeholder="Leave your feedback here"
                            id="floatingTextarea2"
                            name="message"
                            style="height: 100px"
                            required
                        ></textarea>
                        <label for="floatingTextarea2">Comments</label>
                    </div>
                    <button type="submit" class="btn">Send Feedback</button>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
<?php include './includes/footer.php'; ?>
<script>
    document.getElementById("messageForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const emailInput = document.getElementById('floatingInput');
        const emailError = document.getElementById('emailError');

        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Validate email
        if (!emailPattern.test(emailInput.value)) {
            emailError.style.display = 'block'; 
            return; 
        }

        emailError.style.display = 'none'; 

        const formData = new FormData(this); 
        fetch("submit_message.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.text())
            .then((data) => {
                Swal.fire({
                    title: "Success!",
                    text: data,
                    icon: "success",
                    confirmButtonText: "OK",
                });
                document.getElementById("messageForm").reset(); 
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.fire({
                    title: "Error!",
                    text: "An error occurred. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            });
    });
</script>
