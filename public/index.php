  <?php include './includes/header.php'; 
  $programs = query("SELECT p.id, p.title, p.description, p.banner_image, d.department_name FROM programs p INNER JOIN departments d ON p.department_id = d.id WHERE d.status = 'Active' LIMIT 4")->fetch_all(MYSQLI_ASSOC);

  $latestNews = query("SELECT id, title, small_description, created_at FROM news WHERE status = 'approved' ORDER BY created_at DESC LIMIT 4")->fetch_all(MYSQLI_ASSOC);
  ?>
  <div class="video-container">
    <img src="../assets/img/website_img/IMG_8794.PNG" alt="header" style="height: 100%; width: 100%; object-fit: cover;">
    <div class="video-overlay">
        <div class="text-center mt-3">
            <img src="../assets/img/default_img/degault_logo.png" alt="Logo" class="mt-3" style="max-width: 150px; height: auto;">
            <img src="../assets/img/icon.png" alt="Logo" class="mt-3" style="max-width: 150px; height: auto;">
        </div>
      <div>
        <h3 class="text-white text-center text-uppercase">Provincial Government of Dinagat Islands: Programs and Services</h1>
      </div>
      <div >
        <p class="text-white text-center fs-5">Your gateway to accessing the programs and services of the Provincial Governor's Office in Dinagat Islands</p>
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
<div class="container-fluid mt-3 px-3">
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
                if (count($programs) > 0):
                  foreach ($programs as $program) :
                  ?>
                  <div class="col-md-6 col-sm-12 mb-2 px-2">
                    <div class="card card-box h-100 rounded">
                      <img src="../assets/img/uploads/<?php echo $program['banner_image']; ?>" class="img-fluid rounded-top" style="height: 150px; object-fit: cover" alt="Banner">
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
                  <?php endforeach; 
                else:
                  ?>
                  <div class="col-md-12 text-center">
                    <p>No available programs.</p>
                  </div>
                <?php endif; ?> 
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
                <?php if (!empty($latestNews)): ?>
                  <?php foreach ($latestNews as $news): ?>
                    <div class="card shadow-sm border mb-2 rounded-2" style="height: 180px;">
                        <div class="card-body p-2">
                            <div class=" text-center"> 
                                <p class=" fw-bold mb-0" style="font-size:0.8rem;"><?php echo htmlspecialchars($news['title']); ?></p>
                            </div>
                            <hr class="horizontal dark m-0">
                            <div class="multi-line-text-truncate-3 mt-1 mx-1 mb-0">
                                <p >
                                    <?php echo htmlspecialchars($news['small_description']); ?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between text-sm p-3">
                            <a href="news_update.php?id=<?php echo $news['id']; ?>" class="stretched-link text-danger">Read More</a>
                            <span class="fw-light text-muted"><?php echo date('m/d/Y', strtotime($news['created_at'])); ?></span> 
                        </div>
                        </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="d-flex justify-content-center">
                    <p class="text-center">No latest news available.</p>
                  </div>
                <?php endif; ?>
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
      <div class="row my-5 d-flex align-items-center fade-in-section">
        <div class="col-md-6 mb-4 fade-in-left">
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
        <div class="col-md-6 mb-4 fade-in-right">
          <h2 class="mb-4 ps-5">About Us</h2>
          <p class="px-5">We are dedicated to providing a streamlined, efficient, and secure platform that simplifies the process of accessing essential government services and programs. Our mission is to ensure that everyone can easily navigate and benefit from the resources available to them.</p>
        </div>
      </div>
    </div>

    <div class="about-us text-center my-5">
      <h2>Benefits</h2>
      <hr class="horizontal dark m-0">
    </div>
    <div class="row mb-5">
      <div class="col-md-4 fade-in-bottom">
        <div class="card benefit-card mb-4 h-100">
          <div class="card-body text-center">
            <img src="../assets/img/logos/secure.svg" alt="access" style="height: 100px;" class="img-fluid benefit-logo mb-2">
            <h5>Easy to Access</h5>
            <p>Your information is protected with top-notch security measures to ensure privacy and safety.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 fade-in-bottom">
        <div class="card benefit-card mb-4 h-100">
          <div class="card-body text-center">
            <img src="../assets/img/logos/time.svg" alt="access" style="height: 100px;" class="img-fluid benefit-logo mb-2">
            <h5>Time-Saving</h5>
            <p>Save time with our efficient online system, designed to provide quick and easy access to government programs.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 fade-in-bottom">
        <div class="card benefit-card mb-4 h-100">
          <div class="card-body text-center">
            <img src="../assets/img/logos/process.svg" alt="access" style="height: 100px;" class="img-fluid benefit-logo mb-2">
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
  <div class="row d-flex  justify-content-center align-items-center text-center">
    <div class="col-md-6 my-2" > 
     <div class="card h-100" style="background-color: rgba(0,0,0,0.4);">
        <div class="text-white mb-5">
            <div class="card-header text-center pb-0 bg-transparent">
                <h3 class="text-white mb-5">Contact Information</h3>
            </div>
           <div class="card-body py-1">
               <div class="mb-4">
                   <p class="mb-0 text-white">
                    <i class="fa fa-map-marker"></i> 
                    Address:
                    </p>
                    <p class="text-white">123 Main Street, City, Country</p>
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
     </div>
    </div>
    <div class="col-md-6 my-2">
        <div class="card h-100">
            <div class="card-header text-center pb-0">
                <h3>Send Us Your Feedback</h3>
            </div>
            <div class="card-body py-1">
                <form id="messageForm" method="POST" action="submit_message.php">
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
                        <select class="form-control" id="department" name="department_id">
                            <option value="">General (No Specific Department)</option>
                            <?php
                            $query = "SELECT id, department_name FROM departments"; 
                            $result = query($query); 
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['department_name'] . "</option>";
                            }
                            ?>
                        </select>
                        <label for="department">Department (Optional)</label>
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

